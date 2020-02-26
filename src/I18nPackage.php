<?php declare(strict_types=1);

namespace Bone\I18n;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Exception;
use Bone\Http\Middleware\Stack;
use Bone\Http\MiddlewareAwareInterface;
use Bone\I18n\Http\Middleware\I18nMiddleware;
use Bone\I18n\View\Extension\LocaleLink;
use Bone\I18n\View\Extension\Translate;
use Bone\Mvc\View\PlatesEngine;
use Bone\I18n\Service\TranslatorFactory;
use Laminas\I18n\Translator\Translator;

class I18nPackage implements RegistrationInterface, MiddlewareAwareInterface
{
    /**
     * @param Container $c
     * @throws Exception
     */
    public function addToContainer(Container $c)
    {
        if ($c->has('i18n')) {
            $config = $c->get('i18n');
            $factory = new TranslatorFactory();
            $translator = $factory->createTranslator($config);
            $engine = $c->get(PlatesEngine::class);
            $engine->loadExtension(new Translate($translator));
            $engine->loadExtension(new LocaleLink($config['enabled']));
            $defaultLocale = $config['default_locale'] ?: 'en_GB';
            $translator->setLocale($defaultLocale);
            $c[Translator::class] = $translator;
        } else {
            throw new Exception('I18nPackage is registered but there is no i18n config. See the 
            delboy1978uk/bone-i18n README.', 418);
        }
    }

    /**
     * @param Stack $stack
     */
    public function addMiddleware(Stack $stack, Container $c): void
    {
        if ($c->has('i18n')) {
            $i18n = $c->get('i18n');
            $translator = $c->get(Translator::class);
            $i18nMiddleware = new I18nMiddleware($translator, $i18n['supported_locales'], $i18n['default_locale'], $i18n['enabled']);
            $stack->addMiddleWare($i18nMiddleware);
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
