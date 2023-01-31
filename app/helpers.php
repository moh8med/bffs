<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

if (! function_exists('str_dot_to_array')) {
    function str_dot_to_array(string $name): string
    {
        if (! str_contains($name, '.')) {
            return $name;
        }

        $segments = explode('.', $name);

        $str = '';

        foreach ($segments as $key => $segment) {
            if ($key !== 0) {
                $str .= '[';
            }

            $str .= $segment;

            if ($key !== 0) {
                $str .= ']';
            }
        }

        return $str;
    }
}

if (! function_exists('bff_response')) {
    function bff_response(array|\Illuminate\Http\Client\Response $response): \Illuminate\Http\Response
    {
        if (is_array($response)) {
            $data = [];
            $first_key = '';

            foreach ($response as $key => $res) {
                if (! $first_key) {
                    $first_key = $key;
                }

                if (! $res->ok()) {
                    return response('An unexpected error occurred.', 500)
                        ->withHeaders([
                            'X-Powered-By' => null,
                            'Server' => 'Apache',
                        ]);
                }

                $data[$key] = $res->json();
            }

            return response($data)
                ->withHeaders($response[$first_key]->headers())
                ->withHeaders([
                    'X-Powered-By' => null,
                    'Server' => 'Apache',
                ])
                ->setStatusCode(200);
        }

        // Error handling

        // if ($response->unauthorized()) {
        //     return response('Unauthorized.')
        //         ->withHeaders([
        //             'X-Powered-By' => null,
        //             'Server' => 'Apache',
        //         ])
        //         ->setStatusCode($response->status());
        // }

        // if ($response->forbidden()) {
        //     return response('Forbidden.')
        //         ->withHeaders([
        //             'X-Powered-By' => null,
        //             'Server' => 'Apache',
        //         ])
        //         ->setStatusCode($response->status());
        // }

        return (
            ($response->serverError() && ! App::isLocal())
                ? response('An unexpected error occurred.')
                : response($response->getBody())
            )
                ->withHeaders($response->headers())
                ->withHeaders([
                    'X-Powered-By' => null,
                    'Server' => 'Apache',
                ])
                ->setStatusCode($response->status());
    }
}

if (! function_exists('request_multipart')) {
    function request_multipart(array $files): array
    {
        if (! $files) {
            return [];
        }

        $files = Arr::dot($files);

        $multipart = [];

        foreach ($files as $key => $file) {
            $multipart[$key] = [
                'name' => str_dot_to_array($key),
                'contents' => $file->getContent(),
                'filename' => $file->getClientOriginalName(),
            ];
        }

        return $multipart;
    }
}


if (! function_exists('files_keys')) {
    function files_keys(array $files): array
    {
        if (! $files) {
            return [];
        }

        $keys = array_keys($files);

        $list = [];

        foreach ($keys as $key) {
            if (str_contains($key, '.')) {
                $list[  ] = explode('.', $key)[0];
                continue;
            }

            $list[] = $key;
        }

        return array_unique($list);
    }
}
