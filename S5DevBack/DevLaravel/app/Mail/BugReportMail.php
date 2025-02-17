<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BugReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $description;

    public function __construct($description)
    {
        $this->description = $description;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->to('dontforgetmepro@gmail.com') // Remplace avec l'adresse du support
            ->subject('Nouveau rapport de bug')
            ->view('emails.bug_report')
            ->with(['description' => $this->description]);
    }
}
