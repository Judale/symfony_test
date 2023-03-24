<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeDonneeEnumType extends Type
{
    const TYPE_DONNEE_ENUM= 'Type_Donnee_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('string','int','bool')";
    }

    public function getName()
    {
        return self::TYPE_DONNEE_ENUM;
    }
}
