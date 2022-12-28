<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Security;

use App\Core\Controller\BaseController;
use App\Entity\User;
use App\Form\ChangePasswordType;
use Common\Communication\HtmlMailer\Mailer;
use Common\Communication\HtmlMailer\MailerMessage;
use Common\DataManagement\Validator\DataValidator;
use Common\DataStorage\Redis\RedisCacheRegistry;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller class responsible to manage the security.
 */
class SecurityController extends BaseController
{
    /**
     * Creates the login page.
     *
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $utils): Response
    {
        return $this->render('/security/login/login.html.twig', [
            'title'        => $this->getParameter('system_title'),
            'lastUsername' => $utils->getLastUsername() ?? '',
            'error'        => $utils->getLastAuthenticationError() ?? '',
        ]);
    }

    /**
     * Route to log out the user.
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Responsible to render a page to change a password. This renders a page with a field to enter the email.
     *
     * @Route("/change-password/get-email", name="change_password_get_email")
     */
    public function changePasswordGetEmail(): Response
    {
        $title = $this->getParameter('system_title');

        return $this->render('/security/change_password/change_password_get_email.html.twig', [
            'title' => $title,
        ]);
    }

    /**
     * Handles the password changing logic.
     *
     * @Route("/change-password/{hash}", name="change_password")
     */
    public function changePassword(
        Request $request,
        ManagerRegistry $doctrine,
        RedisCacheRegistry $cache,
        UserPasswordHasherInterface $passwordHasher,
        Mailer $mailer
    ): Response {
        $hash = $request->get('hash');
        if (!$data = $cache->get($hash)) {
            throw new NotFoundHttpException('Invalid hash');
        }

        $title = $this->getParameter('system_title');
        $form  = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $isValid  = (new DataValidator())->isValidString($password);
            if (!$isValid) {
                $this->_notifyError('The password is invalid');

                return $this->render('/security/change_password/change_password.html.twig', [
                    'title' => $this->getParameter('system_title'),
                    'form'  => $form->createView(),
                ]);
            }
            $userId = $data->userId;
            /** @var User $user */
            $user = $this->_repository($doctrine, User::class)->find($userId);
            $user->setPassword($passwordHasher->hashPassword($user, $password));
            $this->_em($doctrine)->persist($user);
            $this->_em($doctrine)->flush();

            $msg = new MailerMessage("$title - Password changed.", $user->getEmail());
            $msg->setHtmlTemplate('/email/security/change_password/change_password_notification.html.twig');
            $mailer->send($msg);

            return $this->redirectToRoute('change_password_successfully', [
                'hash' => $hash,
            ]);
        }

        return $this->render('/security/change_password/change_password.html.twig', [
            'title' => $title,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * Renders a page to display that the password has been updated successfully.
     *
     * @Route("/change-password-successfully/{hash}", name="change_password_successfully")
     */
    public function changePasswordSuccessfully(Request $request, RedisCacheRegistry $cache): Response
    {
        if (!$hash = $request->get('hash')) {
            throw new NotFoundHttpException();
        }
        if (!$cache->get($hash)) {
            throw new NotFoundHttpException();
        }
        $cache->purge($hash);

        return $this->render('/security/change_password/changed_password_successfully.html.twig', [
            'title' => $this->getParameter('system_title'),
        ]);
    }
}
