<?php

declare(strict_types = 1);

use App\Enums\Can;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('login/{id}', function (int $id) {
    return Illuminate\Support\Facades\Auth::loginUsingId($id);
});

Route::get('permission/{permission}/{user}', function (int $permission, User $user) {
    $permission = App\Models\Permission::query()->find($permission);
    $user->permissions()->attach($permission);
    return $permission;
})->middleware('throttle:10,1');

Route::get('user/{user}', fn (User $user): User => $user)->can(Can::ViewUser);
