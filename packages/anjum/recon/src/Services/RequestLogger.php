<?php

declare(strict_types=1);

namespace Anjum\Recon\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger
{
    public function log(Request $request, Response $response): void
    {
        if (! config('recon.enabled')) {
            return;
        }

        $excludePaths = (array) config('recon.exclude_paths', []);
        if ($excludePaths !== [] && $request->is(...$excludePaths)) {
            return;
        }

        $entry = [
            'timestamp' => now()->toIso8601String(),
            'method'    => $request->method(),
            'url'       => $request->fullUrl(),
            'ip'        => $request->ip(),
            'status'    => $response->getStatusCode(),
        ];

        if (config('recon.log_body')) {
            $entry['body'] = $this->maskBody($request->all());
        }

        if (config('recon.log_headers')) {
            $entry['headers'] = $request->headers->all();
        }

        try {
            file_put_contents(
                config('recon.log_file'),
                json_encode($entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n---\n",
                FILE_APPEND | LOCK_EX
            );
        } catch (\Throwable $e) {
            Log::error('Recon failed to write request log: ' . $e->getMessage());
        }
    }

    private function maskBody(array $body): array
    {
        foreach ((array) config('recon.mask_fields', []) as $field) {
            if (array_key_exists($field, $body)) {
                $body[$field] = '[masked]';
            }
        }

        return $body;
    }
}
