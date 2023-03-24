<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class SportTypeEnumType extends Type
{
    const SPORT_TYPE_ENUM = 'sport_type_enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('principal', 'secondaire')";
    }

    public function getName()
    {
        return self::SPORT_TYPE_ENUM;
    }
}

