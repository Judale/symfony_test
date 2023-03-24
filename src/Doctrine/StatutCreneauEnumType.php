<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StatutCreneauEnumType extends Type
{
    const STATUT_CRENEAU_ENUM = 'Statut_Creneau_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('disponible','reserve_organisme','reserve_tempo','reserve_valide')";
    }

    public function getName()
    {
        return self::STATUT_CRENEAU_ENUM;
    }
}
