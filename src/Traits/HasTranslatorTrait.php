<?php

namespace Bone\I18n\Traits;

use Laminas\I18n\Translator\Translator;

trait HasTranslatorTrait
{
    /** @var Translator $translator */
    private $translator;

    /**
     * @return Translator
     */
    public function getTranslator(): Translator
    {
        return $this->translator;
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator): void
    {
        $this->translator = $translator;
    }
}
