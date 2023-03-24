<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ReservationStatutEnumType extends Type
{
    const RESERVATION_STATUT_ENUM = 'Reservation_Statut_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('en_cours','a_payer','finalisee','expiree','annulee','fusionnee','jouee_et_terminee')";
    }

    public function getName()
    {
        return self::RESERVATION_STATUT_ENUM;
    }
}
