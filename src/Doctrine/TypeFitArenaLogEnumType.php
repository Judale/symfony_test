<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeFitArenaLogEnumType extends Type
{
    const TYPE_FIT_ARENA_LOG_ENUM= 'Type_Fit_Arena_Log_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('ca','serveur')";
    }

    public function getName()
    {
        return self::TYPE_FIT_ARENA_LOG_ENUM;
    }
}
