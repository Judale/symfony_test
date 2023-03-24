<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeEquipeEnumType extends Type
{
    const TYPE_EQUIPE_ENUM= 'Type_Equipe_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('classique','non_joueur','spectateur')";
    }

    public function getName()
    {
        return self::TYPE_EQUIPE_ENUM;
    }
}
