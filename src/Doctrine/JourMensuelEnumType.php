<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class JourMensuelEnumType extends Type
{
    const JOUR_MENSUEL_ENUM = 'Jour_Mensuel_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('1','2','','5','dernier3','4')";
    }

    public function getName()
    {
        return self::JOUR_MENSUEL_ENUM;
    }
}
