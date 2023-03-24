<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/home/doing', name: 'fit_doing')]
    public function viewDoing(): Response
    {
        return $this->render('home/doing.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/home/dijon', name: 'fit_dijon')]
    public function viewDijon(): Response
    {
        return $this->render('home/dijon.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/home/proto', name: 'fit_proto')]
    public function viewProto(Connection $connection): Response
    {
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = 10
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql)->fetch();

        $sqlSportRepartition = '
        SELECT f.id_fit_arena, f.libelle AS fit_arena_libelle, s.id_sport, s.libelle AS sport_libelle,
               COUNT(r.id_reservation) AS reservations,
               ROUND(COUNT(r.id_reservation) * 100.0 / total_reservations.total, 2) AS pourcentage
        FROM fa_reservation r
        INNER JOIN fa_sport s ON s.id_sport = r.id_sport
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        INNER JOIN (
          SELECT f.id_fit_arena, COUNT(r.id_reservation) AS total
          FROM fa_reservation r
          INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
          GROUP BY f.id_fit_arena
        ) AS total_reservations ON total_reservations.id_fit_arena = f.id_fit_arena
        WHERE f.id_fit_arena = 10
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition)->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = 10
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations)->fetchOne();



        return $this->render('home/proto.html.twig', [
            'chiffre_affaire' => $result['chiffre_affaire'],
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
        ]);
    }


    #[Route('/home/demo', name: 'fit_demo')]
    public function viewDemo(): Response
    {
        return $this->render('home/demo.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
