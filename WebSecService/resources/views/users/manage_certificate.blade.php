@extends('layouts.master')
@section('title', 'Manage SSL Certificate')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Manage SSL Certificate for {{ $user->name }}</h2>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.certificate.save', $user) }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="certificate_cn" class="col-md-4 col-form-label text-md-right">Certificate Common Name (CN)</label>
                            <div class="col-md-6">
                                <input id="certificate_cn" type="text" class="form-control" 
                                    name="certificate_cn" value="{{ old('certificate_cn', $user->certificate_cn) }}" 
                                    placeholder="e.g., John Doe">
                                <small class="form-text text-muted">The CN field from the SSL certificate</small>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="certificate_serial" class="col-md-4 col-form-label text-md-right">Certificate Serial Number</label>
                            <div class="col-md-6">
                                <input id="certificate_serial" type="text" class="form-control" 
                                    name="certificate_serial" value="{{ old('certificate_serial', $user->certificate_serial) }}" 
                                    placeholder="e.g., 1234567890ABCDEF">
                                <small class="form-text text-muted">The serial number of the SSL certificate</small>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="certificate_dn" class="col-md-4 col-form-label text-md-right">Certificate Distinguished Name (DN)</label>
                            <div class="col-md-6">
                                <textarea id="certificate_dn" class="form-control" name="certificate_dn" rows="3" 
                                    placeholder="e.g., CN=John Doe,OU=Users,O=Company,C=US">{{ old('certificate_dn', $user->certificate_dn) }}</textarea>
                                <small class="form-text text-muted">The full distinguished name from the SSL certificate</small>
                            </div>
                        </div>

                        @if($user->last_certificate_login)
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Last Certificate Login</label>
                            <div class="col-md-6">
                                <p class="form-control-plaintext">{{ $user->last_certificate_login->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Certificate Info
                                </button>
                                <a href="{{ route('profile', $user) }}" class="btn btn-secondary">
                                    Back to Profile
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Certificate Debug Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Current SSL Certificate Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Note:</strong> This shows the current SSL certificate presented by your browser (if any):</p>
                    <div id="cert-info" class="bg-light p-3 rounded">
                        <em>Loading certificate information...</em>
                    </div>
                    <button class="btn btn-info mt-2" onclick="loadCertInfo()">Refresh Certificate Info</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadCertInfo() {
    fetch('{{ route("cert.info") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cert-info').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        })
        .catch(error => {
            document.getElementById('cert-info').innerHTML = '<span class="text-danger">Error loading certificate info: ' + error + '</span>';
        });
}

// Load certificate info when page loads
document.addEventListener('DOMContentLoaded', loadCertInfo);
</script>
@endsection
