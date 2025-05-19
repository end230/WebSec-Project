@extends('layouts.master')
@section('title', 'Add Credits')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Add Credits to {{ $user->name }}'s Account</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.credits.add', $user) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Current Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" value="{{ number_format($user->credits, 2) }}" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount to Add</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1" required>
                            </div>
                            <div class="form-text">Enter the amount of credits you want to add to this account.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.customers') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Credits</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
