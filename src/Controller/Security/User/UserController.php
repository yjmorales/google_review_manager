<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Security\User;

use App\Core\Controller\BaseController;
use App\Entity\User;
use App\Form\UserFormType;
use App\Security\Role\RoleTypes;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Responsible to manage the users.
 *
 * @Route("/admin/user")
 */
class UserController extends BaseController
{
    /**
     * @Route("/", name="users_list")
     */
    public function list(ManagerRegistry $doctrine): Response
    {
        $users = $this->_em($doctrine)->getRepository(User::class)->findAll();

        return $this->render('/security/user/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/create", name="user_create")
     */
    public function create(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user, ['isEdit' => false]);

        if (!$this->_save($request, $doctrine, $form, $user, $hasher)) {
            return $this->render('/security/user/user_create.html.twig', [
                'user'       => $user,
                'form'       => $form->createView(),
                'breadcrumb' => [
                    'User list'   => $this->generateUrl('users_list'),
                    'Create user' => $this->generateUrl('user_create'),
                ],
            ]);
        }

        return $this->redirectToRoute('users_list');
    }

    /**
     * Edits a new user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     */
    public function edit(
        Request $request,
        ManagerRegistry $doctrine,
        User $user,
        UserPasswordHasherInterface $hasher
    ): Response {
        $form     = $this->createForm(UserFormType::class, $user, ['isEdit' => true]);
        $response = $this->redirectToRoute('users_list');

        if (!$form->isSubmitted()) {
            $roles = $user->getRoles();
            if (in_array(RoleTypes::ROLE_ADMIN()->getId(), $roles)) {
                $form->get('roles')->setData(RoleTypes::ROLE_ADMIN()->getId());
            } elseif (in_array(RoleTypes::ROLE_USER()->getId(), $roles)) {
                $form->get('roles')->setData(RoleTypes::ROLE_USER()->getId());
            }
        }

        if (!$this->_save($request, $doctrine, $form, $user, $hasher)) {
            $response = $this->render('/security/user/user_edit.html.twig', [
                'form'       => $form->createView(),
                'user'       => $user,
                'breadcrumb' => [
                    'User list' => $this->generateUrl('users_list'),
                    'Edit user' => $this->generateUrl('user_edit', ['id' => $user->getId()]),
                ],
            ]);
        }

        return $response;
    }

    /**
     * Removes a user entity.
     *
     * @Route("/{id}/remove", name="user_remove")
     */
    public function remove(ManagerRegistry $doctrine, User $user): Response
    {
        $response = $this->redirectToRoute('users_list');

        if ($this->getUser()->getUserIdentifier() === $user->getEmail()) {
            $this->_notifyError('The authenticated user cannot be removed.');

            return $response;
        }

        $this->_em($doctrine)->remove($user);
        $this->_em($doctrine)->flush();

        return $response;
    }

    /**
     * Handles common actions used to save a user.
     *
     * @param Request                     $request
     * @param ManagerRegistry             $doctrine
     * @param FormInterface               $form
     * @param User                        $user
     * @param UserPasswordHasherInterface $hasher
     *
     * @return bool
     * @throws Exception
     */
    private function _save(
        Request $request,
        ManagerRegistry $doctrine,
        FormInterface $form,
        User $user,
        UserPasswordHasherInterface $hasher
    ): bool {
        $isEdit = (bool)$user->getId();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->_em($doctrine);

            // Sets roles
            $role = $form->get('roles')->getData();
            if (RoleTypes::ROLE_ADMIN()->getId() === $role) {
                $user->setRoles([RoleTypes::ROLE_ADMIN()->getId(), RoleTypes::ROLE_USER()->getId()]);
            } elseif (RoleTypes::ROLE_USER()->getId() === $role) {
                $user->setRoles([RoleTypes::ROLE_USER()->getId()]);
            } else {
                throw new Exception('Invalid role assignment');
            }

            if (!$isEdit) {
                // Setting password only for creation scenario.
                if (!$password = $form->get('password')->getData()) {
                    throw new Exception('Password is required');
                }
                $user->setPassword($hasher->hashPassword($user, $password));
            }

            // Saving in DB
            $em->persist($user);
            $em->flush();
            if ($isEdit) {
                $this->_notifySuccess("The user \"{$user->getEmail()}\" has been updated successfully.");
            } else {
                $this->_notifySuccess("The user \"{$user->getEmail()}\" has been created successfully.");
            }

            return true;
        }

        return false;
    }
}