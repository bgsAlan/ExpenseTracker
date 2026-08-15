<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Service\TransactionService;
use Exception;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthorize"
            ], 401);
        }

        $transactions = Transaction::where('user_id', $user->id)->get();
        return response()->json([
            "status" => "success",
            "data"  => $transactions
        ]);
    }
    public function __construct(protected TransactionService $transactionService) {}
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                "status"  => "error",
                "message" => "Unauthorized"
            ], 401);
        }

        $validated = $request->validated();
        $validated['user_id'] = $user->id;

        try {
            $transaction = $this->transactionService->createTransaction($validated);
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dicatat',
                'data'    => $transaction
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        if ($transaction->user_id != auth('api')->id()) {
            return response()->json([
                "status"  => "error",
                "message" => "Unauthorized"
            ], 401);
        }
        
        return response()->json([
            'status' => "success",
            'message' => 'Transaksi berhasil dicatat',
            'data'    => $transaction
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request,Transaction $transaction)
    {
        if ($transaction->user_id != auth('api')->id()) {
            return response()->json([
                "status"  => "error",
                "message" => "Unauthorized"
            ], 401);
        }

        $validated = $request->validated();
        $validated['user_id'] = $transaction->user_id;

        try {
            $transaction = $this->transactionService->updateTransaction($validated);
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diupdate',
                'data'    => $transaction
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id != auth('api')->id()){
            return response()->json([
                "status" => "error",
                "message" => "credential not valid"
            ], 401);
        }

        $transaction->delete();

        return response()->json([
            "status" => "success",
            "message" => "transaction berhasil di hapus"
        ]);
    }
}
