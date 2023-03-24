<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeInviteEquipierEnumType extends Type
{
    const TYPE_INVITE_EQUIPIER_ENUM= 'Type_Invite_Equipier_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('grand_public','animateur','adherent','gestionnaire_organisme','gestionnaire_collectivite')";
    }

    public function getName()
    {
        return self::TYPE_INVITE_EQUIPIER_ENUM;
    }
}
