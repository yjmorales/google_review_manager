<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\EmailNotifier;

use Common\Communication\HtmlMailer\MailerMessage;

/**
 * Represents an email notification. this is the message to be handled by a symfony message handler.
 */
class EmailNotification
{
    /**
     * Holds the email data to be sent. Represents an email mesage.
     *
     * @var MailerMessage
     */
    private MailerMessage $mailerMessage;

    /**
     * @param MailerMessage $mailerMessage Holds the email data to be sent. Represents an email mesage.
     */
    public function __construct(MailerMessage $mailerMessage)
    {
        $this->mailerMessage = $mailerMessage;
    }

    /**
     * @return MailerMessage
     */
    public function getMailerMessage(): MailerMessage
    {
        return $this->mailerMessage;
    }
}