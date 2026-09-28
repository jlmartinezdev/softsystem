<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResumenGerencialMail extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;

    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

    public function build()
    {
        $desde = $this->datos['desde'] ?? '';
        $hasta = $this->datos['hasta'] ?? '';
        $empresa = $this->datos['empresa'] ?? 'SoftSystem';

        return $this->subject("Resumen Gerencial ($desde al $hasta) — $empresa")
            ->view('emails.resumen_gerencial');
    }
}
