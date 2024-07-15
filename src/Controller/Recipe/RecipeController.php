<?php

namespace App\Controller\Recipe;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use function Symfony\Component\Clock\now;

#[Route("/recipe/recettes", name: 'recipes.recipe.')]
#[IsGranted('ROLE_USER')]
class RecipeController extends AbstractController
{
    /**
     * function index : Permet d'afficher la liste des recettes de cuisine disponibles
     *
     * @param Request $request
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function recettes(Request $request, RecipeRepository $recipeRepository): Response
    {
        // Permet de récupérer ltoutes les recettes
        $recipes = $recipeRepository->findAll();

        return $this->render('recipes/recipe/index.html.twig', [
            'controller_name' => 'Les recettes',
            'recipes' => $recipes
        ]);
    }



    // premier paramètres :  obligatoire => format de la route
    // name :  obligatoire => nom de la route
    // requirements : optionnel => paramètres permettant de définir les formats attendues pour le paramètrs de l'url
    /**
     * function show : Permet d'afficher une recette de cuisine en détail
     *
     * @param Request $request
     * @param int $id
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    #[Route('/{id}', name: 'show', requirements: ['id' => Requirement::DIGITS])]
    public function show(Request $request, int $id, RecipeRepository $recipeRepository) : Response
    {
        /** @var Recipe $recipe */
        // Permet de récupérer une rectte en fonction de son id
        $recipe = $recipeRepository->find($id);

        return $this->render('recipes/recipe/show.html.twig', [
            'slug' => $recipe->getSlug(),
            'recipe' => $recipe,
        ]);
    }

    /**
     * function edit : Permet de modifier une recette de cuisine
     *
     * @param Request $request
     * @param Recipe $recipe
     * @param EntityManagerInterface $em
     * @return Response
    */
    #[Route('/edit/{id}', name: 'edit', requirements:['id' => Requirement::DIGITS], methods:['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em)
    {
        //Permet de créer le formulaire avec les données à afficher passées en paramêtres
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            /** @var  EntityManagerInterface $em */
            $em->flush();
            $this->addFlash('success', 'La recette a bien été mis à jour');
            return $this->redirectToRoute('recipes.recipe.index');
        }

        return $this->render('recipes/recipe/edit.html.twig', [
            'slug' => $recipe->getSlug(),
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    /**
     * function add : Permet d'ajouter une recette de cuisine
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
    */
    #[Route('/add' , name: 'add', methods:['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em)
    {
        /** @var Recipe $recipe */
        $recipe = new Recipe();
        // Créé le formulaire à envoyeer
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // Permet d'enregistrer la recette
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été ajouté');
            return $this->redirectToRoute('recipes.recipe.index');
        }

        return $this->render('recipes/recipe/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * function delete : Permet de supprimer une recette de cuisine
     *
     * @param Request $request
     * @param Recipe $recipe
     * @param EntityManagerInterface $em
     * @return Response
    */
    #[Route('/delete/{id}', name: 'delete', requirements:['id' => Requirement::DIGITS], methods:['DELETE'])]
    public function delete(Request $request, Recipe $recipe , EntityManagerInterface $em)
    {
        // Permet de supprimer la recette
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a été supprimé avec succès');

        return $this->redirectToRoute('recipes.recipe.index');
    }
}
