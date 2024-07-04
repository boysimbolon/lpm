<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PushStandar;
use App\Http\Controllers\UpdateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\TipeAuditController;
use App\Http\Controllers\StandarController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PerStandarController;

Route::get('/push',[PushStandar::class,'pushing']);

Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verify',[TipeAuditController::class, 'verify']); // true
    Route::get('/audits/{idUser}', [AuditController::class, 'myAudit']); // true
    Route::get('/DetailAudit/{idTipe}/{idStandar}', [AuditController::class, 'DetailAudit']); // true
    Route::get('/mystatement/{idTipeAudit}/{idStandar}', [AuditController::class, 'mystatement']); // true
    Route::get('/audits/{idTipeAudit}/{idStandar}', [AuditController::class, 'show']); // true
    Route::delete('/remove/{audit}/{index}', [AuditController::class, 'hapusFile']); // true
    Route::delete('/uncheck/{perStandar}', [AuditController::class, 'batalkan']); // true
    Route::delete('/allUncheck/{user}/{tipe}/{standar}', [AuditController::class, 'Allbatalkan']); // true
    Route::post('/checkit', [AuditController::class, 'sent']); // true
// Endpoint untuk standar
    Route::post('/standars', [StandarController::class, 'store']);
    Route::get('/standars/{standar}', [StandarController::class, 'show']);
    Route::put('/standars/{standar}', [StandarController::class, 'update']);
    Route::delete('/standars/{standar}', [StandarController::class, 'destroy']);

// Endpoint untuk tipe audit
    Route::get('/tipeAudits', [TipeAuditController::class, 'index']); // true
    Route::post('/tipeAudits', [TipeAuditController::class, 'store']); // true
    Route::post('/salinAudits', [TipeAuditController::class, 'salin']); // true
    Route::post('/assign', [TipeAuditController::class, 'assign']); // true
    Route::post('/batalAssign', [TipeAuditController::class, 'batalAssign']); // true
    Route::get('/myTipeAudit/{tipeAudit}', [TipeAuditController::class, 'MyTipe']); // true
    Route::get('/MyTipeAudits/{tipeAudit}', [TipeAuditController::class, 'MyTipes']); // true
    Route::put('/tipeAudits/{tipeAudit}', [TipeAuditController::class, 'update']); // true
    Route::delete('/tipeAudits/{tipeAudit}', [TipeAuditController::class, 'destroy']); // true

// Endpoint untuk role
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{role}', [RoleController::class, 'show']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);

// Endpoint untuk fakultas
    Route::get('/fakultas', [FakultasController::class, 'index']); // true
    Route::put('/fakultas/{fakultas}', [FakultasController::class, 'update']); // true
    Route::delete('/fakultas/{fakultas}', [FakultasController::class, 'destroy']); // true

    Route::get('/users/{user}', [UsersController::class, 'show']); // true
    Route::put('/user/{user}', [UsersController::class, 'MyUpdate']); // true
    Route::get('/per-standars', [PerStandarController::class, 'index']); // true
    Route::get('/Standars/{idTipeAudit}', [PerStandarController::class, 'Standars']); // true
    Route::get('/perstandars/{standar}', [PerStandarController::class, 'myStandar']); // true
    Route::get('/my-standars/{idUser}/{idTipe}', [PerStandarController::class, 'my_standars']); // true
    Route::post('/perStandars', [PerStandarController::class, 'Input']); // true
    Route::get('/per-standars/{tipeaudit}/{standar}', [PerStandarController::class, 'show']); // true
    Route::put('/per-standars/{perStandar}', [PerStandarController::class, 'update']); // true
    Route::post('/logout', [LoginController::class, 'logout']); // true

    Route::post('/audits/{audit}', [AuditController::class, 'update']); // true
    Route::put('/audits/{idTipeAudit}/{idStandar}', [AuditController::class, 'kunci']); // true
    Route::get('/audits/{idTipeAudit}/{idfakultas}/{idStandar}', [AuditController::class, 'AuditStart']); // true
    Route::put('/OpenAudit/{idTipeAudit}/{idUser}/{idStandar}', [AuditController::class, 'Buka']); //true
    Route::get('/audits', [AuditController::class, 'index']); // true
    Route::get('/Myauditi/{idUser}', [AuditController::class, 'myAuditor']); // true
    Route::get('/standars', [StandarController::class, 'index']); // true
    Route::delete('/per-standars/{perStandar}', [PerStandarController::class, 'destroy']); // true
    Route::post('/fakultas', [FakultasController::class, 'store']); // true
    Route::get('/users', [UsersController::class, 'index']); // true
    Route::get('/userAuditor', [UsersController::class, 'userAuditor']); // true
    Route::delete('/users/{user}', [UsersController::class, 'destroy']); // true
    Route::put('/users/{user}', [UsersController::class, 'update']); // true
    Route::post('/users', [UsersController::class, 'store']); // true

    Route::get('/grafik/{Tipe}/{tahunA}/{tahunB}/{sem}',[AuditController::class,'grafik']);
    Route::get('/grafikAudit/{tahun1}/{tahun2}/{semester}',[AuditController::class,'grafikAudit']);
    Route::get('/grafikStandar/{idTipe}',[AuditController::class,'grafikStandar']);
    Route::get('/ViewStandarsLaporan/{idTipeAudit}', [AuditController::class, 'ViewStandar']);

});
