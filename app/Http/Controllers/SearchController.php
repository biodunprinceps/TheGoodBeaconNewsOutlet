<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $articles = Article::query()
            ->published()
            ->search($query)
            ->with(['category', 'user', 'tags'])
            ->paginate(12);

        return view('search.index', [
            'articles' => $articles,
            'query' => $query,
            'total' => $articles->total(),
        ]);
    }
}
