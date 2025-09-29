<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',

        ]);
    }
    #[Route('/showService/{name}', name: 'show_service')]
    public function showService($name): Response
    {
        return (new Response('service '.$name));
    }
    #[Route('/goto/{name}', name: 'got_to_index')]
    public function gotoI($name): Response
    {
        return ($this->redirectToRoute('show_service', ['name' => $name]));
    }
}

