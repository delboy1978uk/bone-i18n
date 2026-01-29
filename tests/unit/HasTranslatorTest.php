<?php declare(strict_types=1);

namespace BoneTest;

use Bone\I18n\Traits\HasTranslatorTrait;
use Bone\Contracts\Service\TranslatorInterface;
use Codeception\Test\Unit;

class HasTranslatorTest extends Unit
{
    public function testTranslator()
    {
        $class = new class {
          use HasTranslatorTrait;
        };

        $class->setTranslator($this->makeEmpty(TranslatorInterface::class));
        $this->assertInstanceOf(TranslatorInterface::class, $class->getTranslator());
    }
}
