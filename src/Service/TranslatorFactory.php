<?php

namespace Bone\I18n\Service;

use Bone\Contracts\Service\TranslatorInterface;
use Bone\I18n\I18nRegistrationInterface;
use Bone\I18n\Service\TranslatorService;
use Locale;

use Symfony\Component\Translation\Loader\MoFileLoader;

class TranslatorFactory
{
    public function createTranslator(array $config, $domain = 'default')
    {
        $translator = new TranslatorService(Locale::getDefault());
        $translator->addLoader('mo', new MoFileLoader());

        foreach ($config['supported_locales'] as $locale) {
            $file = $config['translations_dir'] . '/' . $locale .  '/' . $locale . '.mo';
            $translator->addResource('mo', $file, $locale, $domain);
        }

        return $translator;
    }

    public function addPackageTranslations(TranslatorInterface $translator, I18nRegistrationInterface $package, string $locale)
    {
        $dir = $package->getTranslationsDirectory();
        $translator->addResource(
            'mo',
            $dir . '/' . $locale . '/' . $locale . '.mo',
            $locale,
            'user'
        );

        return $translator;
    }
}