<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeReservationEnumType extends Type
{
    const TYPE_RESERVATION_ENUM= 'Type_Reservation_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('grand_public','animateur','adherent','gestionnaire_organisme')";
    }

    public function getName()
    {
        return self::TYPE_RESERVATION_ENUM;
    }
}
