<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailreset extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $user_id; 
    public $details; 

    public function __construct($title, $user_id, $details) {
         $this->title = $title; 
         $this->user_id= $user_id;
         $this->details = $details;
         } 

    public function build() { 
              return $this->subject($this->title)->markdown('emails.verify_user')->with(['detail'=>$this->details]);
     }

}
