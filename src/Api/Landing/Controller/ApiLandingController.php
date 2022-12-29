<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Landing\Controller;

use App\Api\Core\Controller\TApiController;
use App\Api\Core\Exception\ApiErrorException;
use App\Api\Core\Exception\ApiNormalOperationException;
use App\Core\Controller\BaseController;
use App\Core\Models\ApiEmptyResponse;
use Common\Communication\HtmlMailer\MailerMessage;
use Common\DataManagement\Validator\DataValidator;
use Common\Security\AntiSpam\ReCaptcha\v3\ReCaptchaV3Validator;
use Exception;
use Common\Communication\HtmlMailer\Mailer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Controller to manages the landing page.
 */
class ApiLandingController extends BaseController
{
    use TApiController;

    /**
     * Sends a message everytime the user send a message by using the contact us form.
     *
     * @param Request         $request
     * @param Mailer          $mailer
     * @param LoggerInterface $logger
     *
     * @return JsonResponse
     * @throws ApiErrorException
     * @throws ApiNormalOperationException
     */
    public function contactUsSendEmail(
        Request $request,
        Mailer $mailer,
        LoggerInterface $logger,
        ReCaptchaV3Validator $reCaptchaV3Validator
    ): JsonResponse {

        /*
         *  Verifies that's not a robot.
         */
        $this->_validateReCaptchaV3($reCaptchaV3Validator);

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
        $isValid   &= $validMsg = $validator->isValidString($message, 2, 500);

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
        if (!$validMsg) {
            $errors[] = 'The message you entered is invalid.';
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
            $sent = $mailer->send($mailerMessage);
            if (!$sent) {
                throw new Exception('not sent');
            }
        } catch (Exception $e) {
            $logger->error('The Contact us message was not able to send.');
            throw new ApiErrorException(['The message was not able to be sent.'], 0, $e);
        }

        return $this->_buildJsonResponse(new ApiEmptyResponse());
    }
}