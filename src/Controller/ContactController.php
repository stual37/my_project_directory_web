<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use App\DTO\ContactRegistration;
use Symfony\Component\Mime\Email;
use App\Form\ContactRegistrationType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            try {
                //Construction du template du mail
                $mail = (new TemplatedEmail())
                ->locale('fr')
                ->to($data->service)
                ->from($data->email)
                ->subject($data->subject)
                ->htmlTemplate('emails/contact.html.twig')
                ->context(['data' => $data]);
                // Envoie du mail
                $mailer->send($mail);
                $this->addFlash('success', 'Votre email a bien été envoyé');
                return $this->redirectToRoute('contact');
            }
            catch (\Exception $e) {
                $this->addFlash('error', 'Un problème a été rencontré lors de l\'envoie de votre message : ' . $e->getMessage());
                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route("/contact_registration", name: 'contact_registration')]
    public function contactRegistration(Request $request, MailerInterface $mailer): Response
    {
        $mailTo = $_ENV['MAIL_TO'];
        $data = new ContactRegistration();

        $form = $this->createForm(ContactRegistrationType::class, $data);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            try {
                //Construction du template du mail
                $mail = (new TemplatedEmail())
                ->locale('fr')
                ->to($mailTo)
                ->from($data->email)
                ->subject($data->subject)
                ->htmlTemplate('emails/registration_problem.html.twig')
                ->context(['data' => $data]);
                // Envoie du mail
                $mailer->send($mail);
                $this->addFlash('success', 'Votre email a bien été envoyé');
                return $this->redirectToRoute('home');
            }
            catch (\Exception $e) {
                $this->addFlash('error', 'Un problème a été rencontré lors de l\'envoie de votre message');
            }
        }

        return $this->render('contact/contact_registration.html.twig', [
            'form' => $form,
        ]);
    }

}
