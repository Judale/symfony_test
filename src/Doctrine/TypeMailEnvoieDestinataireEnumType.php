<?php

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypeMailEnvoieDestinataireEnumType extends Type
{
    const TYPE_MAIL_ENVOIE_DESTINATAIRE_ENUM= 'Type_Mail_Envoie_Destinataire_Enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('to','bcc','cc')";
    }

    public function getName()
    {
        return self::TYPE_MAIL_ENVOIE_DESTINATAIRE_ENUM;
    }
}
