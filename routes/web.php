<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[Crud::class,'registro']);

Route::get('/loginview',[Crud::class,'loginview'])->name('loginview');

Route::post('/crearUser',[Crud::class,'crearUser'])->name('crearUser');

Route::post('/login',[Crud::class,'login'])->name('login');

Route::get('/usuarios', [Crud::class, 'traerUsuarios'])->name('usuarios.list');

Route::get('/cerrarsession',[Crud::class,'cerrarSesion'])->name('cerrarSesion');

Route::get('editar/{id}', [Crud::class, 'editar'])->name('usuario.editar');

Route::delete('eliminar/{id}', [Crud::class, 'eliminar'])->name('usuario.eliminar');