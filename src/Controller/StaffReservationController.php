<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/staff/reservations')]
final class StaffReservationController extends AbstractController
{
    #[Route('/', name: 'staff_reservations_index')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findBy([], ['date' => 'ASC']);

        return $this->render('Staff/staff_reservation.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/{id}/status', name: 'staff_reservations_update_status', methods: ['POST'])]
    public function updateStatus(Request $request, Reservation $reservation, EntityManagerInterface $em): Response
    {
        $status = $request->request->get('status');

        if (in_array($status, ['Confirmed', 'Declined'])) {
            $reservation->setStatus($status);
            $em->flush();

            $this->addFlash('success', "Reservation #{$reservation->getId()} marked as {$status}.");
        }

        return $this->redirectToRoute('staff_reservations_index');
    }
}
