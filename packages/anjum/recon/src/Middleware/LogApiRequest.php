<?php

declare(strict_types=1);

namespace Anjum\Recon\Middleware;

use Anjum\Recon\Services\RequestLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    public function __construct(private readonly RequestLogger $logger)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $this->logger->log($request, $response);

        return $response;
    }
}
