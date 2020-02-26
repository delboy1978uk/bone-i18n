<?php declare(strict_types=1);

namespace Bone\I18n;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Mvc\View\Extension\Plates\LocaleLink;
use Bone\Mvc\View\Extension\Plates\Translate;
use Bone\Mvc\View\PlatesEngine;
use Bone\Service\TranslatorFactory;
use Laminas\I18n\Translator\Translator;

class I18nPackage implements RegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
        $config = $c->has('i18n') ? $c->get('i18n') : [

        ];
        $engine = $c->get(PlatesEngine::class);

        if (is_array($config)) {
            $factory = new TranslatorFactory();
            $translator = $factory->createTranslator($config);
            $engine->loadExtension(new Translate($translator));
            $engine->loadExtension(new LocaleLink());
            $defaultLocale = $config['default_locale'] ?: 'en_GB';
            $translator->setLocale($defaultLocale);
            $c[Translator::class] = $translator;
        }
    }

    /**
     * @return string
     */
    public function getEntityPath(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    public function hasEntityPath(): bool
    {
        return false;
    }
}
