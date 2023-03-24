<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeTerminalEnumType extends Type
{
    const TYPE_TERMINAL_ENUM= 'Type_Terminal_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('ecran_accueil','ecran_geant','tablette','ecran_attente')";
    }

    public function getName()
    {
        return self::TYPE_TERMINAL_ENUM;
    }
}
