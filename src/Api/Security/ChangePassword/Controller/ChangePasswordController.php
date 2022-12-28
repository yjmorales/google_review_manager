<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Security\ChangePassword\Controller;

use App\Api\Core\Exception\ApiErrorException;
use App\Api\Core\Exception\ApiNormalOperationException;
use App\Core\Controller\BaseController;
use App\Core\Models\ApiEmptyResponse;
use App\Entity\User;
use Common\Communication\HtmlMailer\Mailer;
use Common\Communication\HtmlMailer\MailerMessage;
use Common\DataManagement\Validator\DataValidator;
use Common\DataStorage\Redis\RedisCacheRegistry;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API Controller to manages a business.
 *
 * @Route("/api/change-password")
 */
class ChangePasswordController extends BaseController
{
    /**
     * Generates a link to change the password for a user.
     *
     * @param Request            $request
     * @param Mailer             $mailer
     * @param RedisCacheRegistry $cache
     * @param RouterInterface    $router
     * @param ManagerRegistry    $doctrine
     *
     * @return JsonResponse
     * @throws ApiErrorException
     * @throws ApiNormalOperationException
     */
    public function sendLinkToUpdatePassword(
        Request $request,
        Mailer $mailer,
        RedisCacheRegistry $cache,
        RouterInterface $router,
        ManagerRegistry $doctrine
    ): JsonResponse {

        $email = $request->get('email');
        $valid = (new DataValidator())->isValidString($email);
        if (!$valid) {
            throw new ApiNormalOperationException(['The email is invalid.']);
        }
        $ttlMinutes   = 15;
        $hash         = md5(uniqid());
        $route        = $router->generate('change_password', ['hash' => $hash]);
        $link         = $_SERVER['HTTP_ORIGIN'] . $route;
        $user         = $this->_repository($doctrine, User::class)->findOneByEmail($email);
        $data         = new stdClass();
        $data->userId = $user->getId();
        $data->link   = $link;
        $cache->set($hash, $data, $ttlMinutes * 60); // Save hash for 15 minutes.
        $title = $this->getParameter('system_title');
        $msg   = new MailerMessage("$title - Recover password.", $email);
        $msg->setContext([
            'emailAddress' => $email,
            'link'         => $data->link,
            'ttlMinutes'   => $ttlMinutes,
        ]);
        $msg->setHtmlTemplate('/email/security/change_password/change_password_link.html.twig');
        if (!$mailer->send($msg)) {
            throw new ApiErrorException(['The link to change the password was not able to be sent.']);
        }

        return $this->_buildJsonResponse(new ApiEmptyResponse());
    }
}