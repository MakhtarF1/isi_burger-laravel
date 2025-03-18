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
     * L'ID de la commande.
     */
    public $commandeId;

    /**
     * Créer une nouvelle instance de message.
     *
     * @param Commande $commande
     */
    public function __construct(Commande $commande)
    {
        // Stocker l'ID de la commande plutôt que l'objet complet pour éviter les problèmes de sérialisation
        $this->commandeId = $commande->id;
    }

    /**
     * Construire le message.
     */
    public function build()
    {
        try {
            // Récupérer la commande complète avec ses relations
            $commande = Commande::with(['user', 'statut', 'produits', 'paiement'])->findOrFail($this->commandeId);

            // Vérifier que toutes les relations nécessaires sont chargées
            if (!$commande->user || !$commande->statut || !$commande->produits) {
                throw new \Exception("Relations de commande manquantes");
            }

            // Générer le PDF de la facture
            try {
                $pdf = PDF::loadView('pdf.facture', ['commande' => $commande]);

                // Vérifier si le PDF a bien été généré
                if (!$pdf) {
                    throw new \Exception("Échec de la génération du PDF");
                }

                $pdfOutput = $pdf->output();

                // Vérifier que le PDF contient bien du contenu
                if (empty($pdfOutput)) {
                    throw new \Exception("Le PDF généré est vide");
                }

                // Enregistrer le PDF dans le stockage public pour la référence future
                $pdfPath = storage_path('app/public/factures');
                if (!file_exists($pdfPath)) {
                    mkdir($pdfPath, 0755, true); // Créer le répertoire si nécessaire
                }

                $pdfFilePath = $pdfPath . '/facture-' . $commande->numero_commande . '.pdf';
                file_put_contents($pdfFilePath, $pdfOutput);

                // Attacher le PDF à l'email
                return $this->subject('Facture pour votre commande #' . $commande->numero_commande)
                            ->view('emails.commandes.facture', ['commande' => $commande])
                            ->attachData($pdfOutput, 'facture-' . $commande->numero_commande . '.pdf', [
                                'mime' => 'application/pdf',
                            ]);
            } catch (\Exception $pdfError) {
                // Log l'erreur spécifique à la génération du PDF
                Log::error('Erreur lors de la génération du PDF pour la commande #' . $this->commandeId . ': ' . $pdfError->getMessage());
                throw $pdfError;
            }
        } catch (\Exception $e) {
            // Log des erreurs dans le processus global
            Log::error('Erreur dans CommandeFacture::build pour la commande #' . $this->commandeId . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // Récupérer la commande sans le PDF si une erreur survient
            $commande = Commande::with(['user', 'statut'])->findOrFail($this->commandeId);

            // Envoyer l'email sans le PDF en cas d'erreur
            return $this->subject('Facture pour votre commande #' . $commande->numero_commande)
                        ->view('emails.commandes.facture', ['commande' => $commande]);
        }
    }
}