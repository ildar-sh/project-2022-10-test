<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application main page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $account = auth()->user()->account()->first();
        $transactions = $account?->transactions()->latest()->limit(5)->get();
        return view('accounts.index', [
            'account' => $account,
            'transactions' => $transactions,
        ]);
    }
}
