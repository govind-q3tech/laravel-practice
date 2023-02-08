<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class ManuMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The template instance.
     *
     * @var template
     */
    public $template;

    /**
     * The replacement instance.
     *
     * @var replacement
     */
    public $replacement;
    public $subject;
    public $bodyhtml;
    public $attachement;
    public $business;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($replacementVars)
    {
        if (!empty($replacementVars['hooksVars'])) {
            foreach ($replacementVars['hooksVars'] as $hook => $var) {
                $replacement['##' . $hook . '##'] = $var;
            }
        }
        $this->template = $replacementVars['template'];
        $this->replacement = $replacement;

        if (isset($replacementVars['subject']))
            $this->subject = $replacementVars['subject'];

        if (isset($replacementVars['bodyhtml']))
            $this->bodyhtml = $replacementVars['bodyhtml'];

        if (isset($replacementVars['attachement']))
            $this->attachement = $replacementVars['attachement'];

        if (isset($replacementVars['business']))
            $this->business = $replacementVars['business'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $message = $this->buildMessage($this->template);
        if ($message) {
            $subject = $message['subject'];
            $content = $message['message'];
        } else {
            $subject = $this->subject;
            $content = $this->bodyhtml;
        }
        $email = $this->view('emails.echo');
        // $email->from(env("get.FROM_EMAIL"), config("get.SYSTEM_APPLICATION_NAME"));
        $email->from('kumawat.govind@gmail.com', config("get.SYSTEM_APPLICATION_NAME"));
        $email->subject($subject);
        $email->with(['content' => $content]);

        if (!empty($this->attachement)) {
            if (isset($this->attachement['specify'])) {
                $email->attach($this->attachement['fullpath'], $this->attachement['specify']);
            } else {
                $email->attach($this->attachement['fullpath']);
            }
        }
        return $email;
    }



    public function buildMessage($email_type = null)
    {
        if (!$email_type)
            return false;

        $emailTemplate = EmailTemplate::with(['email_hook', 'email_preference'])->whereHas('email_hook', function ($query) use ($email_type) {
            $query->where('slug', '=', $email_type);
        })->first();
        if (empty($emailTemplate)) {
            return false;
        }
        $fullUrl = \App::make('url')->to('/');
        $replacement = $this->replacement;
        // $logo = \URL::asset('/storage/settings/' . config('get.MAIN_LOGO'));
        $logo = \URL::asset('/dist/img/site_logo.png');

        $default_replacement = [
            '##SYSTEM_APPLICATION_NAME##' => config("get.SYSTEM_APPLICATION_NAME"),
            '##BASE_URL##' => $fullUrl,
            '##SYSTEM_LOGO##' => $logo,
            '##COPYRIGHT_TEXT##' => "Copyright &copy; " . date("Y") . " " . config("get.SYSTEM_APPLICATION_NAME"),
        ];
        $message_body = str_replace('##EMAIL_CONTENT##', $emailTemplate->description, $emailTemplate->email_preference->layout_html);
        $message_body = str_replace('##EMAIL_FOOTER##', nl2br($emailTemplate->footer_text), $message_body);
        $message_body = strtr($message_body, $default_replacement);
        $message_body = strtr($message_body, $replacement);
        $subject = strtr($emailTemplate->subject, $default_replacement);
        $subject = strtr($subject, $replacement);
        return $message = ['message' => $message_body, 'subject' => $subject];
    }
}
