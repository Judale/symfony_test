<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class typeContactEnumType extends Type
{
    const TYPE_CONTACT_ENUM= 'type_Contact_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('classique','animateur_sans_compte','adherent_sans_compte')";
    }

    public function getName()
    {
        return self::TYPE_CONTACT_ENUM;
    }
}
