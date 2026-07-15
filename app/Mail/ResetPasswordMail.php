<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;

    /**
     * @param string $token Token reset mentah (belum di-hash) untuk dimasukkan ke link.
     * @param string $email Email tujuan, dipakai untuk memvalidasi token saat reset.
     */
    public function __construct(string $token, string $email)
    {
        // route() otomatis pakai APP_URL dari .env (mis. https://kafetani.store),
        // jadi link di email selalu ikut domain production tanpa hardcode.
        $this->resetUrl = route('password.reset.form', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Akun Kafetani',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
        );
    }
}
