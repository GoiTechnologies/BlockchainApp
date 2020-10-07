<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $demo;

    public function __construct($demo)
    {
        $this->demo = $demo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->from('sender@example.com')
                  ->view('emails.resset_p')
                  //->text('emails.resset_p_plain')
                  ->subject('Reset Password Notification') 
                  ->with(
                    [
                          'testVarOne' => '1',
                          'testVarTwo' => '2',
                    ])
                    ->attach(public_path('/logo_green.png'), [
                            'as' => 'logo_green.png',
                            'mime' => 'image/png',
                    ]);
    }
}
