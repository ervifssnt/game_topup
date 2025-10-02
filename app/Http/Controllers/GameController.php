<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // Show homepage with all games
    public function index()
    {
        $games = Game::all();
        return view('games.index', compact('games'));
    }

    // Show topup page for specific game
    public function topup($id)
    {
        $game = Game::with('topupOptions')->findOrFail($id);
        return view('games.topup', compact('game'));
    }
}