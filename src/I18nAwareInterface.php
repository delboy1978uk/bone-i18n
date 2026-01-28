<?php

namespace Bone\I18n;

use Symfony\Contracts\Translation\TranslatorInterface;

interface I18nAwareInterface
{
    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface;

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void;
}
