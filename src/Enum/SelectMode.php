<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum SelectMode : string implements TranslatableInterface  {
    
    case SINGLE = 'single_select';
    case MULTIPLE = 'multiple_select';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string {
        return match($this) {
            self::SINGLE => 'Selection unique',
            self::MULTIPLE => 'Selection multiple'
        };
    }
}