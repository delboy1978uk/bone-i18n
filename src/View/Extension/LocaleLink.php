<?php

namespace Bone\I18n\View\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Locale;

class LocaleLink implements ExtensionInterface
{
    /** @var bool $enabled */
    private $enabled;

    /**
     * LocaleLink constructor.
     * @param bool $enabled
     */
    public function __construct(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param Engine $engine
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('l', [$this, 'locale']);
        $engine->registerFunction('locale', [$this, 'locale']);
    }

    /**
     * @return string
     */
    public function locale() : string
    {
        return $this->enabled ? '/' . Locale::getDefault() : '';
    }
}
