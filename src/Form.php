<?php

namespace Bone\I18n;

use Bone\I18n\I18nAwareInterface;
use Bone\I18n\Traits\HasTranslatorTrait;
use Bone\Contracts\Service\TranslatorInterface;
use Del\Form\AbstractForm;
use Del\Form\Form as BaseForm;

class Form extends AbstractForm implements I18nAwareInterface
{
    use HasTranslatorTrait;

    public function __construct(string $name, TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
        parent::__construct($name);
    }

    /**
     *  Extend this form an' ye can add yer form elements here
     * @see https://github.com/delboy1978uk/form
     */
    public function init(): void {}
}
