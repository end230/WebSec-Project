@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6 shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Login to WebSecService</h4>
    </div>
    <div class="card-body">
      <form action="{{route('do_login')}}" method="post" autocomplete="on">
      @csrf
      <div class="form-group">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
        @endforeach
      </div>
      <div class="form-group mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="Enter your email" name="email" id="email" autocomplete="email" required>
      </div>
      <div class="form-group mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" placeholder="Enter your password" name="password" id="password" autocomplete="current-password" required>
      </div>
      <div class="form-group mb-4">
        <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
      </div>
    </form>
    
    <div class="text-center my-3">
      <p>OR CONTINUE WITH</p>
      <div class="d-grid gap-2 mb-3">
        <a href="{{ route('login.google') }}" class="btn btn-google position-relative">
          <i class="bi bi-google me-2"></i> Login with Google
          <span class="position-absolute end-0 me-3 top-50 translate-middle-y">→</span>
        </a>
        <a href="{{ route('login.linkedin') }}" class="btn btn-linkedin position-relative">
          <i class="bi bi-linkedin me-2"></i> Login with LinkedIn
          <span class="position-absolute end-0 me-3 top-50 translate-middle-y">→</span>
        </a>
        <a href="{{ route('login.facebook') }}" class="btn btn-facebook position-relative">
          <i class="bi bi-facebook me-2"></i> Login with Facebook
          <span class="position-absolute end-0 me-3 top-50 translate-middle-y">→</span>
        </a>
      </div>
      <p class="mt-3 small text-muted">Don't have an account? <a href="{{ route('register') }}">Register now</a> or use social login</p>
    </div>
    </div>
  </div>
</div>
<style>
.btn-google {
  background-color: #DB4437;
  border-color: #DB4437;
  color: white;
}
.btn-linkedin {
  background-color: #0077B5;
  border-color: #0077B5;
  color: white;
}
.btn-facebook {
  background-color: #1877F2;
  border-color: #1877F2;
  color: white;
}
</style>
@endsection
