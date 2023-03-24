<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypePorteurEnumType extends Type
{
    const TYPE_PORTEUR_ENUM= 'Type_Porteur_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('animateur','adherent')";
    }

    public function getName()
    {
        return self::TYPE_PORTEUR_ENUM;
    }
}
