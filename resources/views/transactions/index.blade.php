@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <form method="GET">
                <div class="row g-3">
                    <div class="col">
                        <input type="text" class="form-control" id="search" name="search"
                               placeholder="Search transaction by description"
                               value="{{ $search }}"
                        >
                    </div>
                    <div class="col">
                        <select class="form-select" aria-label="Order select" name="order">
                            <option {{ $order === 'acs' ? 'selected' : '' }} value="asc">From old to new
                            </option>
                            <option {{ $order === 'desc' ? 'selected' : '' }} value="desc">From new to old
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Description</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $transactions->links() }}
        </div>
    </div>
@endsection
