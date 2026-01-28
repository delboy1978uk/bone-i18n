<?php

namespace Bone\I18n\Traits;

use Symfony\Contracts\Translation\TranslatorInterface;

trait HasTranslatorTrait
{
    /** @var TranslatorInterface $translator */
    private $translator;

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
