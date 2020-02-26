<?php

namespace Bone\I18n\View\Extension;

use Laminas\I18n\Translator\Translator;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Locale;

class Translate implements ExtensionInterface
{
    /** @var Translator $translator */
    private $translator;

    /**
     * Translate constructor.
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Engine $engine
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('t', [$this, 'translate']);
        $engine->registerFunction('translate', [$this, 'translate']);
    }

    /**
     * @param string $string
     * @param string $domain
     * @return string
     */
    public function translate(string $string, string $domain = 'default') : string
    {
        return $this->translator->translate($string, $domain, Locale::getDefault());
    }
}
