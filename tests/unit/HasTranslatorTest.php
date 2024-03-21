<?php declare(strict_types=1);

namespace BoneTest;

use Bone\I18n\Traits\HasTranslatorTrait;
use Codeception\Test\Unit;
use Laminas\I18n\Translator\Translator;

class HasTranslatorTest extends Unit
{
    public function testTranslator()
    {
        $class = new class {
          use HasTranslatorTrait;
        };

        $class->setTranslator($this->make(Translator::class));
        $this->assertInstanceOf(Translator::class, $class->getTranslator());
    }
}
