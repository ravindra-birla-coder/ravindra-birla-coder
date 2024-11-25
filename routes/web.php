<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JobsController;
use Illuminate\Support\Facades\Route;

Route::GET('/', [HomeController::class, 'index'])->name('home');
Route::GET('/jobs', [JobsController::class, 'index'])->name('jobs');

Route::group(['prefix' =>'account'], function()
{
    //guest routes
    Route::group(['middleware' => 'guest'] , function()
    {
       Route::GET('/register', [AccountController::class, 'registraion'])->name('account.registraion');
       Route::POST('/registraion-process', [AccountController::class, 'registraionProcess'])->name('account.registraionProcess');
       Route::GET('/login', [AccountController::class, 'login'])->name('account.login');
       Route::POST('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });

    //authenticate routes
    Route::group(['middleware' => 'auth'] , function()
    {
       Route::GET('/profile', [AccountController::class, 'profile'])->name('account.profile');
       Route::PUT('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
       Route::GET('/logout', [AccountController::class, 'logout'])->name('account.logout');
       Route::POST('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
       Route::GET('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
       Route::POST('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
       Route::GET('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
       Route::GET('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
       Route::POST('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
       Route::POST('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');

    });
 
});

