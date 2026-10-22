<?php

namespace App\Http\Controllers;

use App\Models\Game;

class HomeController extends Controller
{
    public function index()
    {
        $games = Game::all();
        return view('home', compact('games'));
    }
}