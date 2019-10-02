<?php

namespace App\Mail\Course;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class CourseCloseCheckMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $context;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $context)
    {
        $this->context = $context;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = '[重要WEB受付、掲載終了コース情報のお知らせ';

        return $this->subject($subject)
                    ->text('batch.email.course-close-check-mail')
                    ->with(['context' => $this->context,]);
    }
}
