<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TopupOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Create new transaction
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|string',
            'topup_option_id' => 'required|exists:topup_options,id',
        ]);

        DB::beginTransaction();

        try {
            $topupOption = TopupOption::findOrFail($request->topup_option_id);

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'account_id' => $request->account_id,
                'topup_option_id' => $topupOption->id,
                'coins' => $topupOption->coins,
                'price' => $topupOption->price,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('checkout', $transaction->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process top-up. Please try again.');
        }
    }

    // Show checkout page
    public function checkout($id)
    {
        $transaction = Transaction::with('topupOption.game')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $user = Auth::user();

        return view('transactions.checkout', compact('transaction', 'user'));
    }

    // Process checkout
    public function processCheckout(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
        ]);

        DB::beginTransaction();

        try {
            // Lock rows for update
            $transaction = Transaction::where('id', $request->transaction_id)
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaction->status !== 'pending') {
                throw new \Exception('Transaction already processed.');
            }

            $user = Auth::user();

            if (!$user->hasEnoughBalance($transaction->price)) {
                throw new \Exception('Not enough balance.');
            }

            // Deduct balance
            $user->deductBalance($transaction->price);

            // Mark as paid
            $transaction->markAsPaid();

            DB::commit();

            return redirect()->route('checkout', $transaction->id)
                ->with('success', "Checkout successful! Your new balance is Rp " . number_format($user->balance, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}