<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/staff/orders')]
final class StaffOrdersController extends AbstractController
{
    #[Route('/', name: 'staff_orders_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        OrderRepository $orderRepository,
        EntityManagerInterface $em
    ): Response {
        // Fetch all orders, newest first
        $orders = $orderRepository->findBy([], ['createdAt' => 'DESC']);

        // If staff updates order status
        if ($request->isMethod('POST')) {
            $orderId = $request->request->get('order_id');
            $newStatus = $request->request->get('status');

            $order = $orderRepository->find($orderId);
            if ($order) {
                $order->setStatus($newStatus);
                $em->flush();
                $this->addFlash('success', "Order #{$orderId} updated to {$newStatus}");
            }

            return $this->redirectToRoute('staff_orders_index');
        }

        return $this->render('Staff/staff_order.html.twig', [
            'orders' => $orders,
        ]);
    }
}
