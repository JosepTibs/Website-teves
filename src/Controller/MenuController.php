<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class MenuController extends AbstractController
{
    #[Route('/menu/manage', name: 'staff.menu')]
    public function staffMenu(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $dish = new Dish();
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // handle uploaded image if any
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Image upload failed.');
                }

                $dish->setImage($newFilename);
            }

            $em->persist($dish);
            $em->flush();

            $this->addFlash('success', 'Dish added successfully!');
            return $this->redirectToRoute('staff.menu');
        }

        $dishes = $em->getRepository(Dish::class)->findAll();

        return $this->render('Staff/staff_menu.html.twig', [
            'form' => $form->createView(),
            'dishes' => $dishes,
        ]);
    }

    #[Route('/menu/dish/{id}/edit', name: 'dish_edit')]
    public function editDish(int $id, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $dish = $em->getRepository(Dish::class)->find($id);
        if (!$dish) {
            throw $this->createNotFoundException('Dish not found');
        }

        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Image upload failed.');
                }

                $dish->setImage($newFilename);
            }

            $em->flush();
            $this->addFlash('success', 'Dish updated successfully!');
            return $this->redirectToRoute('staff.menu');
        }

        return $this->render('dish/edit.html.twig', [
            'form' => $form->createView(),
            'dish' => $dish,
        ]);
    }

    #[Route('/menu/dish/{id}/delete', name: 'dish_delete')]
    public function deleteDish(int $id, EntityManagerInterface $em): Response
    {
        $dish = $em->getRepository(Dish::class)->find($id);
        if (!$dish) {
            throw $this->createNotFoundException('Dish not found');
        }

        $em->remove($dish);
        $em->flush();

        $this->addFlash('success', 'Dish deleted successfully!');
        return $this->redirectToRoute('staff.menu');
    }
}
