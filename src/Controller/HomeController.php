<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private function getFitArenaNameById(Connection $connection, int $fitArenaId): ?string
    {
        $sql = 'SELECT libelle FROM fa_fit_arena WHERE id_fit_arena = :id';
        $result = $connection->executeQuery($sql, ['id' => $fitArenaId])->fetch();

        return $result ? $result['libelle'] : null;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/home/fit_arena', name: 'fit_arena')]
    public function fit(): Response
    {
        return $this->render('home/FitArena.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /* fit arena Lyon */
    #[Route('/home/doing/week', name: 'fit_doing_week')]
    public function viewDoingWeek(Connection $connection): Response
    {
        $fitArenaId = 1;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);

        $sql = "SELECT WEEK(p.date_creation) as semaine, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY semaine";
        $prediresultS = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $prediresult = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

        $sql = "SELECT WEEK(p.date_creation) as semaine, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = 'solde' AND r.id_fit_arena = :fit_arena_id
                GROUP BY semaine";
        $resultS = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

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
        WHERE f.id_fit_arena = :fit_arena_id
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = :fit_arena_id
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations, ['fit_arena_id' => $fitArenaId])->fetchOne();
        return $this->render('home/doing.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'chiffre_affaire' => $result['chiffre_affaire'],
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
            'data' => $resultS,
            'type' => 'week',
            'predi_chiffre_affaire' => $prediresult['predi_chiffre_affaire'],
            'predi_data' => $prediresultS,
        ]);
    }

    #[Route('/home/doing/month', name: 'fit_doing_month')]
    public function viewDoingMonth(Connection $connection): Response
    {
        $fitArenaId = 1;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);

        $sql = "SELECT MONTH(p.date_creation) as mois, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY mois";
        $prediresultM = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $prediresult = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

        $sql = "SELECT MONTH(p.date_creation) as mois, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = 'solde' AND r.id_fit_arena = :fit_arena_id
                GROUP BY mois";
        $resultM = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

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
        WHERE f.id_fit_arena = :fit_arena_id
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = :fit_arena_id
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations, ['fit_arena_id' => $fitArenaId])->fetchOne();
        return $this->render('home/doing.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'chiffre_affaire' => $result['chiffre_affaire'],
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
            'data' => $resultM,
            'type' => 'month',
            'predi_chiffre_affaire' => $prediresult['predi_chiffre_affaire'],
            'predi_data' => $prediresultM,
        ]);
    }

    #[Route('/home/doing/year', name: 'fit_doing_year')]
    public function viewDoingYear(Connection $connection): Response
    {
        $fitArenaId = 1;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);

        $sql = "SELECT YEAR(p.date_creation) as annee, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY annee";
        $prediresultA = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $prediresult = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

        $sql = "SELECT YEAR(p.date_creation) as annee, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = 'solde' AND r.id_fit_arena = :fit_arena_id
                GROUP BY annee";
        $resultY = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

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
        WHERE f.id_fit_arena = :fit_arena_id
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = :fit_arena_id
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations, ['fit_arena_id' => $fitArenaId])->fetchOne();
        return $this->render('home/doing.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'chiffre_affaire' => $result['chiffre_affaire'],
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
            'data' => $resultY,
            'type' => 'year',
            'predi_chiffre_affaire' => $prediresult['predi_chiffre_affaire'],
            'predi_data' => $prediresultA,
        ]);
    }



    #[Route('/home/demo', name: 'fit_demo')]
    public function viewDemo(): Response
    {
        return $this->render('home/demo.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /* fit arena Lyon */
    #[Route('/home/lyon/week', name: 'fit_lyon_week')]
    public function viewLyonWeek(Connection $connection): Response
    {
        $fitArenaId = 13;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);


        $sql = "SELECT WEEK(p.date_creation) as semaine, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY semaine";
        $prediresultS = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $prediresult = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

        $sql = "SELECT WEEK(p.date_creation) as semaine, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = 'solde' AND r.id_fit_arena = :fit_arena_id
                GROUP BY semaine";
        $resultS = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

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
        WHERE f.id_fit_arena = :fit_arena_id
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = :fit_arena_id
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations, ['fit_arena_id' => $fitArenaId])->fetchOne();
        return $this->render('home/lyon.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'chiffre_affaire' => $result['chiffre_affaire'],
            'predi_chiffre_affaire' => $prediresult['predi_chiffre_affaire'],
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
            'data' => $resultS,
            'predi_data' => $prediresultS,
            'type' => 'week',
        ]);
    }

    #[Route('/home/lyon/month', name: 'fit_lyon_month')]
    public function viewLyonMonth(Connection $connection): Response
    {
        $fitArenaId = 13;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);

        $sql = "SELECT MONTH(p.date_creation) as mois, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY mois";
        $prediresultM = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $prediresult = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

        $sql = "SELECT MONTH(p.date_creation) as mois, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = 'solde' AND r.id_fit_arena = :fit_arena_id
                GROUP BY mois";
        $resultM = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

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
        WHERE f.id_fit_arena = :fit_arena_id
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = :fit_arena_id
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations, ['fit_arena_id' => $fitArenaId])->fetchOne();
        return $this->render('home/lyon.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'chiffre_affaire' => $result['chiffre_affaire'],
            'predi_chiffre_affaire' => $prediresult['predi_chiffre_affaire'],
            'predi_data' => $prediresultM,
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
            'data' => $resultM,
            'type' => 'month',
        ]);
    }

    #[Route('/home/lyon/year', name: 'fit_lyon_year')]
    public function viewLyonYear(Connection $connection): Response
    {
        $fitArenaId = 13;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);

        $sql = "SELECT YEAR(p.date_creation) as annee, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY annee";
        $prediresultA = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $prediresult = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

        $sql = "SELECT YEAR(p.date_creation) as annee, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = 'solde' AND r.id_fit_arena = :fit_arena_id
                GROUP BY annee";
        $resultY = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

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
        WHERE f.id_fit_arena = :fit_arena_id
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = :fit_arena_id
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations, ['fit_arena_id' => $fitArenaId])->fetchOne();
        return $this->render('home/lyon.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'chiffre_affaire' => $result['chiffre_affaire'],
            'predi_chiffre_affaire' => $prediresult['predi_chiffre_affaire'],
            'predi_data' => $prediresultA,
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
            'data' => $resultY,
            'type' => 'year',
        ]);
    }



    #[Route('/home/demo/month', name: 'fit_demo_month')]
    public function viewDemoMonth(Connection $connection): Response
    {
        $fitArenaId = 10;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);

        $sqlReservationParJour = '
            SELECT f.id_fit_arena, f.libelle AS fit_arena_libelle, DAYNAME(r.date_heure_debut) AS jour_semaine, COUNT(r.id_reservation) AS nombre_reservations
            FROM fa_reservation r
            INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
            WHERE f.id_fit_arena = :fit_arena_id
            GROUP BY f.id_fit_arena, f.libelle, DAYNAME(r.date_heure_debut);
        ';
        $reservationsParSport = $connection->executeQuery($sqlReservationParJour, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();


        $sqlCAparSport = '
            SELECT s.id_sport, s.libelle, SUM(p.montant) / 100 AS chiffre_affaire
	        FROM fa_paiement p
            INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
            INNER JOIN fa_sport s ON s.id_sport = r.id_sport
            WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
            GROUP BY s.id_sport, s.libelle;
        ';
        $CAParSport = $connection->executeQuery($sqlCAparSport, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        return $this->render('home/demo.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'reservations_by_sport' => $reservationsParSport,
            'ca_par_sport' => $CAParSport,
        ]);
    }






/* fit arena Proto */
    #[Route('/home/proto/week', name: 'fit_proto_week')]
    public function viewProtoWeek(Connection $connection): Response
    {
        $fitArenaId = 10;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);

        $sql = "SELECT WEEK(p.date_creation) as semaine, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY semaine";
        $prediresultS = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $prediresult = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

        $sql = "SELECT WEEK(p.date_creation) as semaine, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = 'solde' AND r.id_fit_arena = :fit_arena_id
                GROUP BY semaine";
        $resultS = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

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
        WHERE f.id_fit_arena = :fit_arena_id
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = :fit_arena_id
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations, ['fit_arena_id' => $fitArenaId])->fetchOne();
        return $this->render('home/proto_chiffre_affaire.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'chiffre_affaire' => $result['chiffre_affaire'],
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
            'data' => $resultS,
            'type' => 'week',
            'predi_chiffre_affaire' => $prediresult['predi_chiffre_affaire'],
            'predi_data' => $prediresultS,
        ]);
    }

    #[Route('/home/proto/month', name: 'fit_proto_month')]
    public function viewProtoMonth(Connection $connection): Response
    {
        $fitArenaId = 10;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);

        $sql = "SELECT MONTH(p.date_creation) as mois, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY mois";
        $prediresultM = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $prediresult = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

        $sql = "SELECT MONTH(p.date_creation) as mois, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = 'solde' AND r.id_fit_arena = :fit_arena_id
                GROUP BY mois";
        $resultM = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

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
        WHERE f.id_fit_arena = :fit_arena_id
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = :fit_arena_id
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations, ['fit_arena_id' => $fitArenaId])->fetchOne();
        return $this->render('home/proto_chiffre_affaire.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'chiffre_affaire' => $result['chiffre_affaire'],
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
            'data' => $resultM,
            'type' => 'month',
            'predi_chiffre_affaire' => $prediresult['predi_chiffre_affaire'],
            'predi_data' => $prediresultM,
        ]);
    }

    #[Route('/home/proto/year', name: 'fit_proto_year')]
    public function viewProtoYear(Connection $connection): Response
    {
        $fitArenaId = 10;
        $fitArenaName = $this->getFitArenaNameById($connection, $fitArenaId);

        $sql = "SELECT YEAR(p.date_creation) as annee, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY annee";
        $prediresultA = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS predi_chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $prediresult = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

        $sql = "SELECT YEAR(p.date_creation) as annee, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = 'solde' AND r.id_fit_arena = :fit_arena_id
                GROUP BY annee";
        $resultY = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();
        $sql = 'SELECT r.id_fit_arena, SUM(p.montant) / 100 AS chiffre_affaire
                FROM fa_paiement p
                INNER JOIN fa_reservation r ON r.id_reservation = p.id_reservation
                WHERE p.statut = "solde" AND r.id_fit_arena = :fit_arena_id
                GROUP BY r.id_fit_arena';
        $result = $connection->executeQuery($sql, ['fit_arena_id' => $fitArenaId])->fetch();

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
        WHERE f.id_fit_arena = :fit_arena_id
        GROUP BY f.id_fit_arena, f.libelle, s.id_sport, s.libelle;
    ';
        $sportRepartitions = $connection->executeQuery($sqlSportRepartition, ['fit_arena_id' => $fitArenaId])->fetchAllAssociative();

        $sqlTotalReservations = '
        SELECT COUNT(r.id_reservation) AS total_reservations
        FROM fa_reservation r
        INNER JOIN fa_fit_arena f ON f.id_fit_arena = r.id_fit_arena
        WHERE f.id_fit_arena = :fit_arena_id
    ';

        $totalReservations = $connection->executeQuery($sqlTotalReservations, ['fit_arena_id' => $fitArenaId])->fetchOne();
        return $this->render('home/proto_chiffre_affaire.html.twig', [
            'fit_arena_Id' => $fitArenaId,
            'fit_arena_name' => $fitArenaName,
            'chiffre_affaire' => $result['chiffre_affaire'],
            'sport_repartitions' => $sportRepartitions,
            'total_reservations' => $totalReservations,
            'data' => $resultY,
            'type' => 'year',
            'predi_chiffre_affaire' => $prediresult['predi_chiffre_affaire'],
            'predi_data' => $prediresultA,
        ]);
    }



























}
