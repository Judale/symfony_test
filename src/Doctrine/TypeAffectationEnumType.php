<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeAffectationEnumType extends Type
{
    const TYPE_AFFECTATION_ENUM= 'Type_Affectation_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('neant','organisme','sport')";
    }

    public function getName()
    {
        return self::TYPE_AFFECTATION_ENUM;
    }
}
