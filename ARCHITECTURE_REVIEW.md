# PDS System — Comprehensive Architecture & Code Quality Report

**Generated:** 2026-06-05  
**Scope:** Full Laravel Codebase Review (Architecture, Code Quality, Performance, Security, Dependencies)

---

## Executive Summary

The **LGU Trento PDS Management System** is a hybrid Laravel application with **two distinct architectural eras** coexisting:

| Pattern | Base Class | Files | Characteristics |
|---------|-----------|-------|-----------------|
| **Modern** | `Controller` (abstract) | ~13 files | DI, typed returns, Form Requests, Eloquent ORM, service layer |
| **Legacy** | `MasterController` | ~30 files | JWT-embedded auth, `DB::table()` raw queries, inline validation, 11-property constructor boilerplate duplicated in every file |

The legacy portion accounts for **~70% of the codebase** and contains the most critical issues: mass assignment vulnerabilities, duplicated CRUD boilerplate, snake_case methods, and business logic embedded directly in controllers.

---

## 1. Architectural & Structural Analysis

### 1.1 Separation of Concerns — "Fat Controllers"

#### 🔴 CRITICAL: `MasterController` — 1300+ lines (Legacy Parent)
| Location | `app/Http/Controllers/MasterController.php` |
|----------|----------------------------------------------|
| **Problem** | Single class provides JWT validation, access control, ID obfuscation, audit logging, config lookups, image conversion — violations of SRP |
| **Solution** | Split into dedicated services: `TokenService`, `AccessControlService`, `AuditLogService` (already exists), `ConfigurationService` |

#### 🔴 CRITICAL: Legacy Controller Boilerplate (30 files)
| Location | All controllers extending `MasterController` (e.g., `LeaveRequestController.php` — 2053 lines, `TravelRequestController.php` — 1650 lines) |
|----------|--------------------------------------------------------------------------------------------------------------------------------------------|
| **Problem** | Every legacy controller duplicates identical 30-line constructor setting `$response`, `$module`, `$controller`, `$logTitle`, `$table`, `$tablePrimaryKey`, `$page`, `$view_path`, `$moduleActionIDs`, `$auditFieldValues`, `$data` |
| **Solution** | Extract to a base configuration trait or constructor config array |

```php
// BEFORE — in every legacy controller
public function __construct()
{
    $this->response = new Response();
    $this->module = 'Leave Applications';
    $this->controller = 'leave-applications';
    $this->logTitle = 'Leave Application';
    $this->table = 'leave_applications';
    $this->tablePrimaryKey = 'leaveApplicationID';
    $this->view_path = 'modules/leave_applications';
    $this->moduleActionIDs = [...];
    $this->auditFieldValues = [...];
}

// AFTER — centralized config
class MasterController extends Controller
{
    protected array $moduleConfig = [];

    public function __construct(protected Response $response) {}

    protected function initializeConfig(array $config): void
    {
        $this->moduleConfig = $config;
        view()->share('view_path', $config['view_path']);
    }
}

// In child:
class LeaveRequestController extends MasterController
{
    public function __construct(Response $response)
    {
        parent::__construct($response);
        $this->initializeConfig([
            'module' => 'Leave Applications',
            'controller' => 'leave-applications',
            'view_path' => 'modules/leave_applications',
            // ...
        ]);
    }
}
```
**Impact:** HIGH

#### 🔴 CRITICAL: `PdsDataService.php` — 636 lines, doing too much
| Location | `app/Services/PdsDataService.php` |
|----------|------------------------------------|
| **Problem** | Handles field schemas, persistence, QR generation, validation rules, office lookups — violates SRP. `save()` and `update()` are ~90% duplicated |
| **Solution** | Split into `PdsSchemaService`, `PdsPersisterService`, `PdsNormalizerService` |

```php
// BEFORE — one class handles everything
class PdsDataService {
    public function save(...) { /* 60 lines */ }
    public function update(...) { /* 60 lines — nearly identical */ }
    public function defaultData(...) { }
    public function validationRules(...) { }
    private function generateQr(...) { }
}

// AFTER — single responsibility classes
class PdsPersisterService {
    public function upsert(Employee $employee, array $data, ?User $user): Employee {
        DB::transaction(function () use ($employee, $data, $user) {
            $employee->personalInformation()->updateOrCreate([], $data['personal']);
            $this->replaceOrderedRows($employee->education(), $data['education'] ?? []);
            // ... unified upsert logic
        });
        return $employee->fresh();
    }
}

class PdsSchemaService {
    public function validationRules(): array { /* ... */ }
    public function defaultData(): array { /* ... */ }
}
```
**Impact:** HIGH

### 1.2 Route Organization

| Location | `routes/web.php` |
|----------|-------------------|
| **Problem** | Routes are well-organized into `guest`, `auth`, `role:admin`, `role:user` middleware groups. No business logic in routes file. ✅ |
| **Assessment** | Clean organization. Good. |
| **Impact:** LOW (positive finding) |

### 1.3 Model Integrity

#### 🔴 CRITICAL: Mass Assignment (`$guarded = []` in 11 models)
| Location | `Employee.php`, `PersonalInformation.php`, `FamilyBackground.php`, `Education.php`, `Eligibility.php`, `WorkExperience.php`, `VoluntaryWork.php`, `Training.php`, `OtherInformation.php`, `EmployeeChangeLog.php`, `ImportHistory.php`, `SystemAuditLog.php` |
|----------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Problem** | All 12 models have `$guarded = []`, making **every column mass-assignable**. Employee PII, family data, work history — all can be overwritten via request |
| **Solution** | Replace with explicit `$fillable` arrays |

```php
// BEFORE
class Employee extends Model
{
    protected $guarded = [];

// AFTER
class Employee extends Model
{
    protected $fillable = [
        'user_id', 'office', 'profile_photo_path', 'e_signature_path',
        'drawn_signature', 'is_active', 'employment_type',
        'job_order_title', 'qr_code', 'created_by',
    ];
```
**Impact:** HIGH

#### 🔴 HIGH: Missing `$hidden` on `Token.php`
| Location | `app/Models/Token.php:16` |
|----------|---------------------------|
| **Problem** | The `token` column (actual JWT/API token) is NOT in `$hidden`. It's exposed in all JSON/array serialization |
| **Solution** | Add `'token', 'deviceFingerprint'` to `$hidden` |

```php
// BEFORE
class Token extends Model
{
    protected $fillable = ['userID', 'username', 'deviceFingerprint', 'token', ...];

// AFTER
class Token extends Model
{
    protected $fillable = ['userID', 'username', 'deviceFingerprint', 'token', ...];
    protected $hidden = ['token', 'deviceFingerprint'];
```
**Impact:** HIGH

#### 🟡 MEDIUM: Missing Relationships on User Model
| Location | `app/Models/User.php` |
|----------|-----------------------|
| **Problem** | `User` has no Eloquent relationships defined despite having `employee_id` foreign key |
| **Solution** | Add `employee()`, `tokens()`, `importHistories()` relationships |

```php
// AFTER
class User extends Authenticatable implements MustVerifyEmail
{
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class, 'userID', 'id');
    }
}
```
**Impact:** MEDIUM

---

## 2. Code Quality & Clean Code Practices

### 2.1 DRY Violations — Duplicated Code

#### 🔴 CRITICAL: `save()` / `update()` in `PdsDataService.php`
| Lines | ~90% of both methods (lines ~200-450) |
|-------|----------------------------------------|
| **Problem** | `save()` for new records and `update()` for existing records have identical logic for personal info, family, education, eligibility, work experience, voluntary work, trainings, other info |
| **Solution** | Unify into single `upsert()` method |

**Impact:** HIGH

#### 🔴 CRITICAL: `items()` / `print_items()` in ALL legacy controllers
| Location | Every legacy controller (Attendance, AuditLog, AuthenticationLog, LeaveRequest, TravelRequest, etc.) |
|----------|-------------------------------------------------------------------------------------------------------|
| **Problem** | Every controller duplicates identical query-building logic between `items()` (paginated list) and `print_items()` (export/print). The only difference is pagination vs. full result |
| **Solution** | Create a reusable `ListingService` or use a base class method |

```php
// BEFORE — duplicated in 15+ controllers
public function items(Request $request) {
    $data = DB::table('leave_applications')
        ->leftJoin('users', ...)
        ->leftJoin('user_personal_informations', ...)
        ->where(...)
        ->paginate(10);
    return response()->json($data);
}

public function print_items(Request $request) {
    $data = DB::table('leave_applications')
        ->leftJoin('users', ...)
        ->leftJoin('user_personal_informations', ...)
        ->where(...)
        ->get(); // only difference
    return response()->json($data);
}

// AFTER
trait HasListableItems {
    protected function buildItemQuery(Request $request, string $table, array $joins = [], array $filters = [])
    {
        $query = DB::table($table);
        foreach ($joins as $join) {
            $query->leftJoin(...$join);
        }
        // apply filters from $request
        return $query;
    }

    public function items(Request $request) {
        return $this->buildItemQuery(...)->paginate(10);
    }

    public function print_items(Request $request) {
        return $this->buildItemQuery(...)->get();
    }
}
```
**Impact:** HIGH

#### 🔴 CRITICAL: Leave Credit Fraction Lookup (repeated 10+ times)
| Location | `LeaveRequestController.php` and `MyLeaveRequestController.php` |
|----------|------------------------------------------------------------------|
| **Problem** | The same credit fraction lookup query (`SELECT * FROM leave_credit_fractions WHERE ...`) is duplicated across `post`, `check_page`, `check`, `approve`, `get`, and `print_leave_application_data` methods in both controllers |
| **Solution** | Extract to a `LeaveCreditService` |

**Impact:** HIGH

### 2.2 Naming Conventions

#### 🔴 HIGH: Snake_case Methods in TokenHelper
| Location | `app/Libraries/TokenHelper.php` |
|----------|----------------------------------|
| **Problem** | Uses `token_encode()`, `token_decode()` instead of PSR-12 `tokenEncode()`, `tokenDecode()` |
| **Solution** | Rename to camelCase |

**Impact:** MEDIUM

#### 🟡 MEDIUM: Leading Underscore in Method
| Location | `app/Libraries/PasswordHelper.php` — `_isValidPassword()` |
|----------|-----------------------------------------------------------|
| **Problem** | Underscore prefix is non-standard in PHP (suggests private but it's public static) |
| **Solution** | Rename to `isValidPassword()` |

**Impact:** LOW

### 2.3 Missing Type Hints & Return Types

#### 🔴 HIGH: Legacy Controllers — Zero Type Hints
| Location | All 30 legacy controllers |
|----------|---------------------------|
| **Problem** | No method has parameter or return type hints. `$_POST`/`$_GET` superglobals used directly instead of `$request->input()` |
| **Solution** | Add `: JsonResponse`, `: View` return types; type all parameters |

**Impact:** HIGH

#### 🟡 MEDIUM: Missing Return Types in Services
| Location | `PdsDataService.php` (6 methods), `PdsFileParser.php` (6 methods), `Response.php`, `TokenHelper.php` |
|----------|------------------------------------------------------------------------------------------------------|
| **Solution** | Add `: array`, `: ?string`, `: bool`, `: void` return types |

**Impact:** MEDIUM

---

## 3. Performance & Optimization

### 3.1 Database Queries — N+1 Problems

#### 🔴 HIGH: Queries Inside Loops — Leave Requests
| Location | `LeaveRequestController.php` — `check()`, `approve()`, `post()` methods |
|----------|-------------------------------------------------------------------------|
| **Problem** | Credit fraction lookups and leave balance queries execute inside `foreach` loops over leave types |
| **Solution** | Eager load or batch-query credit fractions before looping |

```php
// BEFORE — N+1 inside loop
foreach ($leaveTypes as $type) {
    $fraction = DB::table('leave_credit_fractions')
        ->where('leaveTypeID', $type->id)->first();
}

// AFTER — batch query
$typeIds = $leaveTypes->pluck('id');
$fractions = DB::table('leave_credit_fractions')
    ->whereIn('leaveTypeID', $typeIds)
    ->get()
    ->keyBy('leaveTypeID');

foreach ($leaveTypes as $type) {
    $fraction = $fractions->get($type->id);
}
```
**Impact:** HIGH

#### 🟡 MEDIUM: Schema::hasColumn() Called on Every Request
| Location | `PdsDataService.php` — `personalPersistFields()` |
|----------|----------------------------------------------------|
| **Problem** | Queries `information_schema` on every request to check column existence |
| **Solution** | Cache schema results or use a config-based field list |

```php
// BEFORE
if (Schema::hasColumn('personal_informations', 'job_order')) { ... }

// AFTER
protected array $knownColumns = [];
protected function columnExists(string $table, string $column): bool
{
    if (!isset($this->knownColumns[$table])) {
        $this->knownColumns[$table] = Schema::getColumnListing($table);
    }
    return in_array($column, $this->knownColumns[$table]);
}
```
**Impact:** MEDIUM

### 3.2 Caching Opportunities

#### 🟡 MEDIUM: Office List / Dashboard Stats
| Location | `DashboardController`, `PdsAdminService::sidebarCounts()`, `PdsDataService::officeOptions()` |
|----------|----------------------------------------------------------------------------------------------|
| **Problem** | Dashboard statistics and office dropdown data are recomputed on every page load |
| **Solution** | Cache with `Cache::remember('office_counts', 3600, fn() => ...)` |

**Impact:** MEDIUM

### 3.3 Asset Optimization

| Location | `resources/views/layouts/blank.blade.php` |
|----------|--------------------------------------------|
| **Problem** | Loads legacy CSS/JS via CDN (Boxicons, FontAwesome, jQuery, SweetAlert2) alongside Vite-built assets. Redundant loading |
| **Solution** | Migrate all assets to Vite; remove CDN dependencies |

**Impact:** LOW

---

## 4. Security Audit

### 4.1 Input Validation

#### 🔴 CRITICAL: `$_POST` / `$_GET` Superglobals in Legacy Controllers
| Location | ALL 30 legacy controllers (e.g., `AuthenticationController.php`, `BarangayController.php`, etc.) |
|----------|---------------------------------------------------------------------------------------------------|
| **Problem** | Direct use of `$_POST['field']` bypasses Laravel's input validation, CSRF protection, and request lifecycle |
| **Solution** | Migrate to `$request->input('field')` with Form Request validation |

```php
// BEFORE
$_POST['username'];
$_GET['page'];

// AFTER
$request->validate(['username' => 'required|string|max:255']);
$request->input('username');
$request->input('page', 1);
```
**Impact:** HIGH

#### 🟡 MEDIUM: Form Requests Return `true` Without Authorization
| Location | `app/Http/Requests/StorePdsRequest.php`, `UpdatePdsRequest.php` |
|----------|-----------------------------------------------------------------|
| **Problem** | `authorize()` returns `true` unconditionally — no permission check |
| **Solution** | Add proper authorization gate or policy check |

```php
// AFTER
public function authorize(): bool
{
    return auth()->user() !== null;
}
```
**Impact:** MEDIUM

### 4.2 Authentication & Authorization

#### 🔴 CRITICAL: Weak ID Obfuscation (Not Encryption)
| Location | `MasterController.php` — `_idConverter()` / `_decryptID()` |
|----------|-----------------------------------------------------------|
| **Problem** | Uses integer arithmetic (multiply by 123, add 45, pad to 700) — trivially reversible. Provides false sense of security |
| **Solution** | Use Laravel's native route-model binding with `Hashids` or proper encryption |

```php
// BEFORE — trivially reversible
$encrypted = ($id * 123 + 45) + 700;
$decrypted = ($encrypted - 700 - 45) / 123;

// AFTER — use Hashids or route model binding
// Route::get('/items/{item}', ...) — automatic binding
// Or use laravel/hashids for obscured IDs
$encoded = Hashids::encode($id);
$decoded = Hashids::decode($encoded)[0];
```
**Impact:** HIGH

#### 🟡 MEDIUM: Sanctum Tokens Never Expire
| Location | `config/sanctum.php` — `'expiration' => null` |
|----------|------------------------------------------------|
| **Problem** | Sanctum tokens have no expiration — once issued, valid forever |
| **Solution** | Set a reasonable expiration (e.g., 24 hours) |

```php
// AFTER
'expiration' => env('SANCTUM_TOKEN_EXPIRATION', 1440), // 24 hours in minutes
```
**Impact:** MEDIUM

### 4.3 XSS & Blade Templating

| Location | All Blade templates in `resources/views/` |
|----------|-------------------------------------------|
| **Problem** | Spot-check shows proper use of `{{ }}` escaping. No unescaped `{!! !!}` found in unsafe contexts ✅ |
| **Assessment** | Good — Blade templates properly escape output |
| **Impact:** LOW (positive finding) |

### 4.4 Mass Assignment (Repeated from 1.3 for Security Context)

| Models | `$guarded = []` in 12 models |
|--------|------------------------------|
| **Risk** | Attacker can inject arbitrary column values via request payload |
| **Solution** | Explicit `$fillable` arrays in all models |

**Impact:** HIGH (Security)

---

## 5. Dependency & Environment

### 5.1 Composer Packages

| Package | Version | Notes |
|---------|---------|-------|
| `laravel/framework` | ^11.31 | Latest stable ✅ |
| `barryvdh/laravel-dompdf` | ^3.1 | PDF generation ✅ |
| `maatwebsite/excel` | ^3.1 | Excel import/export ✅ |
| `tymon/jwt-auth` | ^2.1 | JWT auth for legacy API ✅ |
| `laravel/sanctum` | ^4.0 | SPA auth ✅ |
| `firebase/php-jwt` | ^6.11 | Additional JWT library (redundant with tymon/jwt-auth) 🟡 |
| `paragonie/sodium_compat` | ^2.1 | Sodium crypto polyfill ✅ |
| `simplesoftwareio/simple-qrcode` | ^4.2 | QR code generation ✅ |
| `smalot/pdfparser` | ^2.12 | PDF parsing ✅ |

#### 🟡 MEDIUM: Redundant JWT Libraries
| Issue | Both `tymon/jwt-auth` and `firebase/php-jwt` are installed. Custom `TokenHelper` bypasses tymon and uses firebase directly |
|-------|---------------------------------------------------------------------------------------------------------------------------|
| **Solution** | Consolidate on `tymon/jwt-auth` only; remove `firebase/php-jwt` and `TokenHelper` |

### 5.2 Environment Configuration

#### 🔴 HIGH: `.env` Contains Production Credentials in Local
| Location | `.env` (lines 55-56, 68) |
|----------|--------------------------|
| **Problem** | Gmail SMTP password and JWT_SECRET hardcoded in `.env` for development. APP_URL points to ngrok tunnel |
| **Solution** | Use `.env.local` for development overrides; keep `.env` as template only |

**Impact:** HIGH

#### ✅ GOOD: `.env` is gitignored
| Assessment | `.env` is in `.gitignore` line 15 — credentials will not be committed ✅ |

### 5.3 Redundant Custom Libraries

#### 🟡 MEDIUM: `Response.php` Library
| Location | `app/Libraries/Response.php` |
|----------|------------------------------|
| **Problem** | Entirely duplicates Laravel's `response()->json()` |
| **Solution** | Remove class; use `response()->json(['status' => $code, 'message' => $msg], $code)` |

**Impact:** MEDIUM

#### 🟡 MEDIUM: `TokenHelper.php` Library
| Location | `app/Libraries/TokenHelper.php` |
|----------|---------------------------------|
| **Problem** | Duplicates `tymon/jwt-auth` functionality. Bypasses Tymon's standard claims/blacklist/refresh by calling `getJWTProvider()->encode()` directly |
| **Solution** | Replace with `JWTAuth::claims()->fromUser()`, `JWTAuth::parseToken()->authenticate()` |

**Impact:** MEDIUM

---

## 6. Consolidated Priority Action Plan

| Priority | Issue | Location | Effort |
|----------|-------|----------|--------|
| 🔴 P0 | Mass Assignment (`$guarded = []` in 12 models) | Multiple Models | 1 day |
| 🔴 P0 | Token column exposed in JSON (`Token.php`) | `app/Models/Token.php` | 30 min |
| 🔴 P0 | Weak ID obfuscation (false encryption) | `MasterController.php` | 2 days |
| 🔴 P0 | Direct `$_POST`/`$_GET` usage (30 legacy controllers) | Legacy Controllers | 5 days |
| 🔴 P1 | `save()`/`update()` duplication in `PdsDataService` | `app/Services/PdsDataService.php` | 1 day |
| 🔴 P1 | Leave credit N+1 queries in loops | `LeaveRequestController.php` | 2 days |
| 🟡 P2 | `TokenHelper` bypassing Tymon JWT security | `app/Libraries/TokenHelper.php` | 1 day |
| 🟡 P2 | Redundant `Response.php` library | `app/Libraries/Response.php` | 0.5 day |
| 🟡 P2 | Snake_case methods in legacy code | Multiple files | 1 day |
| 🟡 P3 | Missing type hints (all legacy and some new code) | Multiple files | 2 days |
| 🟡 P3 | Sanctum token expiration not set | `config/sanctum.php` | 5 min |
| 🟡 P3 | Schema::hasColumn() on every request | `PdsDataService.php` | 30 min |
| 🟢 P4 | Missing relationships on User model | `app/Models/User.php` | 30 min |
| 🟢 P4 | Cache dashboard stats | `DashboardController.php` | 1 day |
| 🟢 P4 | Extract magic numbers to constants | `PdsDataService.php`, `PdsFileParser.php` | 1 day |

---

## 7. Key Metrics

| Metric | Value |
|--------|-------|
| Total Controllers | 43 |
| Legacy (MasterController) | 30 |
| Modern (Controller) | 13 |
| Models | 29 |
| Models with `$guarded = []` | 12 |
| Models with proper `$fillable` | 11 |
| Models with `$hidden` | 2 |
| Services | 4 |
| Custom Libraries (redundant) | 2 (`Response.php`, `TokenHelper.php`) |
| Total Service Lines | ~1340 |
| Largest Controller | `UserPortalController.php` ~1310 lines |
| Largest Legacy Controller | `LeaveRequestController.php` ~2053 lines |
| Largest Service | `PdsDataService.php` ~636 lines |
