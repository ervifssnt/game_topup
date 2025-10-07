@extends('layouts.main')

@section('title', 'Homepage - UP STORE')

@section('content')
<style>
    .hero-banner {
        background: linear-gradient(135deg, #00a8ff 0%, #0077cc 100%);
        border-radius: 16px;
        padding: 60px 40px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    
    .hero-title {
        font-size: 48px;
        font-weight: 900;
        color: white;
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 3px 3px 0 rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        background: #ffeb3b;
        color: #000;
        display: inline-block;
        padding: 12px 32px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: white;
        margin-bottom: 24px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .flash-sale-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 50px;
    }
    
    .flash-card {
        background: #2a2a2a;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 3/4;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    
    .flash-card:hover {
        transform: translateY(-8px);
        border-color: #FF8C00;
        box-shadow: 0 8px 24px rgba(255, 140, 0, 0.3);
    }
    
    .trending-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 20px;
    }
    
    .game-card {
        background: #2a2a2a;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    
    .game-card:hover {
        transform: translateY(-8px);
        border-color: #FF8C00;
        box-shadow: 0 8px 24px rgba(255, 140, 0, 0.3);
    }
    
    .game-image {
        width: 100%;
        aspect-ratio: 3/4;
        object-fit: cover;
        background: linear-gradient(135deg, #3a3a3a 0%, #2a2a2a 100%);
    }
    
    .game-info {
        padding: 16px;
    }
    
    .game-title {
        font-size: 16px;
        font-weight: 600;
        color: white;
        margin-bottom: 4px;
    }
    
    .game-category {
        font-size: 13px;
        color: #999;
    }
</style>

<!-- Hero Banner -->
<div class="hero-banner">
    <div class="hero-content">
        <h1 class="hero-title">ALL GAME DISC!!!</h1>
        <div class="hero-subtitle">Get 50% Off On Your First Purchase</div>
    </div>
</div>

<!-- SEARCH BAR -->
<!-- SEARCH BAR WITH AUTOCOMPLETE -->
<div style="margin-bottom: 40px; position: relative;">
    <form method="GET" action="{{ route('home') }}" id="searchForm" style="max-width: 600px; margin: 0 auto; position: relative;">
        <div style="display: flex; gap: 12px;">
            <div style="flex: 1; position: relative;">
                <input 
                    type="text" 
                    name="search"
                    id="searchInput"
                    value="{{ request('search') ?? '' }}" 
                    placeholder="🔍 Search games (e.g., Mobile Legends, Free Fire)..."
                    style="width: 100%; padding: 14px 20px; border: 2px solid #3a3a3a; border-radius: 12px; background: #2a2a2a; color: white; font-size: 16px; outline: none;"
                    onfocus="this.style.borderColor='#FF8C00'"
                    onblur="setTimeout(() => this.style.borderColor='#3a3a3a', 200)"
                    autocomplete="off"
                >
                <div id="searchResults" style="position: absolute; top: 100%; left: 0; right: 0; background: #2a2a2a; border: 2px solid #3a3a3a; border-radius: 12px; margin-top: 8px; display: none; max-height: 300px; overflow-y: auto; z-index: 1000;"></div>
            </div>
        </div>
    </form>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
let debounceTimer;

searchInput.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    const query = this.value.trim();
    
    if (query.length < 2) {
        searchResults.style.display = 'none';
        return;
    }
    
    debounceTimer = setTimeout(() => {
        fetch(`/api/search-games?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(games => {
                if (games.length === 0) {
                    searchResults.innerHTML = '<div style="padding: 16px; color: #999; text-align: center;">No games found</div>';
                    searchResults.style.display = 'block';
                    return;
                }
                
                searchResults.innerHTML = games.map(game => `
                    <a href="/topup/${game.id}" style="display: flex; align-items: center; padding: 12px 16px; text-decoration: none; border-bottom: 1px solid #3a3a3a; transition: all 0.3s;" 
                    onmouseover="this.style.background='#3a3a3a'" 
                    onmouseout="this.style.background='transparent'">
                        ${game.logo ? `<img src="/${game.logo}" alt="${game.name}" style="width: 40px; height: 40px; border-radius: 8px; margin-right: 12px; object-fit: cover;">` : ''}
                        <span style="color: white; font-weight: 600;">${game.name}</span>
                    </a>
                `).join('');
                
                searchResults.style.display = 'block';
            });
    }, 300);
});

// Hide results when clicking outside
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});
</script>
    
    @if(request('search'))
        <p style="color: #999; text-align: center; margin-top: 16px; font-size: 14px;">
            Found <strong style="color: #FF8C00;">{{ $games->count() }}</strong> result(s) for "<strong style="color: white;">{{ request('search') }}</strong>"
        </p>
    @endif
</div>

<!-- Flash Sale Section -->
<section>
    <h2 class="section-title">⚡ FLASH SALE</h2>
    <p style="color: #999; margin-bottom: 20px; font-size: 14px;">Penawaran Terbatas! Disc hingga 50%</p>
    
    <div class="flash-sale-grid">
        @if($games->count() >= 3)
            @foreach($games->take(3) as $game)
                <a href="{{ route('topup', $game->id) }}" style="text-decoration: none;">
                    <div class="flash-card">
                        @if($game->logo)
                            <img src="{{ asset($game->logo) }}" alt="{{ $game->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #3a3a3a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; color: #666; font-size: 14px;">
                                {{ $game->name }}
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        @else
            @for($i = 0; $i < 3; $i++)
                <div class="flash-card">
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #3a3a3a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center; color: #666; font-size: 14px;">
                        Coming Soon
                    </div>
                </div>
            @endfor
        @endif
    </div>
</section>

<!-- Trending Section -->
<section>
    <h2 class="section-title">🔥 TRENDING</h2>
    
    <div class="trending-grid">
        @if($games->count() > 0)
            @foreach($games as $game)
                <a href="{{ route('topup', $game->id) }}" style="text-decoration: none;">
                    <div class="game-card">
                        @if($game->logo)
                            <img src="{{ asset($game->logo) }}" alt="{{ $game->name }}" class="game-image">
                        @else
                            <div class="game-image"></div>
                        @endif
                        <div class="game-info">
                            <div class="game-title">{{ $game->name }}</div>
                            <div class="game-category">{{ $game->description ?? 'Popular Game' }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
            
            @for($i = $games->count(); $i < 10; $i++)
                <div class="game-card" style="opacity: 0.5;">
                    <div class="game-image"></div>
                    <div class="game-info">
                        <div class="game-title">Coming Soon</div>
                        <div class="game-category">New Game</div>
                    </div>
                </div>
            @endfor
        @else
            <p style="color: #999;">No games available yet.</p>
        @endif
    </div>
</section>
@endsection