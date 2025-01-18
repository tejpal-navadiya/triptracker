<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class EncryptCookiesMiddleware
{
    protected $encrypter;

    public function __construct(EncrypterContract $encrypter, Application $app)
    {
        $this->encrypter = $encrypter;
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Handle the response based on its type
        if ($response instanceof RedirectResponse) {
            $response = $response->getTargetUrl();
            $response = redirect($response);
        }

        if ($response instanceof Response || $response instanceof SymfonyResponse) {
            return $this->encryptResponseCookies($response);
        }

        return $response;
    }

    protected function encryptResponseCookies(SymfonyResponse $response)
    {
        foreach ($response->headers->getCookies() as $cookie) {
            $encryptedValue = $this->encrypter->encrypt($cookie->getValue());
            $cookie->setValue($encryptedValue);
        }

        return $response;
    }
}
