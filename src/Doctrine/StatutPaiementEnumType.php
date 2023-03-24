<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StatutPaiementEnumType extends Type
{
    const STATUT_PAIEMENT_ENUM = 'Statut_Paiement_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('creation','annule','presentation','acceptation','refus','ajustement','reglement','solde','impaye','abandon')";
    }

    public function getName()
    {
        return self::STATUT_PAIEMENT_ENUM;
    }
}
