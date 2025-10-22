<?php

namespace App\Controller;

use App\Entity\Dish;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route('/menu', name: 'customer_menu')]
    public function menu(EntityManagerInterface $entityManager): Response
    {
        // Fetch all dishes from the same database table staff uses
        $dishes = $entityManager->getRepository(Dish::class)->findAll();

        return $this->render('customer/customer_menu.html.twig', [
            'dishes' => $dishes,
        ]);
    }
}
