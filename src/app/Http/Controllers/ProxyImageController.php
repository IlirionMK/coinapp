<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class ProxyImageController extends Controller
{
    public function __invoke(Request $request)
    {
        $url = $request->query('url');

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            abort(400, 'Invalid image URL');
        }

        $key = 'img_proxy_' . md5($url);

        $image = Cache::remember($key, 60 * 24, function () use ($url) {
            try {
                $response = Http::timeout(3)->get($url);

                if (!$response->successful()) {
                    abort(404, 'Image not found');
                }

                return $response->body();
            } catch (\Exception $e) {
                abort(404, 'Error fetching image');
            }
        });

        $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';

        return Response::make($image, 200, [
            'Content-Type' => 'image/' . $ext,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
