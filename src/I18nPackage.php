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
use Laminas\I18n\Translator\Translator;
use Locale;

class I18nPackage implements RegistrationInterface, GlobalMiddlewareRegistrationInterface
{
    /**
     * @param Container $c
     * @throws Exception
     */
    public function addToContainer(Container $c)
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
            $c[Translator::class] = $translator;
        } else {
            throw new NotFoundException('I18nPackage is registered but there is no i18n config. See the 
            delboy1978uk/bone-i18n README.', 418);
        }
    }

    /**
     * @param Container $c
     * @return array
     */
    public function getMiddleware(Container $c): array
    {
        if ($c->has('i18n')) {
            $i18n = $c->get('i18n');
            $translator = $c->get(Translator::class);
            $i18nMiddleware = new I18nMiddleware($translator, $i18n['supported_locales'], $i18n['default_locale'], $i18n['enabled']);

            return [$i18nMiddleware];
        }

        return [];
    }

    /**
     * @return array
     */
    public function getGlobalMiddleware(Container $c): array
    {
        if ($c->has('i18n') && $c->get('i18n')['enabled']) {
            return [I18nMiddleware::class];
        }
    }
}
