<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class BffController extends Controller
{
    public function __invoke(Request $request, string $uri)
    {
        // request validation
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            $rules = [];

            // register validation rules
            foreach (config('bff.validation.rules') as $key => $value) {
                if ($request->has($key)) {
                    $rules[$key] = $value;
                }
            }

            // register uploaded files to scan with ClamAV
            $rule = config('bff.validation.rules._custom_file_rule');
            foreach ($request->files->all() as $key => $file) {
                if (isset($rules[$key])) {
                    continue;
                }

                $rules[$key] = $rule;
            }

            if ($rules) {
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()
                        ->json($validator->errors(), 422);
                }
            }
        }

        // prepare and send request

        $response = Http::bff($request)
            ->acceptJson()
            ->send($request->method(), $request->route()->getPrefix() . '/' . $uri, [
                (
                    in_array($request->method(), ['POST', 'PUT', 'PATCH'])
                        ? 'form_params'
                        : 'query'
                ) => $request->all(),
            ]);

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

        if ($response->serverError()) {
            return response('An unexpected error occurred.')
                ->withHeaders([
                    'X-Powered-By' => null,
                    'Server' => 'Apache',
                ])
                ->setStatusCode($response->status());
        }

        return response($response->getBody())
            ->withHeaders($response->headers())
            ->withHeaders([
                'X-Powered-By' => null,
                'Server' => 'Apache',
            ])
            ->setStatusCode($response->status());
    }
}
