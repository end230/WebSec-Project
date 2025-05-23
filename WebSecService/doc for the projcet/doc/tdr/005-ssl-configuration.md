# TDR 005: SSL Configuration

## Context

WebSecService requires secure communication between clients and the server. Additionally, we want to provide an option for certificate-based authentication for enhanced security.

## Decision

We have implemented:

1. **HTTPS with SSL/TLS**: All HTTP traffic is redirected to HTTPS for secure communication
2. **Client Certificate Authentication**: Optional certificate-based authentication that allows users to log in with their personal certificates

## Technical Implementation

### Server Configuration

#### Apache Virtual Host Configuration

1. **HTTP Virtual Host** (Redirects to HTTPS):
   ```apache
   <VirtualHost *:80>
       ServerName websecservice.localhost.com
       DocumentRoot "/home/shadow-light/web-sec-230104460/WebSecService/public"
       Redirect permanent / https://websecservice.localhost.com/
       <Directory "/home/shadow-light/web-sec-230104460/WebSecService/public">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
       ErrorLog "/opt/lampp/logs/websec-error.log"
       CustomLog "/opt/lampp/logs/websec-access.log" combined
   </VirtualHost>
   ```

2. **HTTPS Virtual Host** (With SSL and Client Certificate Support):
   ```apache
   <VirtualHost *:443>
       ServerName websecservice.localhost.com:443
       DocumentRoot "/home/shadow-light/web-sec-230104460/WebSecService/public"
       ServerAdmin admin@websecservice.localhost.com
       ErrorLog "/opt/lampp/logs/error.log"
       CustomLog "/opt/lampp/logs/access.log" combined

       SSLEngine on
       SSLCertificateFile "/opt/lampp/etc/ssl.crt/websecservice.localhost.com.crt"
       SSLCertificateKeyFile "/opt/lampp/etc/ssl.key/websecservice.localhost.com.key"
       SSLCACertificateFile "/opt/lampp/etc/ssl.crt/ca.crt"
       SSLVerifyClient require
       SSLOptions +ExportCertData

       <Directory "/home/shadow-light/web-sec-230104460/WebSecService/public">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

### Laravel Integration

#### Helper Function

A helper function was created in `app/helpers.php` to extract the email address from the client certificate:

```php
<?php
if (!function_exists('emailFromLoginCertificate')) {
    function emailFromLoginCertificate()
    {
        if (!isset($_SERVER['SSL_CLIENT_CERT'])) return null;

        $clientCertPEM = $_SERVER['SSL_CLIENT_CERT'];
        $certResource = openssl_x509_read($clientCertPEM);
        if (!$certResource) return null;
        $subject = openssl_x509_parse($certResource, false);
        if (!isset($subject['subject']['emailAddress'])) return null;
        return $subject['subject']['emailAddress'];
    }
}
```

This helper is autoloaded in `composer.json`:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/"
    },
    "files": [
        "app/helpers.php"
    ]
}
```

#### Route Integration

The welcome route was modified to handle certificate-based authentication:

```php
Route::get('/', function () {
    $email = emailFromLoginCertificate();
    if ($email && !auth()->user()) {
        $user = User::where('email', $email)->first();
        if ($user) Auth::login($user);
    }
    return view('welcome');
});
```

### Local Development Setup

1. **Hosts File Configuration**:
   Add to `/etc/hosts`:
   ```
   127.0.0.1 websecservice.localhost.com
   ```

2. **Certificate Generation**:
   - Server certificate: Generated for `websecservice.localhost.com`
   - CA certificate: Generated for signing client certificates
   - Client certificates: Generated for individual users with email embedded

3. **Client Certificate Installation**:
   Import the `.pfx` format certificate into the browser's certificate store.

### Security Considerations

1. **Certificate Validation**: The server validates client certificates against the trusted CA.
2. **User Mapping**: Client certificates are mapped to users via the email address embedded in the certificate.
3. **Automatic Login**: Users with valid certificates are automatically logged in without password prompts.

## Benefits

1. **Enhanced Security**: Two-factor authentication combining something you have (certificate) with standard authentication.
2. **Passwordless Authentication**: Users can authenticate with certificates without entering passwords.
3. **Phishing Resistance**: Certificate-based authentication is resistant to phishing attacks.

## Alternatives Considered

1. **Token-based Authentication**: While simpler to implement, lacks the security benefits of client certificates.
2. **Biometric Authentication**: Requires specialized hardware and complex implementation.

## References

- [Apache SSL Documentation](https://httpd.apache.org/docs/2.4/ssl/ssl_howto.html)
- [PHP OpenSSL Functions](https://www.php.net/manual/en/ref.openssl.php)
