<?php declare(strict_types=1);

namespace Bone\I18n;

use Barnacle\Container;
use Barnacle\Exception\NotFoundException;
use Barnacle\RegistrationInterface;
use Bone\Http\GlobalMiddlewareRegistrationInterface;
use Bone\Http\Middleware\Stack;
use Bone\I18n\Http\Middleware\I18nMiddleware;
use Bone\I18n\View\Extension\LocaleLink;
use Bone\I18n\View\Extension\Translate;
use Bone\View\ViewEngine;
use Bone\I18n\Service\TranslatorFactory;
use Bone\Contracts\Service\TranslatorInterface;
use Locale;

class I18nPackage implements RegistrationInterface, GlobalMiddlewareRegistrationInterface
{
    public function addToContainer(Container $c): void
    {
        if ($c->has('i18n')) {
            $i18n = $c->get('i18n');
            $factory = new TranslatorFactory();
            $translator = $factory->createTranslator($i18n);
            $engine = $c->get(ViewEngine::class);
            $engine->loadExtension(new Translate($translator));
            $engine->loadExtension(new LocaleLink($i18n['enabled']));
            $defaultLocale = $i18n['default_locale'] ?: 'en_GB';
            $translator->setLocale($defaultLocale);
            Locale::setDefault($defaultLocale);
            $c[TranslatorInterface::class] = $translator;
        } else {
            throw new NotFoundException('I18nPackage is registered but there is no i18n config. See the 
            delboy1978uk/bone-i18n README.', 418);
        }
    }

    public function getMiddleware(Container $c): array
    {
        if ($c->has('i18n')) {
            $i18n = $c->get('i18n');
            $translator = $c->get(TranslatorInterface::class);
            $i18nMiddleware = new I18nMiddleware($translator, $i18n['supported_locales'], $i18n['default_locale'], $i18n['enabled']);

            return [$i18nMiddleware];
        }

        return [];
    }

    public function getGlobalMiddleware(Container $c): array
    {
        if ($c->has('i18n') && $c->get('i18n')['enabled']) {
            return [I18nMiddleware::class];
        }

        return [];
    }
}
