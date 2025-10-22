<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\DishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
final class OrderController extends AbstractController
{
    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, DishRepository $dishRepository): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        // Fetch dishes & group by category for display
        $dishes = $dishRepository->findAll();
        $dishesByCategory = [];
        foreach ($dishes as $dish) {
            $category = $dish->getCategory() ?? 'Uncategorized';
            $dishesByCategory[$category][] = $dish;
        }

        // Handle submission manually (since dishes[] isn’t part of Symfony form)
        if ($request->isMethod('POST')) {
            $dishInputs = $request->request->all('dishes');

            $total = 0;

            foreach ($dishInputs as $dishId => $quantity) {
                $quantity = (int)$quantity;
                if ($quantity > 0) {
                    $dish = $dishRepository->find($dishId);
                    if ($dish) {
                        $order->addDish($dish);
                        $total += $dish->getPrice() * $quantity;
                    }
                }
            }

            $order->setTotalPrice($total);
            $order->setCreatedAt(new \DateTimeImmutable());
            $order->setStatus('Pending');

            // Persist even if specialRequest is empty
            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'Order created successfully!');
            return $this->redirectToRoute('customer_dashboard');
        }

        return $this->render('Customer/order/new.html.twig', [
            'form' => $form->createView(),
            'dishesByCategory' => $dishesByCategory,
        ]);
    }

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(?Order $order): Response
    {
        if (!$order) {
            throw $this->createNotFoundException('Order not found.');
        }

        return $this->render('Customer/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $total = 0;
            foreach ($order->getDishes() as $dish) {
                $total += $dish->getPrice();
            }
            $order->setTotalPrice($total);

            $entityManager->flush();
            $this->addFlash('success', 'Order updated successfully.');
            return $this->redirectToRoute('app_order_index');
        }

        return $this->render('Customer/order/edit.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }

    #[Route('/{id}', name: 'app_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_index');
    }
}

