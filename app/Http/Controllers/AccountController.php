<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        //ambil data user
        $user = auth('api')->user();
        //kalo user tidak ditemukan
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token tidak valid atau sudah kadaluarsa.'
            ], 401);
        }
        //mengambil accound dari user
        $accounts = Account::where('user_id',$user->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $accounts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        //create account
        //ambil data user
        $user = auth('api')->user();
        //kalo user tidak ditemukan
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token tidak valid atau sudah kadaluarsa.'
            ], 401);
        }

        $validated = $request->validated();

        //create
        $account = Account::create([
            'user_id' => $user->id,
            'name'    => $validated['name'],
            'balance' => $validated['balance'],
        ]);

        //return response
        return response()->json([
            "message" => "account berhasil dibuat",
            "data"  => $account
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //cek apakah account benar benar milik user
        if($account->user_id != auth('api')->id()){
            return response()->json(
                [
                    'status'  => 'error',
                    'message' => 'Kamu tidak berhak melihat account ini.'
                ],404);
        }
        return response()->json([
            'status' => 'success',
            'data'   => $account
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        //cek apakah account benar benar milik user
        if($account->user_id != auth('api')->id()){
            return response()->json(
                [
                    'status'  => 'error',
                    'message' => 'Kamu tidak berhak melihat account ini.'
                ],404);
        }
        //ambill request dari user dan validasi
        $validated = $request->validated();

        //update 
        $account->update($validated);//model binding account update(validasi)
        //return response
        return response()->json([
            'message' => "Update berhasil",
            'data' => $account
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //cek apakah account benar benar milik user
        if ($account->user_id != auth('api')->id()) {
            return response()->json(
                [
                    'status'  => 'error',
                    'message' => 'Kamu tidak berhak melihat account ini.'
                ],404);
        }
        $account->delete();
        //return
        return response()->json([
            'status' => 'success',
            'message' => 'account berhasil di hapus'
        ]);
    }
}
