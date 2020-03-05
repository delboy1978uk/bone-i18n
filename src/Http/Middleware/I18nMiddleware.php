<?php declare(strict_types=1);

namespace Bone\I18n\Http\Middleware;

use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
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
        $uri = $request->getUri();
        $path = $uri->getPath();
        preg_match(self::REGEX_LOCALE, $path, $matches);
        $locale = $this->defaultLocale;

        if (isset($matches['locale'])) {
            $locale = in_array($matches['locale'], $this->supportedLocales) ? $matches['locale'] : $this->defaultLocale;
            $request = $this->stripLocaleFromUri($uri, $path, $locale, $request);
        }

        Locale::canonicalize($locale);
        Locale::setDefault($locale);
        $request = $request->withAttribute('locale', $locale);

        if ($this->enabled === true) {
            $this->translator->setLocale($locale);
        }

        return $handler->handle($request);
    }

    /**
     * @param UriInterface $uri
     * @param string $path
     * @param string $locale
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    private function stripLocaleFromUri(UriInterface $uri, string $path, string $locale, ServerRequestInterface $request): ServerRequestInterface
    {
        $path = substr($path, strlen($locale) + 1) ?? '/';
        $uri = $uri->withPath($path);
        $request = $request->withUri($uri);

        return $request;
    }
}