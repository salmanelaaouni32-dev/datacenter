<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resource;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch resources with their category, assuming we want to show active ones or all
        $resources = Resource::with('category')->get();
        return view('welcome', compact('resources'));
    }
}