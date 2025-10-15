<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum CriteriaValueType : string implements TranslatableInterface  {
    
    case INT = 'int';
    case FLOAT = 'float';
    case TEXT = 'text';
    case LONG_TEXT = 'textarea';
    case RANGE = 'range';
    case BOOLEAN = 'boolean';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string {
        return match($this) {
            self::INT => 'Entier',
            self::FLOAT => 'Nombre à virgule',
            self::TEXT => 'Texte court',
            self::LONG_TEXT => 'Long text',
            self::RANGE => 'Plage',
            self::BOOLEAN => 'Booleen'
        };
    }
}