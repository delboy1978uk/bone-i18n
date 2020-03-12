<?php declare(strict_types=1);

namespace BoneTest\FakePackage;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\I18n\I18nRegistrationInterface;

class FakePackagePackage implements RegistrationInterface, I18nRegistrationInterface
{
    public function addToContainer(\Barnacle\Container $c)
    {
        // TODO: Implement addToContainer() method.
    }

    public function getTranslationsDirectory(): string
    {
        return __DIR__ . '/translations';
    }
}