<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class resetPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $code;
    private $name;

    
    public function __construct($code,$name=null)
    {
        $this->code= $code;
        $this->name = $name;
    }

    public function build()
    {

        return $this->subject('Reset Password')->view('mail.reset_password')->with(['code'=>$this->code,'name_user'=>$this->name]);
    }
}
