<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contactusmail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $details; 

    public function __construct($title,$details) {
         $this->title = $title; 
         $this->details = $details;
         } 

    public function build() { 
              return $this->subject($this->title)->markdown('emails.contact_us_mail')->with(['detail'=>$this->details]);
     }

}
