<?php

use App\Http\Controllers\AdminAuditLogPageController;
use App\Http\Controllers\AdminToolsController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandAssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OfficePageController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserPortalController;
use App\Http\Controllers\IdTemplateController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    Route::get('/portal/login', [UserAuthController::class, 'showLogin'])->name('user.login');
    Route::post('/portal/login', [AuthController::class, 'login'])->name('user.login.attempt');
    Route::get('/signup', [UserAuthController::class, 'showRegister'])->name('user.register');
    Route::post('/signup', [AuthController::class, 'registerUser'])->name('user.register.store');
});

Route::get('/brand/logo', [BrandAssetController::class, 'logo'])->name('brand.logo');
Route::get('/brand/login-background', [BrandAssetController::class, 'loginBackground'])->name('brand.login-background');
Route::get('/public/profile/{employee}', [EmployeeController::class, 'publicShow'])->name('profile.public');
Route::get('/public/profile/{employee}/print', [EmployeeController::class, 'publicPrint'])->name('profile.public.print');
Route::get('/public/profile/{employee}/photo', [EmployeeController::class, 'publicPhoto'])->name('profile.public.photo');
Route::get('/public/profile/{employee}/signature', [EmployeeController::class, 'publicSignature'])->name('profile.public.signature');

Route::middleware('auth')->group(function () {
    Route::post('/portal/notifications/read', [UserPortalController::class, 'markNotificationsRead'])->name('user.notifications.read');
    Route::get('/portal/notifications/{id}/read', [UserPortalController::class, 'markSingleNotificationRead'])->name('user.notifications.read.single');
    Route::post('/pds/{employee}/regenerate-qr', [EmployeeController::class, 'regenerateQr'])->name('pds.regenerate-qr');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/email/verify', [UserAuthController::class, 'verifyNotice'])
        ->name('verification.notice');
    Route::post('/email/verify', [UserAuthController::class, 'verifyEmail'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [UserAuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/offices', OfficePageController::class)->name('offices.index');
        Route::post('/admin/offices', [\App\Http\Controllers\AdminOfficeController::class, 'store'])->name('admin.offices.store');
        Route::put('/admin/offices/{admin_office}', [\App\Http\Controllers\AdminOfficeController::class, 'update'])->name('admin.offices.update');
        Route::delete('/admin/offices/{admin_office}', [\App\Http\Controllers\AdminOfficeController::class, 'destroy'])->name('admin.offices.destroy');
        Route::get('/records', [EmployeeController::class, 'index'])->name('records.index');
        Route::get('/reports/analytics', [EmployeeController::class, 'reportAnalytics'])->name('reports.analytics');
        Route::get('/reports/analytics/export-excel', [EmployeeController::class, 'exportAnalyticsExcel'])->name('reports.analytics.export-excel');
        Route::get('/admin/import-history', [AdminToolsController::class, 'importHistory'])->name('admin.import-history');
        Route::get('/admin/import-history/{importHistory}/error-report', [AdminToolsController::class, 'downloadErrorReport'])->name('admin.import-history.error-report');
        Route::get('/admin/incomplete-queue', [AdminToolsController::class, 'incompleteQueue'])->name('admin.incomplete-queue');
        Route::post('/admin/incomplete-queue/{employee}/notify-user', [AdminToolsController::class, 'notifyIncomplete'])->name('admin.incomplete-queue.notify');
        Route::get('/admin/audit-logs', [AdminAuditLogPageController::class, 'index'])->name('admin.audit-logs');
        Route::delete('/admin/audit-logs', [AdminAuditLogPageController::class, 'clear'])->name('admin.audit-logs.clear');
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::put('/admin/users/{user}/password', [AdminUserController::class, 'updatePassword'])->name('admin.users.password');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

        Route::get('/pds/create', [EmployeeController::class, 'create'])->name('pds.create');
        Route::post('/pds', [EmployeeController::class, 'store'])->name('pds.store');
        Route::get('/pds/{employee}/edit', [EmployeeController::class, 'edit'])->name('pds.edit');
        Route::put('/pds/{employee}', [EmployeeController::class, 'update'])->name('pds.update');
        Route::delete('/pds/{employee}', [EmployeeController::class, 'destroy'])->name('pds.destroy');
        Route::patch('/pds/{employee}/toggle-active', [EmployeeController::class, 'toggleActive'])->name('pds.toggle-active');
        Route::get('/pds/upload', [EmployeeController::class, 'upload'])->name('pds.upload');
        Route::post('/pds/upload', [EmployeeController::class, 'parseUpload'])->name('pds.upload.parse');

        // ID Templates Management
        Route::get('/id-templates', [IdTemplateController::class, 'index'])->name('admin.id-templates.index');
        Route::post('/id-templates', [IdTemplateController::class, 'store'])->name('admin.id-templates.store');
        Route::patch('/id-templates/{template}/activate', [IdTemplateController::class, 'activate'])->name('admin.id-templates.activate');
        Route::put('/id-templates/{template}', [IdTemplateController::class, 'update'])->name('admin.id-templates.update');
        Route::delete('/id-templates/{template}', [IdTemplateController::class, 'destroy'])->name('admin.id-templates.destroy');

        Route::get('/records/batch-id-cards', [EmployeeController::class, 'batchViewIdCards'])->name('pds.records.batch-id-cards');
        Route::get('/records/batch-valid-ids', [EmployeeController::class, 'batchViewValidIdCards'])->name('pds.records.batch-valid-ids');
        Route::get('/records/{employee}', [EmployeeController::class, 'show'])->name('pds.records.show');
        Route::get('/records/{employee}/id-card', [EmployeeController::class, 'viewIdCard'])->name('pds.records.id-card');
        Route::get('/records/{employee}/valid-id', [EmployeeController::class, 'viewValidIdCard'])->name('pds.records.valid-id');
        Route::get('/records/{employee}/edit', [EmployeeController::class, 'edit'])->name('pds.records.edit');
        Route::get('/profile/{employee}', [EmployeeController::class, 'show'])->name('profile.show');
        Route::get('/profile/{employee}/photo', [EmployeeController::class, 'photo'])->name('profile.photo');
        Route::get('/profile/{employee}/signature', [EmployeeController::class, 'signature'])->name('profile.signature');
        Route::get('/profile/{employee}/print', [EmployeeController::class, 'print'])->name('profile.print');
        Route::get('/profile/{employee}/export-pdf', [EmployeeController::class, 'exportPdf'])->name('profile.export-pdf');
    });

    Route::middleware(['role:user', 'verified'])->group(function () {
        Route::get('/portal/dashboard', [UserPortalController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/portal/pds', [UserPortalController::class, 'pdsForm'])->name('user.pds.form');
        // Route::get('/portal/pds/upload', [UserPortalController::class, 'uploadForm'])->name('user.pds.upload');
        // Route::post('/portal/pds/upload', [UserPortalController::class, 'parseUpload'])->name('user.pds.upload.parse');
        Route::post('/portal/pds', [UserPortalController::class, 'savePds'])->name('user.pds.save');
        Route::get('/portal/profile-photo/{employee}', [UserPortalController::class, 'photo'])->name('user.profile-photo');
        Route::get('/portal/signature/{employee}', [UserPortalController::class, 'signature'])->name('user.signature');
        Route::get('/portal/pds/print', [UserPortalController::class, 'print'])->name('user.pds.print');
        Route::get('/portal/pds/export-pdf', [UserPortalController::class, 'exportPdf'])->name('user.pds.export-pdf');
        Route::get('/portal/offices', [UserPortalController::class, 'offices'])->name('user.offices');
        Route::get('/portal/offices/staff', [UserPortalController::class, 'officeStaff'])->name('user.offices.staff');
        Route::get('/portal/records', [UserPortalController::class, 'records'])->name('user.records');
        Route::get('/portal/records/create', [UserPortalController::class, 'createRecord'])->name('user.records.create');
        Route::post('/portal/records', [UserPortalController::class, 'storeRecord'])->name('user.records.store');
        // Route::get('/portal/records/upload', [UserPortalController::class, 'uploadRecordForm'])->name('user.records.upload');
        // Route::post('/portal/records/upload', [UserPortalController::class, 'parseRecordUpload'])->name('user.records.upload.parse');
        
        Route::get('/portal/records/{employee}', [UserPortalController::class, 'showRecord'])->name('user.records.show');
        Route::get('/portal/records/{employee}/print', [UserPortalController::class, 'printRecord'])->name('user.records.print');
        Route::get('/portal/records/{employee}/export-pdf', [UserPortalController::class, 'exportPdfRecord'])->name('user.records.export-pdf');
        Route::get('/portal/records/{employee}/id-card', [UserPortalController::class, 'viewIdCard'])->name('user.records.id-card');
        Route::get('/portal/records/{employee}/edit', [UserPortalController::class, 'editRecord'])->name('user.records.edit');
        Route::put('/portal/records/{employee}', [UserPortalController::class, 'updateRecord'])->name('user.records.update');
        Route::delete('/portal/records/{employee}', [UserPortalController::class, 'destroyRecord'])->name('user.records.destroy');

        Route::get('/portal/profile', [UserPortalController::class, 'showProfile'])->name('user.profile');
        Route::put('/portal/profile', [UserPortalController::class, 'updateProfile'])->name('user.profile.update');
    });
});
