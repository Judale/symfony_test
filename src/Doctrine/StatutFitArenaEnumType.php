<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StatutFitArenaEnumType extends Type
{
    const STATUT_FIT_ARENA_ENUM = 'Statut_Fit_Arena_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('a_venir')";
    }

    public function getName()
    {
        return self::STATUT_FIT_ARENA_ENUM;
    }
}
