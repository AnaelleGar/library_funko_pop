<?php

namespace App\Controller\Back;

use App\Form\Type\LoginType;
use App\Form\Type\SignUpType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class BackController.
 */
class BackController extends AbstractController
{
    protected const TEMPLATE_DIR = 'back';

    /**
     * @return Response
     */
    #[Route(name: 'index')]
    public function index(): Response
    {
        return $this->render(self::TEMPLATE_DIR.DIRECTORY_SEPARATOR.'index.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if (null !== $this->getUser()) {
            return new RedirectResponse($this->generateUrl('back_index'));
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(self::TEMPLATE_DIR.DIRECTORY_SEPARATOR.'login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'loginForm' => $this->createForm(LoginType::class)->createView(),
        ]);
    }

    /**
     * @param Security $security
     *
     * @return Response
     */
    #[Route(path: ['fr' => '/deconnexion', 'en' => '/logout'], name: 'logout')]
    public function logout(Security $security): Response
    {
        if ($this->getUser()) {
            return $security->logout();
        }

        return new RedirectResponse($this->generateUrl('back_index'));
    }

    /**
     * @return Response
     */
    #[Route(path: ['fr' => '/inscription', 'en' => '/sign-up'], name: 'sign_up')]
    public function signUp(): Response
    {
        return $this->render(self::TEMPLATE_DIR.DIRECTORY_SEPARATOR.'signup.html.twig', [
            'signupForm' => $this->createForm(SignUpType::class)->createView(),
        ]);
    }
}
