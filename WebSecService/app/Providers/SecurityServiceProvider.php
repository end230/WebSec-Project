<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register common password validation rules
        $this->app->singleton('password.rules', function ($app) {
            return [
                'required',
                'confirmed',
                Password::min(12)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols()
                    ->uncompromised(3)
            ];
        });
        
        // Add custom validation rules for security
        Validator::extend('no_script_tags', function ($attribute, $value, $parameters, $validator) {
            return !preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $value);
        }, 'The :attribute may not contain script tags.');

        // Add safe_html validation rule
        Validator::extend('safe_html', function ($attribute, $value, $parameters, $validator) {
            // Basic implementation - remove or sanitize potentially harmful HTML
            $disallowedTags = ['script', 'iframe', 'object', 'embed', 'form', 'input', 'button'];
            foreach ($disallowedTags as $tag) {
                if (stripos($value, '<' . $tag) !== false) {
                    return false;
                }
            }
            return true;
        }, 'The :attribute contains potentially unsafe HTML.');
    }
}
