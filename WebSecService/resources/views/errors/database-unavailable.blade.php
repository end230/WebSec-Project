@extends('layouts.master')

@section('title', 'Database Unavailable')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="mb-0">Database Connection Error</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        We're currently experiencing technical difficulties with our database connection.
                    </div>

                    <h4>What happened?</h4>
                    <p>
                        The application cannot connect to the database server. This could be due to:
                    </p>
                    <ul>
                        <li>The database service is not running</li>
                        <li>Connection information in the configuration is incorrect</li>
                        <li>Network connectivity issues between the application and database</li>
                    </ul>

                    <h4>What can you do?</h4>
                    <p>Here are steps to resolve this issue:</p>

                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>For Administrators</strong>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li>Check if the MySQL service is running:
                                    <code>sudo systemctl status mysql</code> or 
                                    <code>sudo service mysql status</code>
                                </li>
                                <li>If it's not running, start it:
                                    <code>sudo systemctl start mysql</code> or
                                    <code>sudo service mysql start</code>
                                </li>
                                <li>Verify your database connection settings in the <code>.env</code> file:
                                    <pre class="bg-light p-2">
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=your_password</pre>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <p>
                        <a href="/" class="btn btn-primary">Try Again</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
