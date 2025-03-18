<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouvelleCommandeNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * La commande.
     *
     * @var \App\Models\Commande
     */
    public $commande;

    /**
     * Create a new message instance.
     */
    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle commande #' . $this->commande->numero_commande,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.commandes.nouvelle-commande',
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