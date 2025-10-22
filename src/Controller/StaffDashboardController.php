<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/staff')]
final class StaffDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'staff_dashboard')]
    public function index(
        OrderRepository $orderRepository,
        ReservationRepository $reservationRepository
    ): Response {
        // Retrieve all orders and reservations (for now, no filtering by staff)
        $orders = $orderRepository->findAll();
        $reservations = $reservationRepository->findAll();

        // Basic counts for summary
        $pendingOrders = array_filter($orders, fn($o) => $o->getStatus() === 'Pending');
        $inProgressOrders = array_filter($orders, fn($o) => $o->getStatus() === 'Preparing');
        $readyOrders = array_filter($orders, fn($o) => $o->getStatus() === 'Ready');

        return $this->render('Staff/staff_dashboard.html.twig', [
            'orders' => $orders,
            'reservations' => $reservations,
            'pendingOrders' => count($pendingOrders),
            'inProgressOrders' => count($inProgressOrders),
            'readyOrders' => count($readyOrders),
        ]);
    }
}
