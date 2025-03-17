<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class CommandeFacture extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * La commande.
     */
    public $commande;

    /**
     * Create a new message instance.
     */
    public function __construct(Commande $commande)
    {
        // Stocker l'ID de la commande plutôt que l'objet complet pour éviter les problèmes de sérialisation
        $this->commande = $commande->id;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        try {
            // Récupérer la commande complète avec ses relations au moment de l'envoi
            $commande = Commande::with(['user', 'statut', 'produits', 'paiement'])->findOrFail($this->commande);
            
            // Vérifier que toutes les relations sont chargées
            if (!$commande->user || !$commande->statut || !$commande->produits) {
                throw new \Exception("Relations de commande manquantes");
            }
            
            // Générer le PDF avec un try/catch spécifique
            try {
                $pdf = PDF::loadView('pdf.facture', ['commande' => $commande]);
                
                // Vérifier que le PDF a été généré correctement
                if (!$pdf) {
                    throw new \Exception("Échec de la génération du PDF");
                }
                
                $pdfOutput = $pdf->output();
                
                // Vérifier que le PDF a un contenu
                if (empty($pdfOutput)) {
                    throw new \Exception("Le PDF généré est vide");
                }
                
                // Enregistrer le PDF dans le stockage pour référence future
                $pdfPath = storage_path('app/public/factures');
                if (!file_exists($pdfPath)) {
                    mkdir($pdfPath, 0755, true);
                }
                
                file_put_contents($pdfPath . '/facture-' . $commande->numero_commande . '.pdf', $pdfOutput);
                
                // Attacher le PDF à l'email
                return $this->subject('Facture pour votre commande #' . $commande->numero_commande)
                            ->view('emails.commandes.facture', ['commande' => $commande])
                            ->attachData($pdfOutput, 'facture-' . $commande->numero_commande . '.pdf', [
                                'mime' => 'application/pdf',
                            ]);
            } catch (\Exception $pdfError) {
                Log::error('Erreur spécifique à la génération du PDF: ' . $pdfError->getMessage());
                throw $pdfError;
            }
        } catch (\Exception $e) {
            Log::error('Erreur dans CommandeFacture::build: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            // Récupérer la commande pour l'email sans PDF
            $commande = Commande::with(['user', 'statut'])->findOrFail($this->commande);
            
            // Envoyer l'email sans la pièce jointe en cas d'erreur
            return $this->subject('Facture pour votre commande #' . $commande->numero_commande)
                        ->view('emails.commandes.facture', ['commande' => $commande]);
        }
    }
}