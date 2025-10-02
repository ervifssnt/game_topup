@extends('layout')

@section('title', 'Available Games')

@section('styles')
<style>
    .games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 20px;
    }
    .game-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        text-align: center;
    }
    .game-card img {
        width: 150px;
        height: auto;
        margin: 20px 0;
    }
    .game-card h3 {
        margin: 10px 0;
    }
    .btn {
        display: inline-block;
        margin-top: 15px;
        padding: 12px 30px;
        background: #00ff55;
        color: black;
        font-weight: bold;
        border-radius: 5px;
        text-decoration: none;
        transition: background 0.3s;
        border: none;
        cursor: pointer;
    }
    .btn:hover {
        background: #00cc44;
        color: white;
    }
</style>
@endsection

@section('content')
    <h2 style="text-align: center; margin-bottom: 20px;">Available Games for Top Up</h2>

    @if($games->count() > 0)
        <div class="games-grid">
            @foreach($games as $game)
                <div class="game-card">
                    <h3>{{ $game->name }}</h3>
                    @if($game->logo)
                        <img src="{{ asset($game->logo) }}" alt="{{ $game->name }}">
                    @endif
                    @if($game->description)
                        <p>{{ $game->description }}</p>
                    @endif
                    <a class="btn" href="{{ route('topup', $game->id) }}">Top Up</a>
                </div>
            @endforeach
        </div>
    @else
        <p style="text-align: center;">No games available.</p>
    @endif
@endsection