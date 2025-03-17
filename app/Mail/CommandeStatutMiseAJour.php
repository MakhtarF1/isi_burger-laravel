<?php

namespace App\Mail;

use App\Models\Commande;
use App\Models\Statut;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommandeStatutMiseAJour extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * La commande.
     *
     * @var \App\Models\Commande
     */
    public $commande;

    /**
     * L'ancien statut.
     *
     * @var \App\Models\Statut
     */
    public $ancienStatut;

    /**
     * Create a new message instance.
     */
    public function __construct(Commande $commande, Statut $ancienStatut)
    {
        $this->commande = $commande;
        $this->ancienStatut = $ancienStatut;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à jour de votre commande #' . $this->commande->numero_commande,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.commandes.statut-mise-a-jour',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}