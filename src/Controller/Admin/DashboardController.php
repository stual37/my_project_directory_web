<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/admin", name: 'admin.')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function dashboard(Request $request): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            
        ]);
    }
    
    #[Route('/users', name: 'users.list')]
    public function users(Request $request, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/users/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/users/show/{id}', name: 'users.user.show', requirements:['id' => Requirement::DIGITS], methods:['GET'])]
    public function showUser(Request $request, int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        return $this->render('admin/users/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/users/edit/{id}', name: 'users.user.edit', requirements:['id' => Requirement::DIGITS], methods:['GET', 'POST'])]
    public function editUser(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $password = $form->getData(['password']);
            dd($password);
            if($form->getData(['isVerified'])==0) {
                $user->setVerified(0);
            }
            else {
                $user->setVerified(1);
            }
            /** @var  EntityManagerInterface $em */
            $em->flush();
            $this->addFlash('success', 'La recette a bien été mis à jour');
            return $this->redirectToRoute('users.list');
        }
        return $this->render('admin/users/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/users/add', name: 'users.user.add', methods:['GET', 'POST'])]
    public function addUser(Request $request, UserRepository $userRepository): Response
    {
        $user = new user();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($form->getData(['isverified']==0)) {
                $user->setVerified(0);
            }
            else {
                $user->setVerified(1);
            }
            /** @var  EntityManagerInterface $em */
            $em->flush();
            $this->addFlash('success', 'La recette a bien été mis à jour');
            return $this->redirectToRoute('users.list');
        }
        return $this->render('admin/users/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/users/delete/{id}', name: 'users.user.delete', requirements:['id' => Requirement::DIGITS], methods:['DELETE'])]
    public function delete(Request $request, User $user , EntityManagerInterface $em)
    {
        // Permet de supprimer la recette
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'La recette a été supprimé avec succès');

        return $this->redirectToRoute('users.list');
    }
}