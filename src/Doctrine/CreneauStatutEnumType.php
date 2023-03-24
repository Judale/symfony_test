<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class CreneauStatutEnumType extends Type
{
    const CRENEAU_STATUT_ENUM = 'creneau_statut_enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('disponible', 'reserve_organisme', 'reserve_tempo', 'reserve_valide')";
    }

    public function getName()
    {
        return self::CRENEAU_STATUT_ENUM;
    }
}


