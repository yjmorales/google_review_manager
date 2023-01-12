<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\EmailNotifier;

use Exception;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Common\Communication\HtmlMailer\Mailer;

/**
 * Class acting as a messenger handler.
 *
 * @link https://symfony.com/doc/current/messenger.html
 *
 * The following must be executed `php bin/console messenger:consume async_communication -vv --limit=10 --memory-limit=128M`
 * This command should be managed by supervisord.
 */
#[AsMessageHandler(fromTransport: 'async_communication')]
class EmailNotificationHandler
{
    /**
     * Holds the operational logic to send an email.
     *
     * @var Mailer
     */
    private Mailer $mailer;

    /**
     * @param Mailer $mailer Holds the operational logic to send an email.
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * This function holds the logic to be executed by this handler. Once this handler is executed this function is
     * called.
     *
     * @param EmailNotification $notification Holds the notification to be sent.
     *
     * @return void
     * @throws Exception
     */
    public function __invoke(EmailNotification $notification): void
    {
        try {
            $result = $this->mailer->send($notification->getMailerMessage());
        } catch (Exception $e) {
            // todo: log the failure point.
            throw $e;
        }

        if (false === $result) {
            // todo: log the failure point.
            throw new Exception('Unable to send the email');
        }
    }
}