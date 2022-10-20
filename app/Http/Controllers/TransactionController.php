<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
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
        if (!$account) {
            redirect(route('home'));
        } else {
            $search = request('search');
            $order = request('order', 'asc');
            if (!in_array($order, ['asc', 'desc'])) {
                $order = 'asc';
            }
            $transactions = $account->transactions()->orderBy('created_at', $order);
            if (!empty($search)) {
                $transactions = $transactions->whereFullText(
                    'description',
                    $search
                );
            }
            $transactions = $transactions->paginate(5);
            return view(
                'transactions.index',
                compact('transactions', 'search', 'order')
            );
        }
    }
}
