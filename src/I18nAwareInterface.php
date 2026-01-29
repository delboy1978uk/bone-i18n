<?php

namespace Bone\I18n;

use Bone\Contracts\Service\TranslatorInterface;

interface I18nAwareInterface
{
    public function getTranslator(): TranslatorInterface;
    public function setTranslator(TranslatorInterface $translator): void;
}
