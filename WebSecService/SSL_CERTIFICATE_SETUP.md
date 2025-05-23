# SSL Client Certificate Configuration Guide

This guide explains how to configure your web server to request SSL client certificates for automatic authentication.

## Prerequisites

1. You need a Certificate Authority (CA) certificate
2. Client certificates issued by that CA
3. Web server configured for HTTPS

## Apache Configuration

Add the following to your Apache virtual host configuration:

```apache
<VirtualHost *:443>
    ServerName your-domain.com
    DocumentRoot /path/to/your/laravel/public
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/server.crt
    SSLCertificateKeyFile /path/to/server.key
    
    # Client Certificate Configuration
    SSLCACertificateFile /path/to/ca.crt
    SSLVerifyClient optional
    SSLVerifyDepth 1
    SSLOptions +StdEnvVars +ExportCertData
    
    # Pass certificate info to PHP
    <Location />
        SSLOptions +StdEnvVars +ExportCertData
    </Location>
    
    # PHP Configuration
    <FilesMatch "\.php$">
        SetHandler application/x-httpd-php
    </FilesMatch>
</VirtualHost>
```

## Nginx Configuration

For Nginx, add the following to your server block:

```nginx
server {
    listen 443 ssl;
    server_name your-domain.com;
    
    # SSL Configuration
    ssl_certificate /path/to/server.crt;
    ssl_certificate_key /path/to/server.key;
    
    # Client Certificate Configuration
    ssl_client_certificate /path/to/ca.crt;
    ssl_verify_client optional;
    ssl_verify_depth 1;
    
    # Pass certificate variables to FastCGI
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SSL_CLIENT_VERIFY $ssl_client_verify;
        fastcgi_param SSL_CLIENT_S_DN $ssl_client_s_dn;
        fastcgi_param SSL_CLIENT_S_DN_CN $ssl_client_s_dn_cn;
        fastcgi_param SSL_CLIENT_S_DN_Email $ssl_client_s_dn_email;
        fastcgi_param SSL_CLIENT_CERT $ssl_client_cert;
        fastcgi_param SSL_CLIENT_M_SERIAL $ssl_client_serial;
        include fastcgi_params;
    }
}
```

## How It Works

1. **Certificate Request**: When a user visits your site, the web server requests a client certificate
2. **User Selection**: The browser shows a dialog asking the user to select a certificate
3. **Automatic Login**: If the certificate matches a user in the database, they are automatically logged in
4. **Fallback**: If no certificate or no matching user, normal login is required

## User Setup

1. **Admin Configuration**: Admins can pre-configure certificate information for users via the "Manage SSL Certificate" link in user profiles
2. **Automatic Discovery**: When a user logs in with a certificate for the first time, their certificate information is automatically stored
3. **Future Logins**: Subsequent visits with the same certificate will automatically log them in

## Certificate Information Stored

- **Certificate CN (Common Name)**: The name field from the certificate
- **Certificate Serial Number**: Unique identifier for the certificate
- **Certificate DN (Distinguished Name)**: Full certificate subject information
- **Last Certificate Login**: Timestamp of last automatic login

## Security Considerations

1. **Certificate Validation**: Only certificates signed by your trusted CA are accepted
2. **Revocation**: Implement certificate revocation checking if needed
3. **Backup Authentication**: Normal password login remains available
4. **Audit Logging**: All certificate logins are logged for security auditing

## Testing

1. Visit `/cert-info` to see what certificate information is being passed from your web server
2. Check the application logs for SSL authentication attempts
3. Use the "Manage SSL Certificate" page to configure test certificates

## Troubleshooting

### No Certificate Information
- Check web server SSL configuration
- Verify CA certificate is properly configured
- Ensure SSL environment variables are being passed to PHP

### Certificate Not Recognized
- Check if user exists in database with matching email
- Verify certificate CN or serial number matches database
- Check application logs for authentication attempts

### Browser Issues
- Ensure client certificate is installed in browser
- Check certificate is not expired
- Verify certificate chain is complete
