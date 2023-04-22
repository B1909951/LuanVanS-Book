<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class ExceptionHandlerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (Exception $e) {
            // Redirect to 'err-server' route
            $response = redirect('err-server');

            // Ensure to set a response status code of 500
            $response->setStatusCode(500);

            return $response;
        }
    }
}
