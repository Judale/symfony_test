<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StatutPartiePeriodeEnumType extends Type
{
    const STATUT_PARTIE_PERIODE_ENUM= 'Statut_Partie_Periode_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('attente','demarree','terminee','reservation_expiree')";
    }

    public function getName()
    {
        return self::STATUT_PARTIE_PERIODE_ENUM;
    }
}
