<?php

namespace Bone\I18n\View\Extension;

use Laminas\I18n\Translator\Translator;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use League\Plates\Template\Template;
use Locale;

class Translate implements ExtensionInterface
{
    public ?Template $template = null;

    public function __construct(
        private Translator $translator
    ) {
    }

    public function register(Engine $engine): void
    {
        $engine->registerFunction('t', [$this, 'translate']);
        $engine->registerFunction('translate', [$this, 'translate']);
    }

    public function translate(string $string, string $domain = 'default') : string
    {
        return $this->translator->translate($string, $domain, Locale::getDefault());
    }
}
