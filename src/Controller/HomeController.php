<?php

namespace App\Controller;

use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {

        $texte = 'Bonjour ' .$request->query->get('name');
        /*
        $user = new User();
        $user->setEmail('john@doe.fr')
            ->setUsername('johnDoe')
            ->setPassword($hasher->hashPassword($user, '4'9&M:,,nahET4S'))
            ->setRoles([]);
        $em->persist($user);
        $em->flush();
        
        $user = new User();
        $user->setEmail('stual37@gmail.com')
            ->setUsername('admmin')
            ->setPassword($hasher->hashPassword($user, 'ARX-jdk-928-324!'))
            ->setRoles(['ROLE_ADMIN']);
        $em->persist($user);
        $em->flush();
        */
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'texte' => $texte
        ]);
    }

}
