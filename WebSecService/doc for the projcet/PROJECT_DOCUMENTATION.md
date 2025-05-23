# WebSecService Project Documentation

## Table of Contents
- [Project Overview](#project-overview)
- [System Architecture](#system-architecture)
  - [Architecture Decisions (ADR)](#architecture-decisions-adr)
  - [Technical Decisions (TDR)](#technical-decisions-tdr)
- [Database Design](#database-design)
  - [Technical Decisions (TDR)](#database-technical-decisions-tdr)
- [Authentication & Authorization](#authentication--authorization)
  - [Technical Decisions (TDR)](#auth-technical-decisions-tdr)
- [Backend Implementation](#backend-implementation)
  - [Controllers](#controllers)
  - [Models](#models)
  - [Middleware](#middleware)
- [Frontend Implementation](#frontend-implementation)
  - [Key Views](#key-views)
  - [UI Enhancements](#ui-enhancements)
- [Security Considerations](#security-considerations)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)
- [Deployment and Maintenance](#deployment-and-maintenance)
- [Route Configuration Best Practices](#route-configuration-best-practices)

## Project Overview

WebSecService is a secure Laravel-based e-commerce platform with robust security measures. The application implements role-based access control with three primary user types: Admin, Employee, and Customer. The platform allows customers to browse products, make purchases using a credit system, and manage their accounts with multiple authentication options.

### Key Features
- Multi-provider authentication (Email, Google, LinkedIn, Facebook)
- Role-based authorization
- Product catalog and management
- Credit system for purchases
- Admin dashboard for user and product management
- Secure password handling with strong validation rules
- Data validation and sanitization
- Enhanced UI with responsive design
- Comprehensive security measures (See [Security Considerations](#security-considerations))

## System Architecture

The application follows the Model-View-Controller (MVC) architecture pattern provided by the Laravel framework:

- **Models**: Represent database tables and relationships (User, Product, Order, etc.)
- **Views**: Blade templates rendering the frontend of the application
- **Controllers**: Handle business logic and user requests
- **Middleware**: Process HTTP requests and enforce security measures

### Technology Stack
- **Backend**: PHP 8.x with Laravel 10.x
- **Frontend**: Blade templates with Bootstrap CSS and custom styling
- **Database**: MySQL/MariaDB
- **Caching**: Redis
- **Queue System**: Laravel's built-in queue system
- **Authentication**: Multi-provider OAuth (Google, LinkedIn, Facebook) via Laravel Socialite
- **Authorization**: Spatie Laravel Permission

### Architecture Decisions (ADR)

Key architecture decisions are documented in the `doc/adr` directory.

- **[ADR 001: Initial Architecture Choice](./doc/adr/001-initial-architecture.md)**: Documents the selection of Laravel, MySQL, Bootstrap, and Redis.

### Technical Decisions (TDR)

Key technical implementation decisions are documented in the `doc/tdr` directory.

- **[TDR 002: Frontend Framework Choice](./doc/tdr/002-frontend-framework-choice.md)**: Documents the choice of Bootstrap.
- **[TDR 004: Caching Choice](./doc/tdr/004-caching-choice.md)**: Documents the choice of Redis.
- **[TDR 005: SSL Configuration](./doc/tdr/005-ssl-configuration.md)**: Documents the SSL and client certificate authentication setup.

## Database Design

The database follows a normalized structure with the following key entities:

1.  **Users**: Stores user account information, credits, and role assignments.
2.  **Products**: Stores product information (name, description, price, stock).
3.  **Orders**: Records customer purchases (user, status, total).
4.  **Order Items**: Links orders to products (quantity, price).
5.  **Roles & Permissions**: Managed by Spatie's permission package (`roles`, `permissions`, `model_has_roles`, etc.).
6.  **Cache, Jobs, Sessions**: Standard Laravel tables for caching, background jobs, and session management.
7.  **Social Logins**: *Note: Socialite typically doesn't require dedicated columns by default, linking happens via the `users` table email or a dedicated provider ID column if added.*

*(Refer to migration files in `database/migrations` for detailed schema)*

### Database Technical Decisions (TDR)

- **[TDR 003: Database Choice](./doc/tdr/003-database-choice.md)**: Documents the choice of MySQL/MariaDB.

## Authentication & Authorization

The application implements a comprehensive authentication and authorization system:

1.  **Multi-provider Authentication**:
    *   Traditional email/password login (Laravel built-in).
    *   OAuth integration with Google, LinkedIn, Facebook using Laravel Socialite.
    *   SSL client certificate authentication for secure and passwordless login.
2.  **Role-based Access Control (RBAC)**:
    *   Managed using Spatie Laravel Permission.
    *   Pre-defined roles: Admin, Employee, Customer.
    *   Granular permissions assigned to roles.
3.  **Password Security**:
    *   Strong password requirements enforced via validation rules.
    *   Secure password hashing using bcrypt.
    *   Standard password reset functionality.
4.  **Route & Action Protection**:
    *   Middleware (`role:`, `permission:`) protects routes.
    *   Controller and view checks enforce fine-grained access.

### Auth Technical Decisions (TDR)

Decisions regarding the implementation of authentication and authorization are documented in the `doc/tdr` directory.

- **[TDR 001: Authentication and Authorization Implementation](./doc/tdr/001-authentication-choices.md)**: Documents the choice of Laravel Auth, Socialite, and Spatie Permission.

## Backend Implementation

### Controllers

The application follows a modular controller approach within `app/Http/Controllers`:

1.  **Web Controllers** (`App\Http\Controllers\Web`):
    *   `UsersController`: Manages user registration, authentication (email/pass & social), profile management.
    *   `ProductsController`: Handles product listing, details, search, and management (for Admin/Employee).
    *   `OrdersController`: Processes cart management, checkout, order creation, viewing, and status updates.
    *   `GradeController`, `QuizController`: Handle specific module functionalities.
2.  **Base Controllers** (`App\Http\Controllers`):
    *   `UserController`: Handles specific user actions like customer listing, credit management, employee creation (potentially refactor/merge with Web\UsersController).
    *   `Controller`: Base controller class.

### Models

Eloquent ORM models (`app/Models`) interact with the database:

1.  **User**: Represents user accounts, uses `HasRoles` trait.
2.  **Product**: Represents product information.
3.  **Order**: Manages customer purchases, relationships to `User` and `OrderItem`.
4.  **OrderItem**: Represents items within an order.
5.  **Grade**, **Quiz**, **Question**, **QuizSubmission**: Models for specific application modules.
6.  **Role**, **Permission**: Models provided by Spatie Laravel Permission.

### Middleware

Custom and built-in middleware (`app/Http/Middleware`) handle request processing:

-   **Authentication**: `Authenticate.php` (Laravel default).
-   **Authorization**: 
    * `RoleMiddleware`: Check roles using `role:admin|employee` format (pipe symbol for OR logic).
    * `PermissionMiddleware`: Check permissions using `permission:add_products|edit_products` format (pipe symbol for OR logic).
    * Both middleware are from Spatie package, aliased in `Kernel.php`.
-   **CSRF Protection**: `VerifyCsrfToken.php` (Laravel default).
-   **Rate Limiting**: Defined in `bootstrap/app.php` or route definitions (e.g., `throttle:3,5`, `rate.login`).
-   **Custom Rate Limiters**: Custom rate limiters are configured in `App\Providers\RouteServiceProvider`.
-   **Security Headers**: Recommended to add a middleware for setting security headers like Content-Security-Policy.

## Frontend Implementation

### Key Views

1.  **Layouts**: Base layout (`layouts/master.blade.php`), menu (`layouts/menu.blade.php`).
2.  **Authentication**: Login, registration, password reset views (`resources/views/users`).
3.  **Products**: Product list, details, edit form (`resources/views/products`).
4.  **Orders/Cart**: Cart view, checkout, order list, order details (`resources/views/orders`).
5.  **User Management**: User list, profile, edit forms (`resources/views/users`).
6.  **Feedback Management**: Feedback list, feedback details views (`resources/views/feedback`).
7.  **Other Modules**: Views for grades, quizzes, etc. (`resources/views/grades`, `resources/views/quizzes`).
8.  **Welcome Page**: `resources/views/welcome.blade.php`.

### UI Enhancements

-   Bootstrap 5 for responsive design.
-   Custom CSS for branding/styling with dark mode support.
-   Theme management with user preferences.
-   Blade components for reusable UI elements (e.g., forms, alerts).
-   Form validation with real-time feedback.
-   Responsive layout for all device sizes.
-   Animated transitions using Animate.css.
-   Proper use of Illuminate\Support\Str for text manipulation in views.

## Security Considerations

Security is a primary focus. Key measures include authentication, authorization, input validation, CSRF protection, XSS prevention (via Blade), and SQL injection prevention (via Eloquent).

**For a detailed breakdown of security measures, refer to the dedicated security document:**

-   **[Security Details](./doc/security.md)**

## API Endpoints

Currently, the application focuses on web routes. The `routes/api.php` file contains a basic placeholder protected by Sanctum.

*(If API endpoints are developed, they should be documented here, including authentication methods (e.g., Sanctum tokens) and rate limiting.)*

## Testing

The application should include comprehensive testing:

1.  **Unit Tests**: Testing individual classes, methods, and validation rules.
2.  **Feature Tests**: Testing user workflows, authentication, authorization, and interactions via HTTP requests.
3.  **Security Testing**: Specific tests for potential vulnerabilities (though many are covered by framework defaults and feature tests).

*(Testing setup and specific test suites should be detailed further if implemented)*

## Deployment and Maintenance

Standard Laravel deployment procedures apply:

1.  **Environment Configuration**: Secure management of `.env` file for production (DB credentials, App Key, Social Keys, Mail settings). `APP_DEBUG` must be `false`.
2.  **Performance Optimization**:
    *   `composer install --optimize-autoloader --no-dev`
    *   `php artisan config:cache`
    *   `php artisan route:cache`
    *   `php artisan view:cache`
    *   Configure Redis for caching (`.env`).
    *   Frontend asset bundling/minification (e.g., using Vite).
3.  **Monitoring and Logging**: Configure logging channels in `config/logging.php`. Monitor `storage/logs/laravel.log`.
4.  **Backup and Recovery**: Implement regular database backups and ensure version control (Git) is used.
5.  **Maintenance Mode**: Use `php artisan down` and `php artisan up`.

## Route Configuration Best Practices

The application follows certain best practices for route configuration:

1. **Authentication Guards**: Explicitly specifying guards like `auth:web` instead of just `auth`.
2. **Permission Middleware**: Using pipe symbol (`|`) for OR condition between permissions: `permission:add_products|edit_products|delete_products`.
3. **Route Organization**: Routes are grouped by authentication and permission requirements.
4. **Rate Limiting**: Critical routes have rate limiters to prevent abuse.
5. **Named Routes**: All routes have descriptive names for easy reference in views and controllers.

**Important**: When using the Spatie Permission middleware:
- Use `role:admin|employee` (pipe symbol) for checking multiple roles (OR logic)
- Use `permission:create_posts|edit_posts` (pipe symbol) for checking multiple permissions (OR logic)
- Avoid using commas as they can be misinterpreted by the routing system
