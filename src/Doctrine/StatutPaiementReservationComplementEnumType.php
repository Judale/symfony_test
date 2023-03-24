<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StatutPaiementReservationComplementEnumType extends Type
{
    const STATUT_PAIEMENT_RESERVATION_COMPLEMENT_ENUM = 'Statut_Paiement_Reservation_Complement_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('en_attente','en_cours','finalise')";
    }

    public function getName()
    {
        return self::STATUT_PAIEMENT_RESERVATION_COMPLEMENT_ENUM;
    }
}
