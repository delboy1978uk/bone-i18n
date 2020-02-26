<?php declare(strict_types=1);

namespace Bone\I18n\Http\Middleware;

use Bone\Mvc\Router\NotFoundException;
use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\Translator;

class I18nMiddleware implements MiddlewareInterface
{
    const REGEX_LOCALE = '#^/(?P<locale>[a-z]{2}[-_][a-zA-Z]{2})(?:/|$)#';

    /** @var Translator$translator */
    private $translator;

    /** @var array $supportedLocales */
    private $supportedLocales;

    /** @var string $defaultLocale */
    private $defaultLocale;

    /** @var bool $enabled */
    private $enabled;

    /**
     * InternationalisationMiddleware constructor.
     * @param  $helper
     * @param string|null $defaultLocale
     */
    public function __construct(Translator $translator, array $supportedLocales, string $defaultLocale, bool $enabled)
    {
        $this->translator = $translator;
        $this->supportedLocales = $supportedLocales;
        $this->defaultLocale = $defaultLocale;
        $this->enabled = $enabled;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->enabled === true) {
            $uri = $request->getUri();
            $path = $uri->getPath();

            if (! preg_match(self::REGEX_LOCALE, $path, $matches)) {
                Locale::canonicalize($this->defaultLocale);
                Locale::setDefault($this->defaultLocale);
            } else {
                $locale = $matches['locale'];

                if (in_array($locale, $this->supportedLocales)) {
                    $locale = Locale::canonicalize($locale);
                    Locale::setDefault($locale);
                    $this->translator->setLocale($locale);
                    $path = substr($path, strlen($locale) + 1);
                    $uri = $uri->withPath($path);
                    $request = $request->withAttribute('locale', $locale);
                    $request = $request->withUri($uri);
                }
            }
        }

        return $handler->handle($request);
    }


}