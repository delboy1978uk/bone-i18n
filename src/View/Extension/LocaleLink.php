<?php

namespace Bone\I18n\View\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Locale;

class LocaleLink implements ExtensionInterface
{
    public ?Template $template = null;

    public function __construct(
        private bool $enabled
    ) {
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('l', [$this, 'locale']);
        $engine->registerFunction('locale', [$this, 'locale']);
    }

    public function locale(): string
    {
        return $this->enabled ? '/' . Locale::getDefault() : '';
    }
}
