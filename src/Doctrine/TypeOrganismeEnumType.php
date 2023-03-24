<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeOrganismeEnumType extends Type
{
    const TYPE_ORGANISME_ENUM= 'Type_Organisme_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('ecole','college','lycee','enseignement_superieur','association','entreprise')";
    }

    public function getName()
    {
        return self::TYPE_ORGANISME_ENUM;
    }
}
