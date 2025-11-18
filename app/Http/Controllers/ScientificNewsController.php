<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ScientificNew;

class ScientificNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = ScientificNew::orderBy('published_date', 'desc')->get();
        return view('scientific-news.index', ['news' => $news]);
    }
}
