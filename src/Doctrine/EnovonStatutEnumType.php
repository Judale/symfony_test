<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnovonStatutEnumType extends Type
{
    const ENOVON_STATUT_ENUM = 'Enovon_Statut_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('en_attente', 'en_cours','valide')";
    }

    public function getName()
    {
        return self::ENOVON_STATUT_ENUM;
    }
}
