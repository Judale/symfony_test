<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class JourSemaineEnumType extends Type
{
    const JOUR_SEMAINE_ENUM = 'Jour_Semaine_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('1','2','3','4','5','6','7')";
    }

    public function getName()
    {
        return self::JOUR_SEMAINE_ENUM;
    }
}
