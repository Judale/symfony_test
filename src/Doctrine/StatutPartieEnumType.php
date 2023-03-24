<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StatutPartieEnumType extends Type
{
    const STATUT_PARTIE_ENUM = 'Statut_Partie_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('attente','demarree','terminee','reservation_expiree')";
    }

    public function getName()
    {
        return self::STATUT_PARTIE_ENUM;
    }
}
