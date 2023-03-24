<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EtatTempsFortEnumType extends Type
{
    const ETAT_TEMPS_FORT_ENUM = 'Etat_Temps_Fort_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('valide', 'annule')";
    }

    public function getName()
    {
        return self::ETAT_TEMPS_FORT_ENUM;
    }
}
