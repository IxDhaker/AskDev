<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReponseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();



// Question routes
// Explicitly redirect the index route to home
Route::get('/questions', function () {
    return redirect()->route('home');
})->name('questions.index');

Route::resource('questions', QuestionController::class)->except(['index']);

// Response routes
Route::post('/questions/{question}/reponses', [ReponseController::class, 'store'])->name('reponses.store');
Route::get('/reponses/{reponse}', [ReponseController::class, 'show'])->name('reponses.show');
Route::get('/reponses/{reponse}/edit', [ReponseController::class, 'edit'])->name('reponses.edit');
Route::put('/reponses/{reponse}', [ReponseController::class, 'update'])->name('reponses.update');
Route::delete('/reponses/{reponse}', [ReponseController::class, 'destroy'])->name('reponses.destroy');

// User profile routes
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
Route::get('/users/{user}/archive/questions', [UserController::class, 'archiveQuestions'])->name('users.archive.questions');
Route::get('/users/{user}/archive/reponses', [UserController::class, 'archiveReponses'])->name('users.archive.reponses');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.delete');
Route::post('/users/check-password', [UserController::class, 'checkPassword'])->name('users.check-password');

// Notification routes
Route::get('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.readAll');
Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');

// Vote routes
Route::post('/questions/{question}/vote', [VoteController::class, 'vote'])->name('votes.vote');
Route::delete('/questions/{question}/vote', [VoteController::class, 'removeVote'])->name('votes.remove');
Route::post('/reponses/{reponse}/vote', [VoteController::class, 'voteReponse'])->name('reponses.vote');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Question moderation
    Route::get('/questions', [AdminController::class, 'questions'])->name('questions');
    Route::put('/questions/{question}/status', [AdminController::class, 'updateQuestionStatus'])->name('questions.status');
});
