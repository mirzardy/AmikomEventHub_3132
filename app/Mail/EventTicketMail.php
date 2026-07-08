<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Data transaksi yang akan dikirim ke email.
     *
     * @var \App\Models\Transaction
     */
    public $transaction;

    /**
     * Membuat instance mail baru.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Menentukan subjek email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Resmi Anda: ' . $this->transaction->event->title,
        );
    }

    /**
     * Menentukan view yang digunakan sebagai isi email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
        );
    }
}