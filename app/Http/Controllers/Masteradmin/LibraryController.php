<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index(): View
    {
        return view('masteradmin.library.index');
    }

    public function create(): View
    {
        return view('masteradmin.library.create');

    }


}