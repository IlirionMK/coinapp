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

        // Кешируем изображение на 1 день
        $image = Cache::remember($key, 60 * 24, function () use ($url) {
            return Http::timeout(5)->get($url)->body();
        });

        $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

        return Response::make($image, 200, [
            'Content-Type' => 'image/' . ($ext ?: 'png'),
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
