<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Landing\Controller;

use App\Api\Core\Controller\TApiController;
use App\Api\Core\Exception\ApiErrorException;
use App\Api\Core\Exception\ApiNormalOperationException;
use App\Api\Core\Model\ApiEmptyResponse;
use App\Core\Controller\BaseController;
use Common\Communication\HtmlMailer\MailerMessage;
use Common\DataManagement\Validator\DataValidator;
use Common\Security\AntiSpam\ReCaptcha\v3\ReCaptchaV3Validator;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\EmailNotifier\EmailNotification;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * API Controller to manages the landing page.
 */
class ApiLandingController extends BaseController
{
    use TApiController;

    /**
     * Sends a message everytime the user send a message by using the contact us form.
     *
     * @param Request              $request
     * @param LoggerInterface      $logger
     * @param ReCaptchaV3Validator $reCaptchaV3Validator
     * @param MessageBusInterface  $bus
     *
     * @return JsonResponse
     * @throws ApiErrorException
     * @throws ApiNormalOperationException
     */
    public function contactUsSendEmail(
        Request $request,
        LoggerInterface $logger,
        ReCaptchaV3Validator $reCaptchaV3Validator,
        MessageBusInterface $bus
    ): JsonResponse {

        /*
         *  Verifies that's not a robot.
         */
        $this->_validateReCaptchaV3($reCaptchaV3Validator, $logger);

        // All normal. Continue...
        $name    = $request->get('name');
        $email   = $request->get('email');
        $subject = $request->get('subject');
        $message = $request->get('message');

        $isValid   = true;
        $validator = new DataValidator();
        $isValid   &= $validName = $validator->isValidString($name, 2, 255, false);
        $isValid   &= $validEmail = $validator->isValidString($email, 2, 255);
        $isValid   &= $validSubject = $validator->isValidString($subject, 2, 255, false);

        $errors = [];
        if (!$validName) {
            $errors[] = 'The name you entered is invalid.';
        }
        if (!$validEmail) {
            $errors[] = 'The email you entered is invalid.';
        }
        if (!$validSubject) {
            $errors[] = 'The subject you entered is invalid.';
        }

        if (!$isValid) {
            throw new ApiNormalOperationException($errors);
        }

        try {
            $mailerMessage = new MailerMessage("The contact `$email` sent us a message.",
                $this->getParameter('sys_admin_email'));
            $mailerMessage->setHtmlTemplate('/email/contact_us/contact_us_by_email.html.twig');
            $mailerMessage->setContext([
                'contactEmail' => $email,
                'subject'      => $subject,
                'message'      => $message,
            ]);
            // Queues the email delivery. Asynchronously.
            $bus->dispatch(new EmailNotification($mailerMessage));

        } catch (Exception $e) {
            $logger->error('The Contact us message was not able to send.', [
                'trace' => $e->getTraceAsString()
            ]);
            throw new ApiErrorException(['The message was not able to be sent.'], 0, $e);
        }

        return $this->_buildJsonResponse(new ApiEmptyResponse());
    }
}