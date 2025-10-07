<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TopupOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTransactionRequest;

class TransactionController extends Controller
{
    // Create new transaction
    public function store(StoreTransactionRequest $request)
    {
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
            'promo_code' => 'nullable|string|max:50',
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
            $finalPrice = $transaction->price;
            $discount = 0;
            $promoId = null;

            // Check promo code FIRST
            if ($request->filled('promo_code')) {
                $promo = DB::table('promo_codes')
                    ->where('code', strtolower(trim($request->promo_code)))
                    ->where('is_active', true)
                    ->first();

                if (!$promo) {
                    throw new \Exception('Invalid promo code.');
                }
                
                // Check if user already used this promo
                $alreadyUsed = DB::table('promo_code_usage')
                    ->where('user_id', Auth::id())
                    ->where('promo_code_id', $promo->id)
                    ->exists();
                
                if ($alreadyUsed) {
                    throw new \Exception('You have already used this promo code.');
                }
                
                $discount = ($transaction->price * $promo->discount_percent) / 100;
                $finalPrice = $transaction->price - $discount;
                $promoId = $promo->id;
            }

            // Check balance AFTER applying discount
            if (!$user->hasEnoughBalance($finalPrice)) {
                throw new \Exception('Not enough balance. Total after discount: Rp ' . number_format($finalPrice, 0, ',', '.'));
            }

            // Deduct balance
            $user->deductBalance($finalPrice);

            // Mark as paid
            $transaction->markAsPaid();

            // Log promo usage
            if ($promoId) {
                DB::table('promo_code_usage')->insert([
                    'user_id' => Auth::id(),
                    'promo_code_id' => $promoId,
                    'transaction_id' => $transaction->id,
                    'used_at' => now(),
                ]);
            }

            DB::commit();

            $message = "Checkout successful!";
            if ($discount > 0) {
                $message .= " Promo applied: -Rp " . number_format($discount, 0, ',', '.') . ".";
            }
            $message .= " Your new balance is Rp " . number_format($user->balance, 0, ',', '.');

            return redirect()->route('checkout', $transaction->id)->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}