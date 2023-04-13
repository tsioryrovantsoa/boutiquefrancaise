<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        // $mail = new Mail();
        // $mail->send('tsioryrovantsoa@gmail.com', 'Tsiory', 'Bienvenue parmi nous', '<h1>Bonjour Tsiory !</h1><br/>Bienvenue parmi nous, Effectuer votre premiere achat !');

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
