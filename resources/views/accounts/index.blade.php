@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if ($account)
                    <div class="card">
                        <div class="card-header">{{ __('Account information') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Login</div>
                                        {{ $account->user->login }}
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Balance</div>
                                        {{ $account->balance }}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        @if (count($transactions) > 0)
                            <div class="card-header">{{ __('Last :count transactions', ['count' => count($transactions)]) }}</div>
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
                        @else
                            <div class="card-header">{{ __('There is no transactions related to current account') }}</div>
                        @endif
                    </div>
            </div>
            @else
                <div class="card">
                    <div class="card-header">{{ __('Current user hasn\'t balance') }}</div>
                </div>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            setInterval(function () {
                $.ajax({
                    type:'GET',
                    url:"{{ route('home') }}",
                    success:function(data){
                        $('#content').html(data);
                    }
                });
            }, 5000);
        }, false);
    </script>
@endsection
