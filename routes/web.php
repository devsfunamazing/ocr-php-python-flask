<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PythonIntegrationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PythonIntegrationController::class, 'index'])->name('index');

Route::post('/convert-pdf', [PythonIntegrationController::class, 'convertPdf'])->name('convert.pdf');
Route::post('/convert-pdfs', [PythonIntegrationController::class, 'convertPdfs'])->name('convert.pdfs');

