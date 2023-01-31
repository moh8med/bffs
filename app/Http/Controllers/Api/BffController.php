<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\BffRequest;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class BffController extends Controller
{
    public function request(Request $request, string $uri): Response
    {
        $response = Http::bff($request)
            ->acceptJson()
            ->send($request->method(), $request->route()->getPrefix() . '/' . $uri, [
                'query' => $request->query(),
            ]);

        return bff_response($response);
    }

    public function multipartRequest(BffRequest $request, string $uri): Response
    {
        $multipart = request_multipart($request->files->all());

        $response = Http::bff($request)
            ->acceptJson()
            ->send($request->method(), $request->route()->getPrefix() . '/' . $uri, [
                'query' => $request->except(files_keys($multipart)),
                'multipart' => $multipart,
            ]);

        return bff_response($response);
    }

    public function aggregation(Request $request): Response
    {
        $responses = Http::pool(fn (Pool $pool) => [
            $pool->as('users')->bff($request)->acceptJson()->get('/api/users'),
            $pool->as('products')->bff($request)->acceptJson()->get('/api/products'),
        ]);

        return bff_response($responses);
    }
}
