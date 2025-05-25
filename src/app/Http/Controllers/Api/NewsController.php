<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query()->latest('published_at');

        if ($request->filled('currency')) {
            $query->whereJsonContains('currencies', [['code' => strtoupper($request->currency)]]);
        }

        if ($request->filled('from')) {
            $query->whereDate('published_at', '>=', $request->from);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'ILIKE', '%' . $request->search . '%')
                    ->orWhere('summary', 'ILIKE', '%' . $request->search . '%')
                    ->orWhere('source', 'ILIKE', '%' . $request->search . '%');
            });
        }

        $paginated = $query->paginate(20);

        return response()->json([
            'data' => $paginated->items(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }
}
