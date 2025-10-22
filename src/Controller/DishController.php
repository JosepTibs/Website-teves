<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/staff')]
class DishController extends AbstractController
{
    #[Route('/staff/menu', name: 'staff_menu')]
public function menu(Request $request, EntityManagerInterface $em): Response
{
    $dish = new Dish();
    $form = $this->createForm(DishType::class, $dish);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($dish);
        $em->flush();

        $this->addFlash('success', 'Dish added successfully!');
        return $this->redirectToRoute('staff_menu');
    }

    $dishes = $em->getRepository(Dish::class)->findAll();

    return $this->render('Staff/staff_menu.html.twig', [
        'form' => $form->createView(),   // ✅ this line fixes the Twig error
        'dishes' => $dishes,
    ]);
}


    #[Route('/edit/{id}', name: 'staff_dish_edit', methods: ['GET', 'POST'])]
    public function edit(Dish $dish, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Dish updated successfully!');
            return $this->redirectToRoute('staff_menu');
        }

        return $this->render('dish/dish_form.html.twig', [
            'form' => $form->createView(),
            'dish' => $dish,
        ]);
    }

    #[Route('/delete/{id}', name: 'staff_dish_delete', methods: ['POST'])]
    public function delete(Dish $dish, EntityManagerInterface $em): Response
    {
        $em->remove($dish);
        $em->flush();

        $this->addFlash('danger', 'Dish deleted successfully!');
        return $this->redirectToRoute('staff_menu');
    }
}
