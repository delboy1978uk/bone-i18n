<?php

declare(strict_types=1);

namespace Bone\I18n\Service;

use Bone\Contracts\Service\TranslatorInterface;
use Symfony\Component\Translation\Translator;

class TranslatorService extends Translator implements TranslatorInterface
{
    public function translate(string $message, ?string $textDomain = null, ?string $locale = null, ?array $parameters = null): string
    {
        return $this->trans($message, $parameters, $textDomain, $locale);
    }
}