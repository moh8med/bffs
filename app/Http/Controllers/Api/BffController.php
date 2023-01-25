<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class BffController extends Controller
{
    public function __invoke(Request $request, string $uri)
    {
        // return 'https://' . parse_url(config('bff.base_url'), PHP_URL_HOST) . '/' . $uri;

        $response = Http::acceptJson()
            ->withHeaders([
                'Host' => parse_url(config('bff.base_url'), PHP_URL_HOST)
            ])
            ->withToken($request->bearerToken())
            ->timeout(5)
            ->connectTimeout(5)
            ->retry(3, 100, throw: false)
            ->baseUrl(config('bff.base_url'))
            ->send($request->method(), $uri);
            // ->onError(function (callable $callback) {
            //     dd($callback);
            // });
            // ->throw(function ($response, $e) {
            //     dd($response);
            //     dd($e);
            // });

        // Error handling

        if ($response->unauthorized()) {
            return abort(401, 'Unauthorized.');
        }

        if ($response->forbidden()) {
            return abort(403, 'Forbidden.');
        }

        if ($response->serverError()) {
            return abort(500, 'An unexpected error occurred.');
            // return response()->json([
            //     'message' => 'An unexpected error occurred.'
            // ], $response->status());
        }


        return response($response->getBody())
            ->withHeaders($response->headers())
            ->withHeaders([
                'X-Powered-By' => null,
                'Server' => 'Apache',
            ])
            ->setStatusCode($response->status());

        // return [
        //     $response->status(),
        //     $response->headers(),
        // ];
    }
}
