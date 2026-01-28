# Architecture Diagram: Laravel + CI3 Integration

Diagram arsitektur integrasi Laravel dengan CodeIgniter 3 menggunakan package `opensid/laravel-ci3`.

## 🏗️ Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                         HTTP Request                            │
│                              ↓                                  │
│                    Laravel Entry Point                          │
│                      (public/index.php)                         │
└─────────────────────────────────────────────────────────────────┘
                               ↓
┌─────────────────────────────────────────────────────────────────┐
│                     Laravel Middleware Stack                     │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │ 1. TrustProxies                                          │  │
│  │ 2. HandleCors                                            │  │
│  │ 3. ValidatePostSize                                      │  │
│  │ 4. TrimStrings                                           │  │
│  │ 5. StartSession ← CRITICAL: Must be before CI3          │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                               ↓
┌─────────────────────────────────────────────────────────────────┐
│                CodeIgniterFallback Middleware                   │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Check if route exists in Laravel?                       │  │
│  │     ↓ YES → Process with Laravel                        │  │
│  │     ↓ NO → Try CI3 routing                              │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
            ↓ (404)                          ↓ (Found)
            ↓                                ↓
    ┌───────────────┐                ┌──────────────┐
    │ Try CI3 Route │                │ Laravel Route│
    └───────────────┘                └──────────────┘
            ↓                                ↓
    ┌───────────────────┐            ┌──────────────────┐
    │ CI3 Bootstrap     │            │ Laravel Controller│
    │ - Load CI3 System │            │ - Process Request │
    │ - Load Config     │            │ - Return Response │
    │ - Load Hooks      │            └──────────────────┘
    │ - Run Controller  │
    └───────────────────┘
            ↓
    ┌───────────────────┐
    │ CI3 Controller    │
    │ with LaravelBridge│
    └───────────────────┘
            ↓
    ┌───────────────────────────────────────┐
    │ Available in CI3 Controllers:         │
    │                                       │
    │ • LaravelBridge Trait Methods         │
    │   - $this->config()                   │
    │   - $this->cache()                    │
    │   - $this->log()                      │
    │   - $this->user()                     │
    │   - $this->json()                     │
    │   - $this->redirectTo()               │
    │                                       │
    │ • Laravel Facades (Static)            │
    │   - Session::get()                    │
    │   - Cache::put()                      │
    │   - Log::info()                       │
    │   - DB::table()                       │
    │   - Config::get()                     │
    │                                       │
    │ • Laravel Helper Functions            │
    │   - config()                          │
    │   - cache()                           │
    │   - session()                         │
    │   - module_path()                     │
    └───────────────────────────────────────┘
            ↓
    ┌───────────────────┐
    │ CI3 Response      │
    │ - View            │
    │ - JSON            │
    │ - Redirect        │
    └───────────────────┘
            ↓
    ┌───────────────────────────────────┐
    │ Inject DebugBar (if enabled)      │
    └───────────────────────────────────┘
            ↓
┌─────────────────────────────────────────────────────────────────┐
│                         HTTP Response                           │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Request Flow Examples

### Example 1: Laravel Route

```
Request: GET /api/users
    ↓
Laravel Routing → Found /api/users
    ↓
Laravel Controller (UsersController@index)
    ↓
Eloquent Query
    ↓
JSON Response
```

### Example 2: CI3 Route (Fallback)

```
Request: GET /welcome
    ↓
Laravel Routing → Not Found (404)
    ↓
CodeIgniterFallback Middleware
    ↓
CI3 Routing → Found Welcome controller
    ↓
CI3 Controller (Welcome::index)
    ↓
CI3 View or Response
    ↓
Inject DebugBar
    ↓
HTML Response
```

### Example 3: Module Route (HMVC)

```
Request: GET /contoh/dashboard
    ↓
Laravel Routing → Not Found (404)
    ↓
CodeIgniterFallback Middleware
    ↓
Check Modules/*/Routes/web.php
    ↓
Found Contoh module route
    ↓
Module Controller (Modules/Contoh/Controllers/Dashboard)
    ↓
Module View
    ↓
HTML Response
```

---

## 🗂️ File Structure

```
project-root/
│
├── app/                           # Laravel App
│   ├── Http/
│   │   ├── Controllers/          # Laravel Controllers
│   │   ├── Middleware/
│   │   └── Kernel.php            # Register CodeIgniterFallback here
│   ├── Models/                   # Laravel Models (Eloquent)
│   └── Providers/
│       └── AppServiceProvider.php
│
├── application/                   # CI3 Application
│   ├── controllers/              # CI3 Controllers
│   ├── models/                   # CI3 Models
│   ├── views/                    # CI3 Views
│   ├── config/
│   │   ├── hooks.php             # ✅ Published from package
│   │   ├── modules.php           # ✅ Published from package
│   │   └── autoload.php          # ⚠️ Update to load helpers
│   ├── core/
│   │   └── MY_Controller.php     # ✅ Published (with LaravelBridge trait)
│   ├── helpers/
│   │   ├── hooks_helper.php      # ✅ Published
│   │   ├── laravel_helper.php    # ✅ Published
│   │   └── laravel_facades_helper.php # ✅ Published
│   └── libraries/
│       └── MY_Session.php        # ✅ Published (Laravel session sync)
│
├── config/
│   ├── app.php                   # Register CodeIgniterServiceProvider
│   └── ci3.php                   # ✅ Published CI3 config
│
├── Modules/                       # HMVC Modules (optional)
│   └── Contoh/
│       ├── Controllers/
│       ├── Models/
│       ├── Views/
│       └── Routes/
│           └── web.php
│
├── vendor/
│   └── opensid/
│       └── laravel-ci3/          # Package
│           ├── src/
│           │   ├── CodeIgniterFallback.php
│           │   ├── Providers/
│           │   ├── Services/
│           │   └── Traits/
│           ├── stubs/            # Template files untuk publish
│           ├── config/
│           │   └── ci3.php
│           ├── README.md
│           ├── MIGRATION_GUIDE.md
│           ├── CI3_SETUP.md
│           └── CI3_HELPERS_REFERENCE.md
│
└── public/
    └── index.php                 # Laravel entry point
```

---

## 🔐 Session Sharing

```
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Session                          │
│                  (Illuminate\Session)                       │
│                          ↕                                  │
│              StartSession Middleware                        │
│                          ↕                                  │
│   ┌──────────────────┬──────────────────┐                 │
│   ↓                  ↓                  ↓                  │
│ Laravel          CI3 via              CI3 via              │
│ Controllers      MY_Session           Facades              │
│                  (libraries)          (Session::)          │
│                                                             │
│ session('key')   $this->session->     Session::get('key')  │
│                  userdata('key')                            │
└─────────────────────────────────────────────────────────────┘

Same session data accessible from:
1. Laravel: session() helper, Session facade
2. CI3: $this->session->userdata(), Session::get()
3. Both read/write to same storage
```

---

## 📊 Component Interaction

```
┌───────────────────────────────────────────────────────────────┐
│                   Laravel Container                           │
│  ┌─────────────────────────────────────────────────────────┐ │
│  │  Registered Services:                                   │ │
│  │                                                          │ │
│  │  • 'ci3.booted' → CI3Bootstrap (singleton)             │ │
│  │  • 'ci' → CI3Instance (singleton)                      │ │
│  │  • 'session' → Laravel Session                         │ │
│  │  • 'cache' → Laravel Cache                             │ │
│  │  • 'log' → Laravel Log                                 │ │
│  │  • 'db' → Laravel DB                                   │ │
│  │  • 'config' → Laravel Config                           │ │
│  └─────────────────────────────────────────────────────────┘ │
└───────────────────────────────────────────────────────────────┘
                          ↓
                  app() helper / Facades
                          ↓
        ┌─────────────────┴─────────────────┐
        ↓                                   ↓
┌─────────────────┐              ┌─────────────────────┐
│ Laravel Code    │              │ CI3 Code            │
│                 │              │                     │
│ Session::get()  │              │ Session::get()      │
│ Cache::put()    │              │ Cache::put()        │
│ Log::info()     │              │ Log::info()         │
│ DB::table()     │              │ DB::table()         │
└─────────────────┘              └─────────────────────┘
        ↓                                   ↓
    Same Laravel Services accessed from both sides
```

---

## 🎯 Key Integration Points

### 1. CodeIgniterServiceProvider

```php
// Registered in config/app.php
// Boot CI3 early in Laravel lifecycle
// Register 'ci' service in container
```

### 2. CodeIgniterFallback Middleware

```php
// Registered in app/Http/Kernel.php
// Intercepts 404 responses
// Tries CI3 routing before returning 404
// Injects DebugBar into responses
```

### 3. LaravelBridge Trait

```php
// Used in application/core/MY_Controller.php
// Provides Laravel helper methods to CI3 controllers
// Bridge between Laravel container and CI3
```

### 4. Laravel Facades Helper

```php
// File: application/helpers/laravel_facades_helper.php
// Creates static facades: Session, Cache, Log, DB, Config
// Uses app() to access Laravel container
```

### 5. MY_Session Library

```php
// File: application/libraries/MY_Session.php
// Overrides CI_Session methods
// Uses Laravel session directly via app('session')
// Syncs session data between frameworks
```

---

## 🔄 Deployment Flow

```
Development:
    Local Machine
    ↓
    php artisan serve
    ↓
    Test Laravel routes
    Test CI3 routes (fallback)
    Test session sharing
    Test facades

Staging:
    Deploy to staging server
    ↓
    Set .env: APP_ENV=staging
    ↓
    php artisan config:cache
    php artisan route:cache
    ↓
    Test all functionalities
    Monitor logs

Production:
    Deploy to production
    ↓
    Set .env: APP_ENV=production, APP_DEBUG=false
    ↓
    php artisan config:cache
    php artisan route:cache
    php artisan optimize
    ↓
    Monitor performance
    Monitor error logs
```

---

## 📈 Gradual Migration Strategy

```
Phase 1: Integration
├── Install opensid/laravel-ci3
├── Configure paths
├── Test existing CI3 routes
└── ✅ CI3 works in Laravel

Phase 2: Coexistence
├── Keep all CI3 code running
├── Build new features in Laravel
├── Use shared session/cache
└── ✅ Both frameworks active

Phase 3: Gradual Rewrite
├── Identify critical CI3 controllers
├── Rewrite one by one to Laravel
├── Keep non-critical in CI3
└── ✅ Hybrid application

Phase 4: Full Laravel (Optional)
├── All critical code in Laravel
├── CI3 only for legacy features
├── Consider full migration
└── ✅ Mostly Laravel

Phase 5: Complete (If desired)
├── Remove CI3 completely
├── All code in Laravel
└── ✅ Pure Laravel application
```

---

## 🎨 Visual Summary

```
┌─────────────────────────────────────────────────────────┐
│                    Your Application                     │
│  ┌───────────────────────────────────────────────────┐ │
│  │                                                   │ │
│  │  Laravel Side          opensid/         CI3 Side │ │
│  │  (New Features)       laravel-ci3    (Existing)  │ │
│  │                        Bridge                     │ │
│  │  ┌─────────┐            ↕            ┌─────────┐ │ │
│  │  │ Routes  │ ←──────────┼──────────→ │ Routes  │ │ │
│  │  │ API     │            │            │ Web     │ │ │
│  │  └─────────┘            │            └─────────┘ │ │
│  │                         │                        │ │
│  │  ┌─────────┐            │            ┌─────────┐ │ │
│  │  │ Eloquent│ ←──────────┼──────────→ │ Models  │ │ │
│  │  │ Models  │   Shared   │            │ CI3     │ │ │
│  │  └─────────┘   Session  │            └─────────┘ │ │
│  │                Cache     │                        │ │
│  │  ┌─────────┐   DB       │            ┌─────────┐ │ │
│  │  │ Blade   │ ←──────────┼──────────→ │ Views   │ │ │
│  │  │ Views   │            │            │ CI3     │ │ │
│  │  └─────────┘            │            └─────────┘ │ │
│  │                                                   │ │
│  └───────────────────────────────────────────────────┘ │
│                                                         │
│              One Application, Two Frameworks            │
└─────────────────────────────────────────────────────────┘
```

---

**For detailed migration steps, see [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)**
