<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BaseController.
 */
class BaseController extends AbstractController
{
    /**
     * @return RedirectResponse
     */
    #[Route(name: 'index')]
    public function index(): RedirectResponse
    {
        return new RedirectResponse($this->generateUrl('back_index'));
    }
}
