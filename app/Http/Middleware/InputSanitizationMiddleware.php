<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InputSanitizationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->query->replace($this->sanitizeData($request->query->all()));
        $request->request->replace($this->sanitizeData($request->request->all()));
        $request->cookies->replace($this->sanitizeData($request->cookies->all()));

        return $next($request);
    }

    protected function sanitizeData(mixed $data): mixed
    {
        if (is_array($data)) {
            $sanitized = [];

            foreach ($data as $key => $value) {
                $sanitized[$key] = $this->sanitizeData($value);
            }

            return $sanitized;
        }

        if (is_string($data)) {
            return str_replace("\0", '', trim($data));
        }

        if (is_bool($data) || is_int($data) || is_float($data) || $data === null) {
            return $data;
        }

        return $data;
    }
}
