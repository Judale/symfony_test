<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeUtilisateurEnumType extends Type
{
    const TYPE_UTILISATEUR_ENUM= 'Type_Utilisateur_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('administrateur','utilisateur','contact')";
    }

    public function getName()
    {
        return self::TYPE_UTILISATEUR_ENUM;
    }
}
