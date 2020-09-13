<?php

namespace E4nar\Xm\Mail;

/**
 * If you have mailable classes that you want to always be queued,
 * you may implement the ShouldQueue contract on the class. Now,
 * even if you call the send method when mailing, the mailable will
 * still be queued since it implements the contract:
 */
use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class FormSubmittedMail extends Mailable  {
	
    use Queueable, SerializesModels;

    public $companyName;
    public $startDate;
    public $endDate;
    public $email;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($formData) {
    	
        $this->startDate   = Arr::get($formData, 'startDate');
        
        $this->endDate     = Arr::get($formData, 'endDate');
        
		$this->companyName = Arr::get($formData, 'company_name');
	
		$this->email = Arr::get($formData, 'email');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
    	
        return $this->view('emails.form_submitted')->subject($this->companyName);
        
    }
}
