<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotMairest extends Mailable
{
    use Queueable, SerializesModels;
    public $title;
    public $details; 

    public function __construct($title, $details) {
         $this->title = $title; 
         $this->details = $details;
         } 

    public function build() { 
             return $this->subject($this->title)->markdown('emails.passwordReset')->with(['detail'=>$this->details]);
             
     }

}