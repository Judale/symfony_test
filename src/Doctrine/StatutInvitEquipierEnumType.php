<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StatutInvitEquipierEnumType extends Type
{
    const STATUT_INVIT_EQUIPIER_ENUM = 'Statut_Invit_Equipier_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('valide','refuse','peut-etre','attente_reponse','supprimer')";
    }

    public function getName()
    {
        return self::STATUT_INVIT_EQUIPIER_ENUM;
    }
}
