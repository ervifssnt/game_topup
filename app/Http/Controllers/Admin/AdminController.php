<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TopupOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Dashboard
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_transactions' => Transaction::count(),
            'total_revenue' => Transaction::where('status', 'paid')->sum('price'),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
        ];
        
        $recent_transactions = Transaction::with('user', 'topupOption.game')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recent_transactions'));
    }
    
    // Games Management
    public function games()
    {
        $games = Game::withCount('topupOptions')->get();
        return view('admin.games.index', compact('games'));
    }
    
    public function createGame()
    {
        return view('admin.games.create');
    }
    
    public function storeGame(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
        ]);
        
        Game::create($request->all());
        
        return redirect()->route('admin.games')->with('success', 'Game created successfully!');
    }
    
    public function editGame($id)
    {
        $game = Game::findOrFail($id);
        return view('admin.games.edit', compact('game'));
    }
    
    public function updateGame(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
        ]);
        
        $game = Game::findOrFail($id);
        $game->update($request->all());
        
        return redirect()->route('admin.games')->with('success', 'Game updated successfully!');
    }
    
    public function deleteGame($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();
        
        return redirect()->route('admin.games')->with('success', 'Game deleted successfully!');
    }
    
    // Topup Options Management
    public function topupOptions()
    {
        $options = TopupOption::with('game')->orderBy('game_id')->get();
        $games = Game::all();
        return view('admin.topup-options.index', compact('options', 'games'));
    }
    
    public function createTopupOption()
    {
        $games = Game::all();
        return view('admin.topup-options.create', compact('games'));
    }
    
    public function storeTopupOption(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'coins' => 'required|integer',
            'amount' => 'required|string',
            'price' => 'required|numeric',
        ]);
        
        TopupOption::create($request->all());
        
        return redirect()->route('admin.topup-options')->with('success', 'Topup option created successfully!');
    }
    
    public function editTopupOption($id)
    {
        $option = TopupOption::findOrFail($id);
        $games = Game::all();
        return view('admin.topup-options.edit', compact('option', 'games'));
    }
    
    public function updateTopupOption(Request $request, $id)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'coins' => 'required|integer',
            'amount' => 'required|string',
            'price' => 'required|numeric',
        ]);
        
        $option = TopupOption::findOrFail($id);
        $option->update($request->all());
        
        return redirect()->route('admin.topup-options')->with('success', 'Topup option updated successfully!');
    }
    
    public function deleteTopupOption($id)
    {
        $option = TopupOption::findOrFail($id);
        $option->delete();
        
        return redirect()->route('admin.topup-options')->with('success', 'Topup option deleted successfully!');
    }
    
    // Users Management
    public function users()
    {
        $users = User::withCount('transactions')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }
    
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string',
            'balance' => 'required|numeric',
            'is_admin' => 'boolean',
        ]);
        
        $user = User::findOrFail($id);
        $user->update($request->all());
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }
    
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->is_admin) {
            return back()->with('error', 'Cannot delete admin user!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
    
    // Transactions Management
    public function transactions()
    {
        $transactions = Transaction::with('user', 'topupOption.game')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.transactions.index', compact('transactions'));
    }
}