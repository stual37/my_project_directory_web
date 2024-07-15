<?php

namespace App\Controller;

use App\Domain\AntiSpam\CaptchaInterface;
use App\Entity\User;
use App\Form\RegistrationFormType;
//use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
//use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    /**
     * Constructeur par défaut
     *
     * @param EmailVerifier $emailVerifier
     */
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    /**
     * Fonction appelée lorsque l'utilisateur veut s'enregistrer
     *
     * @param Request $request requête http envoyé par Symfony
     * @param UserPasswordHasherInterface $userPasswordHasher interface servant à hacher (encoder) le mot de passe
     * @param Security $security oclasse servant à gérer la sécurité
     * @param EntityManagerInterface $entityManager le gestionnaire d'entité pur les données
     * @return Response la réponse retournée
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,
                            Security $security, EntityManagerInterface $entityManager, CaptchaInterface $captcha): Response
    {
        //$key = $captcha->generateKey();
        //$solution = $captcha->getSolution($key);
        //dd($captcha->verify($key, implode('-', $solution)));

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                    $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('registration@demo.fr', 'registration'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $security->login($user, 'form_login', 'main');
            /*
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
            */
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'captcha_challenge' => $captcha->generateKey()
        ]);
    }

    /**
     * Méthode servant à vérifier l'authenticité de la personne qui créée un compte en lui envoyant un mail
     *
     * @param Request $request requête http envoyé par Symfony
     * @param TranslatorInterface $translator
     * @return Response
     */
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }
}
