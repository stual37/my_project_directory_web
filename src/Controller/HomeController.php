<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $texte = 'Bonjour ' .$request->query->get('name');

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'texte' => $texte
        ]);
    }

    #[Route('demo', name: 'demo')]
    public function demo(ValidatorInterface $validator): void
    {
        $recipe = new Recipe();
        $errors = $validator->validate($recipe);
        dd((string)$errors);
    }

   
}
