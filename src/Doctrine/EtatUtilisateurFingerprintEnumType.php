<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EtatUtilisateurFingerprintEnumType extends Type
{
    const ETAT_UTILISATEUR_FINGERPRINT_ENUM = 'Etat_Utilisateur_Fingerprint_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('en_attente', 'autorise')";
    }

    public function getName()
    {
        return self::ETAT_UTILISATEUR_FINGERPRINT_ENUM;
    }
}
