<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display wallet dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get or create wallet
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => 0,
                'lifetime_earnings' => 0,
                'lifetime_spent' => 0
            ]
        );
        
        // Get recent transactions
        $recentTransactions = $wallet->transactions()
            ->latest()
            ->take(10)
            ->get();
        
        // Get transaction statistics
        $stats = [
            'total_credited' => $wallet->transactions()
                ->where('type', 'credit')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_debited' => $wallet->transactions()
                ->where('type', 'debit')
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_credits' => $wallet->transactions()
                ->where('type', 'credit')
                ->where('status', 'pending')
                ->sum('amount'),
        ];
        
        return view('client.wallet.index', compact('wallet', 'recentTransactions', 'stats'));
    }

    /**
     * Display all transactions.
     */
    public function transactions(Request $request)
    {
        $user = auth()->user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $user->id]);
        }
        
        $transactions = $wallet->transactions()
            ->latest()
            ->paginate(20);
        
        return view('client.wallet.transactions', compact('wallet', 'transactions'));
    }

    /**
     * Show transaction details.
     */
    public function showTransaction(WalletTransaction $transaction)
    {
        // Check if transaction belongs to user
        if ($transaction->wallet->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('client.wallet.show', compact('transaction'));
    }

    /**
     * Show top-up form.
     */
    public function showTopUp()
    {
        return view('client.wallet.topup');
    }

    /**
     * Process top-up.
     */
    public function topUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10|max:10000',
            'payment_method' => 'required|in:mtn,airtel,zamtel,card'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();
        $wallet = $user->wallet;

        DB::beginTransaction();

        try {
            // Create pending transaction
            $transaction = $wallet->transactions()->create([
                'amount' => $request->amount,
                'type' => 'credit',
                'description' => 'Wallet top-up via ' . strtoupper($request->payment_method),
                'status' => 'pending',
                'reference' => 'TOP-' . strtoupper(uniqid()),
                'metadata' => [
                    'payment_method' => $request->payment_method,
                    'initiated_at' => now()->toDateTimeString()
                ]
            ]);

            DB::commit();

            // Redirect to payment gateway based on method
            switch ($request->payment_method) {
                case 'mtn':
                    return $this->redirectToMTN($transaction);
                case 'airtel':
                    return $this->redirectToAirtel($transaction);
                case 'zamtel':
                    return $this->redirectToZamtel($transaction);
                default:
                    return redirect()->route('client.wallet.index')
                        ->with('error', 'Payment method not supported yet.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process top-up. Please try again.');
        }
    }

    /**
     * Export wallet statement.
     */
    public function exportStatement(Request $request)
    {
        $user = auth()->user();
        $wallet = $user->wallet;
        
        $transactions = $wallet->transactions()
            ->whereBetween('created_at', [
                $request->get('from', now()->startOfMonth()),
                $request->get('to', now()->endOfMonth())
            ])
            ->get();
        
        // Generate CSV
        $filename = 'wallet-statement-' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // Add headers
        fputcsv($handle, ['Date', 'Description', 'Type', 'Amount', 'Status', 'Reference']);
        
        // Add transactions
        foreach ($transactions as $transaction) {
            fputcsv($handle, [
                $transaction->created_at->format('Y-m-d H:i:s'),
                $transaction->description,
                ucfirst($transaction->type),
                $transaction->amount,
                ucfirst($transaction->status),
                $transaction->reference
            ]);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * API Methods
     */
    public function getBalance(Request $request)
    {
        $wallet = $request->user()->wallet;
        
        return response()->json([
            'balance' => $wallet->balance,
            'lifetime_earnings' => $wallet->lifetime_earnings,
            'lifetime_spent' => $wallet->lifetime_spent
        ]);
    }

    public function getTransactions(Request $request)
    {
        $wallet = $request->user()->wallet;
        
        $transactions = $wallet->transactions()
            ->latest()
            ->paginate(20);

        return response()->json($transactions);
    }

    public function getTransaction(WalletTransaction $transaction)
    {
        if ($transaction->wallet->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return response()->json($transaction);
    }

    /**
     * Payment gateway redirects
     */
    private function redirectToMTN($transaction)
    {
        session(['pending_transaction' => $transaction->id]);
        return redirect()->away('https://momodev.mtn.com/v1/payments?reference=' . $transaction->reference);
    }

    private function redirectToAirtel($transaction)
    {
        session(['pending_transaction' => $transaction->id]);
        return redirect()->away('https://openapi.airtel.africa/v1/payments?reference=' . $transaction->reference);
    }

    private function redirectToZamtel($transaction)
    {
        session(['pending_transaction' => $transaction->id]);
        return redirect()->away('https://developer.zamtel.zm/v1/payments?reference=' . $transaction->reference);
    }

    /**
     * Payment callback
     */
    public function paymentCallback(Request $request)
    {
        $transactionId = session('pending_transaction');
        $transaction = WalletTransaction::find($transactionId);
        
        if (!$transaction) {
            return redirect()->route('client.wallet.index')
                ->with('error', 'Transaction not found.');
        }
        
        // For demo purposes, we'll assume payment is successful
        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => 'completed',
                'metadata' => array_merge($transaction->metadata ?? [], [
                    'completed_at' => now()->toDateTimeString()
                ])
            ]);
            
            $transaction->wallet->balance += $transaction->amount;
            $transaction->wallet->lifetime_earnings += $transaction->amount;
            $transaction->wallet->save();
        });
        
        return redirect()->route('client.wallet.index')
            ->with('success', 'Wallet topped up successfully with K' . number_format($transaction->amount, 2));
    }
}