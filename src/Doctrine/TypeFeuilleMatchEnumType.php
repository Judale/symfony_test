<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeFeuilleMatchEnumType extends Type
{
    const TYPE_FEUILLE_MATCH_ENUM= 'Type_Feuille_Match_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('point','multiple','set','')";
    }

    public function getName()
    {
        return self::TYPE_FEUILLE_MATCH_ENUM;
    }
}
