<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PagesController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function show($slug = null)
    {
        $cmsPage = Page::where('slug', $slug )->first();
        
        return view('pages.show', compact('cmsPage'));
    }
}
