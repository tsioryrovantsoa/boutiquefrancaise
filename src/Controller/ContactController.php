<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mail = new Mail();
            // $form->get('new_password')->getData();
            // dd($form->getData());

            $content = "<h4>Bonjour Tsiory, </h4><br/> Message du Boutique Française <br/> Nom : " . $form->get('nom')->getData() . " <br/> E-mail : " . $form->get('email')->getData() . " <br/> Message : " . $form->get('content')->getData() . "<br/> A Bientot !";
            $mail->send('tsioryrovantsoa@gmail.com', 'Tsiory Rovantsoa', 'Message venant de La Boutique Francaise', $content);

            $this->addFlash('notice', 'Merci de nous avoir contacter');
            unset($form);
            $form = $this->createForm(ContactType::class);
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
