<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\Website\HomeController;
use Illuminate\Support\Facades\Route;

use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaAlias;


Route::middleware('web')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

});

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::view('/', 'dashboard.dashboard')->name('dashboard');
    Route::resource('user', UserController::class);

    Route::get('edit-profile', [UserController::class, 'editProfile'])->name('edit.profile');
    Route::get('change_password', [UserController::class, 'change_password'])->name('change_password');
    Route::get('settings/company_settings', [SettingController::class, 'editCompanySetting'])->name('company.edit');
    Route::post('settings/company_setting', [SettingController::class, 'updateCompanySetting'])->name('company.update');

   // Role Permission
   Route::resource('developer/permission', PermissionController::class)->only('index', 'store');
//    Route::get('role/assign', [RoleController::class, 'roleAssign'])->name('role.assign');
  // Route::post('role/assign', [RoleController::class, 'storeAssign'])->name('store.assign');
   Route::resource('role', RoleController::class);

   Route::post('user-demo', [\App\Http\Controllers\Admin\UserController::class, 'demo'])->name('checkbok');

    Route::delete('remove-media/{media}', function (MediaAlias $media) {
        $media->delete();
        return back()->with('success', 'Media successfully deleted.');
    })->name('remove-media');
});
