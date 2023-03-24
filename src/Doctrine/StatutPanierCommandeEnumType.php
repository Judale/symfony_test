<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StatutPanierCommandeEnumType extends Type
{
    const STATUT_PANIER_COMMANDE_ENUM = 'Statut_Panier_Commande_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('panier','a_payer','commande')";
    }

    public function getName()
    {
        return self::STATUT_PANIER_COMMANDE_ENUM;
    }
}
