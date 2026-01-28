<?php

namespace BoneTest;

use Barnacle\Container;
use Barnacle\Exception\NotFoundException;
use Bone\Http\Middleware\Stack;
use Bone\I18n\Form;
use Bone\I18n\Http\Middleware\I18nMiddleware;
use Bone\I18n\I18nPackage;
use Bone\I18n\Service\TranslatorFactory;
use Bone\I18n\View\Extension\LocaleLink;
use Bone\I18n\View\Extension\Translate;
use Bone\Router\Router;
use Bone\View\ViewPackage;
use BoneTest\FakePackage\FakePackagePackage;
use Codeception\Test\Unit;
use Laminas\Diactoros\ServerRequest;
use Symfony\Component\Translation\Loader\MoFileLoader;
use Symfony\Component\Translation\Translator;
use Bone\Contracts\Service\TranslatorInterface;
use Locale;

class I18nTest extends Unit
{
    /** @var Container */
    protected $container;

    protected function _before()
    {
        $this->container = $c = new Container();
        $this->container['packages'] = [];
        $this->container['viewFolder'] = 'tests/_data/';
        $this->container['default_layout'] = 'whatever';
        $this->container['error_pages'] = [500 => 'whatever'];
        $this->container['i18n'] = [
            'enabled' => true,
            'translations_dir' => 'tests/_data/translations',
            'type' => MoFileLoader::class,
            'default_locale' => 'fr_BE',
            'supported_locales' => ['fr_BE'],
            'date_format' => 'd/m/Y',
        ];
        $router = new Router();
        $this->container[Router::class] = $router;
        $this->container[Stack::class] = new Stack($router);
    }

    protected function _after()
    {
        unset($this->container);
    }

    public function testI18nPackageThrowsException()
    {
        $this->container->offsetUnset('i18n');
        $this->expectException(NotFoundException::class);
        $package = new I18nPackage();
        $package->addToContainer($this->container);
    }

    private function setUpPackage()
    {
        $package = new ViewPackage();
        $package->addToContainer($this->container);
        $package = new I18nPackage();
        $package->addToContainer($this->container);
        $middleware = $package->getMiddleware($this->container)[0];
        /** @var Stack $stack */
        $stack = $this->container->get(Stack::class);
        $stack->addMiddleWare($middleware);
        $factory = new TranslatorFactory();
        $translator = $this->container->get(TranslatorInterface::class);
        $factory->addPackageTranslations($translator, new FakePackagePackage(), 'fr_BE');
    }

    public function testI18nPackage()
    {
        $this->setUpPackage();
        $this->assertTrue($this->container->has(TranslatorInterface::class));
    }

    public function testI18nForm()
    {
        $this->setUpPackage();
        $translator = $this->container->get(TranslatorInterface::class);
        $form = new Form('i18nForm', $translator);
        $form->init();
        $this->assertInstanceOf(TranslatorInterface::class, $form->getTranslator());
    }

    public function testLocale()
    {
        $locale = Locale::getDefault();
        $link = new LocaleLink(true);
        $link = $link->locale();
        $this->assertEquals('/' . $locale, $link);
    }

    public function testTranslateViewHelper()
    {
        $this->setUpPackage();
        $translator = $this->container->get(TranslatorInterface::class);
        $viewHelper = new Translate($translator);
        $translation = $viewHelper->translate('download');
        $this->assertEquals('Télécharger', $translation);
    }

    public function testMiddleware()
    {
        $this->setUpPackage();
        $translator = $this->container->get(TranslatorInterface::class);
        $middleware = new I18nMiddleware($translator, ['fr_BE'], 'fr_BE', true);
        $request = new ServerRequest([],[],'/fr_BE/with-locale');
        $response = $middleware->process($request, new MiddlewareTestHandler());
        $this->assertEquals('/with-locale', $response->getBody()->getContents());
    }
}


