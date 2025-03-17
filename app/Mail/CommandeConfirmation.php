<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CommandeConfirmation extends Mailable implements ShouldQueue
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
        // Charger explicitement toutes les relations nécessaires
        $this->commande = Commande::with(['user', 'statut', 'produits'])->find($commande->id);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        try {
            return $this->subject('Confirmation de votre commande #' . $this->commande->numero_commande)
                        ->view('emails.commandes.confirmation');
        } catch (\Exception $e) {
            Log::error('Erreur dans CommandeConfirmation::build: ' . $e->getMessage());
            throw $e;
        }
    }
}