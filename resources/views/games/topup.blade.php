@extends('layouts.main')

@section('title', 'Top Up - ' . $game->name)

@section('styles')
<style>
    .topup-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Game Header */
    .game-header {
        background: #2a2a2a;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 30px;
        border: 1px solid #3a3a3a;
    }
    
    .game-image {
        width: 150px;
        height: 150px;
        border-radius: 16px;
        object-fit: cover;
        background: linear-gradient(135deg, #3a3a3a 0%, #2a2a2a 100%);
    }
    
    .game-info h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        color: white;
    }
    
    .game-info p {
        color: #999;
        font-size: 15px;
    }
    
    /* Form Section */
    .form-section {
        background: #2a2a2a;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 20px;
        border: 1px solid #3a3a3a;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 20px;
        color: white;
    }
    
    .input-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .input-group {
        margin-bottom: 0;
    }
    
    .input-group label {
        display: block;
        font-size: 14px;
        margin-bottom: 8px;
        color: #ccc;
    }
    
    .input-group input {
        width: 100%;
        padding: 12px 16px;
        background: #1a1a1a;
        border: 1px solid #3a3a3a;
        border-radius: 8px;
        color: white;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .input-group input:focus {
        outline: none;
        border-color: #FF8C00;
    }
    
    /* Item Selection Grid */
    .items-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }
    
    .item-card {
        background: #3a3a3a;
        border: 2px solid transparent;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    
    .item-card:hover {
        background: #404040;
        border-color: #FF8C00;
    }
    
    .item-card.selected {
        border-color: #FF8C00;
        background: #404040;
    }
    
    .item-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .item-name {
        font-size: 14px;
        font-weight: 600;
        color: white;
        margin-bottom: 8px;
    }
    
    .item-price {
        font-size: 15px;
        font-weight: 700;
        color: #FF8C00;
    }
    
    /* Bottom Section */
    .bottom-section {
        background: #2a2a2a;
        border-radius: 12px;
        padding: 30px;
        border: 1px solid #3a3a3a;
    }
    
    .selected-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #3a3a3a;
    }
    
    .summary-left h3 {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin-bottom: 4px;
    }
    
    .summary-left p {
        color: #999;
        font-size: 14px;
    }
    
    .summary-right {
        font-size: 24px;
        font-weight: 700;
        color: white;
    }
    
    .purchase-controls {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .quantity-control {
        display: flex;
        flex-direction: column;
    }
    
    .quantity-control label {
        font-size: 14px;
        margin-bottom: 8px;
        color: #ccc;
    }
    
    .quantity-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .qty-btn {
        width: 40px;
        height: 40px;
        background: #FF8C00;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 20px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .qty-btn:hover {
        background: #ff9d1f;
    }
    
    .qty-btn:disabled {
        background: #3a3a3a;
        color: #666;
        cursor: not-allowed;
    }
    
    .qty-input {
        width: 60px;
        text-align: center;
        padding: 10px;
        background: #1a1a1a;
        border: 1px solid #3a3a3a;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 600;
    }
    
    .promo-input {
        width: 100%;
        padding: 12px 16px;
        background: #1a1a1a;
        border: 1px solid #3a3a3a;
        border-radius: 8px;
        color: white;
        font-size: 14px;
    }
    
    .promo-input:focus {
        outline: none;
        border-color: #FF8C00;
    }
    
    .btn-buy-now {
        width: 100%;
        padding: 16px;
        background: #FF8C00;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-buy-now:hover {
        background: #ff9d1f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 140, 0, 0.4);
    }
    
    .btn-buy-now:disabled {
        background: #3a3a3a;
        color: #666;
        cursor: not-allowed;
        transform: none;
    }
    
    .error-message {
        background: #4a1a1a;
        border: 1px solid #d32f2f;
        color: #ef5350;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    @media (max-width: 768px) {
        .game-header {
            flex-direction: column;
            text-align: center;
        }
        
        .input-grid {
            grid-template-columns: 1fr;
        }
        
        .items-grid {
            grid-template-columns: 1fr;
        }
        
        .purchase-controls {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="topup-container">
    <!-- Game Header -->
    <div class="game-header">
        @if($game->logo)
            <img src="{{ asset($game->logo) }}" alt="{{ $game->name }}" class="game-image">
        @else
            <div class="game-image"></div>
        @endif
        <div class="game-info">
            <h1>{{ $game->name }}</h1>
            <p>{{ $game->description ?? 'Moonton' }}</p>
        </div>
    </div>
    
    @if($errors->any())
        <div class="error-message">
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif
    
    <form id="topupForm" method="POST" action="{{ route('topup.store') }}">
        @csrf
        
        <!-- Account Details -->
        <div class="form-section">
            <h2 class="section-title">Enter account details</h2>
            <div class="input-grid">
                <div class="input-group">
                    <label>User ID</label>
                    <input type="text" 
                           name="account_id" 
                           id="userId"
                           value="{{ old('account_id') }}"
                           placeholder="Enter your User ID"
                           required>
                </div>
                <div class="input-group">
                    <label>Zone ID</label>
                    <input type="text" 
                           name="zone_id" 
                           id="zoneId"
                           value="{{ old('zone_id') }}"
                           placeholder="Enter your Zone ID">
                </div>
            </div>
        </div>
        
        <!-- Flash Sale Items -->
        @php
            $flashSaleItems = $game->topupOptions->take(1);
            $regularItems = $game->topupOptions->skip(1);
        @endphp
        
        @if($flashSaleItems->count() > 0)
        <div class="form-section">
            <h2 class="section-title">Flash Sale</h2>
            <div class="items-grid">
                @foreach($flashSaleItems as $option)
                    <label class="item-card" data-price="{{ $option->price }}" data-name="{{ $option->amount ?? $option->coins . ' Coins' }}">
                        <input type="radio" 
                               name="topup_option_id" 
                               value="{{ $option->id }}"
                               {{ old('topup_option_id') == $option->id ? 'checked' : '' }}>
                        <div class="item-name">{{ $option->amount ?? $option->coins . ' Diamonds' }}</div>
                        <div class="item-price">Rp {{ number_format($option->price, 0, ',', '.') }}</div>
                    </label>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Choose Item (Diamonds) -->
        <div class="form-section">
            <h2 class="section-title">Select Amount</h2>
            <div class="items-grid">
                @foreach($regularItems as $option)
                    <label class="item-card" data-price="{{ $option->price }}" data-name="{{ $option->amount ?? $option->coins . ' Coins' }}">
                        <input type="radio" 
                               name="topup_option_id" 
                               value="{{ $option->id }}"
                               {{ old('topup_option_id') == $option->id ? 'checked' : '' }}
                               required>
                        <div class="item-name">{{ $option->amount ?? $option->coins . ' Diamonds' }}</div>
                        <div class="item-price">Rp {{ number_format($option->price, 0, ',', '.') }}</div>
                    </label>
                @endforeach
            </div>
        </div>
        
        <!-- Bottom Section: Summary + Purchase Controls -->
        <div class="bottom-section">
            <div class="selected-summary">
                <div class="summary-left">
                    <h3 id="selectedItemName">Rp 0</h3>
                    <p id="selectedItemDesc">Please select an item</p>
                </div>
                <div class="summary-right" id="totalPrice">Rp 0</div>
            </div>
            
            <div class="purchase-controls">
                <div class="quantity-control">
                    <label>Purchase Quantity</label>
                    <div class="quantity-wrapper">
                        <button type="button" class="qty-btn" id="decreaseQty">-</button>
                        <input type="number" 
                               name="quantity" 
                               id="quantity" 
                               class="qty-input" 
                               value="1" 
                               min="1" 
                               max="99" 
                               readonly>
                        <button type="button" class="qty-btn" id="increaseQty">+</button>
                    </div>
                </div>
                
                <div class="quantity-control">
                    <label>Have a Promo Code?</label>
                    <input type="text" 
                           name="promo_code" 
                           class="promo-input" 
                           placeholder="Enter promo code">
                </div>
            </div>
            
            <button type="submit" class="btn-buy-now" id="buyNowBtn" disabled>Buy Now</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('topupForm');
    const itemCards = document.querySelectorAll('.item-card');
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const buyNowBtn = document.getElementById('buyNowBtn');
    const selectedItemName = document.getElementById('selectedItemName');
    const selectedItemDesc = document.getElementById('selectedItemDesc');
    const totalPrice = document.getElementById('totalPrice');
    
    let selectedPrice = 0;
    let selectedName = '';
    
    // Handle item selection
    itemCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            itemCards.forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            this.classList.add('selected');
            
            // Get price and name
            selectedPrice = parseFloat(this.dataset.price);
            selectedName = this.dataset.name;
            
            // Update display
            updateSummary();
            buyNowBtn.disabled = false;
        });
        
        // Check if already selected (on page load)
        const radio = card.querySelector('input[type="radio"]');
        if (radio && radio.checked) {
            card.classList.add('selected');
            selectedPrice = parseFloat(card.dataset.price);
            selectedName = card.dataset.name;
            updateSummary();
            buyNowBtn.disabled = false;
        }
    });
    
    // Quantity controls
    decreaseBtn.addEventListener('click', function() {
        let qty = parseInt(quantityInput.value);
        if (qty > 1) {
            quantityInput.value = qty - 1;
            updateSummary();
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        let qty = parseInt(quantityInput.value);
        if (qty < 99) {
            quantityInput.value = qty + 1;
            updateSummary();
        }
    });
    
    function updateSummary() {
        const qty = parseInt(quantityInput.value);
        const total = selectedPrice * qty;
        
        selectedItemName.textContent = 'Rp ' + formatNumber(total);
        selectedItemDesc.textContent = selectedName + (qty > 1 ? ' x ' + qty : '');
        totalPrice.textContent = 'Rp ' + formatNumber(total);
    }
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});
</script>
@endsection