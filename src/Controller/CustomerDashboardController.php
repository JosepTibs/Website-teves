<?php

namespace App\Controller;

use App\Repository\DishRepository;
use App\Repository\OrderRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class CustomerDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'customer_dashboard')]
    public function index(
        DishRepository $dishRepository,
        OrderRepository $orderRepository,
        ReservationRepository $reservationRepository
    ): Response {
        // Get chef's picks (main course dishes)
        $chefs_picks = $dishRepository->findBy(['category' => 'Main Course'], ['id' => 'DESC'], 3);

        // Get all orders (no user filter yet)
        $orders = $orderRepository->findAll();

        // Get all reservations (no user filter yet)
        $reservations = $reservationRepository->findAll();

        return $this->render('Customer/customer_dashboard.html.twig', [
            'chefs_picks' => $chefs_picks,
            'orders' => $orders,
            'reservations' => $reservations,
        ]);
    }
}
