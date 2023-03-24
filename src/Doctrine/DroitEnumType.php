<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class DroitEnumType extends Type
{
    const DROIT_ENUM = 'Droit_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('autorise', 'interdit')";
    }

    public function getName()
    {
        return self::DROIT_ENUM;
    }
}
