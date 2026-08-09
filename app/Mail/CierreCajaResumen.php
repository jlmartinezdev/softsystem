<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CierreCajaResumen extends Mailable
{
    use Queueable, SerializesModels;

    public $resumen;

    public function __construct(array $resumen)
    {
        $this->resumen = $resumen;
    }

    public function build()
    {
        $nro = $this->resumen['nro_operacion'] ?? '';

        return $this->subject('Cierre de caja — Operación #' . $nro)
            ->view('emails.cierre_caja');
    }
}
