<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FactureCommande extends Mailable
{
    use Queueable, SerializesModels;

    public $ligneCommande;

    public function __construct($ligneCommande)
    {
        $this->ligneCommande = $ligneCommande;
    }

    public function build()
    {
        // Générer le PDF
        $pdf = Pdf::loadView('factures.facture', ['ligneCommande' => $this->ligneCommande]);

        return $this->subject('Votre commande a été validée')
            ->view('emails.commande_validee', ['ligneCommande' => $this->ligneCommande])
            ->attachData($pdf->output(), 'facture.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
