# Unused Scaffolding Files

This document lists the files and directories identified as unused leftovers from the initial Laravel Breeze scaffolding. These files are not linked or used by the current custom "Bossku House" implementation.

## 1. Controllers
- `app/Http/Controllers/ProfileController.php`

## 2. Views & Layouts
- `resources/views/welcome.blade.php` (Landing page redirects to customer menu)
- `resources/views/dashboard.blade.php` (Dashboard route redirects to role-specific dashboards)
- `resources/views/layouts/app.blade.php` (Default layout, only used by profile pages)
- `resources/views/layouts/navigation.blade.php` (Default navigation, only used by `app.blade.php`)

## 3. Profile Module
The entire profile module is inaccessible from the main application navigation:
- `resources/views/profile/edit.blade.php`
- `resources/views/profile/partials/delete-user-form.blade.php`
- `resources/views/profile/partials/update-password-form.blade.php`
- `resources/views/profile/partials/update-profile-information-form.blade.php`

## 4. Unused Components
These components were only used by the default navigation and profile views:
- `resources/views/components/dropdown.blade.php`
- `resources/views/components/dropdown-link.blade.php`
- `resources/views/components/nav-link.blade.php`
- `resources/views/components/responsive-nav-link.blade.php`
- `resources/views/components/modal.blade.php`
- `resources/views/components/danger-button.blade.php`
- `resources/views/components/secondary-button.blade.php`

## 5. Routes
In `routes/web.php`, the following routes are technically active but point to the unused profile feature:
```php
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
```
