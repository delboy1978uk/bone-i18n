<?php

namespace Bone\I18n\Traits;

use Bone\Contracts\Service\TranslatorInterface;

trait HasTranslatorTrait
{
    private TranslatorInterface $translator;

    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
