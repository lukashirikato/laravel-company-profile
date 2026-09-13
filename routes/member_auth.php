<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberAuthController;

// ============================
// Autentikasi Member (Login / Register / Logout)
// ============================

// Tampilkan form login
Route::get('/member/login', [MemberAuthController::class, 'showLoginForm'])->name('member.login');
Route::get('/member/login-alias', [MemberAuthController::class, 'showLoginForm'])->name('member.login.form');

// Proses login
Route::post('/member/login', [MemberAuthController::class, 'login']);

// Tampilkan form register
Route::get('/member/register', [MemberAuthController::class, 'showRegisterForm'])->name('member.register');

// Proses register
Route::post('/member/register', [MemberAuthController::class, 'register']);

// Proses logout
Route::post('/member/logout', [MemberAuthController::class, 'logout'])->name('member.logout');
