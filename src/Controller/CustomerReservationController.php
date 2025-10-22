<?php
namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerReservationController extends AbstractController
{
    #[Route('/reservation', name: 'customer.reservation', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setCreationDate(new \DateTimeImmutable());
            $reservation->setStatus('pending');

            $em->persist($reservation);
            $em->flush();

            $this->addFlash('success', 'Your reservation has been submitted successfully!');

            // Later: redirect to pre-order or confirmation
            return $this->redirectToRoute('customer.reservation');
        }

        return $this->render('Customer/customer_reservation.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
