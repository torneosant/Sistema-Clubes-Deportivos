<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntrenadorController;
use App\Http\Controllers\EntrenamientoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\PartidoJugadorController;
use App\Http\Controllers\ConceptoContableController;
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\HistorialMedicoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;  
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\TipoArticuloController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\AsignacionInventarioController;


Route::get('/', function () {
    return view('inicio');
});

Route::middleware(['auth'])->group(function () {

 Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('permiso:dashboard')
    ->name('dashboard');

    Route::get('/club', [ClubController::class, 'index'])
    ->middleware('permiso:club')
    ->name('club.index');

Route::post('/club', [ClubController::class, 'store'])
    ->middleware('permiso:club')
    ->name('club.store');

    Route::get('/jugadores/exportar/excel', [JugadorController::class, 'exportExcel'])
    ->name('jugadores.exportExcel');
    
        Route::get('/jugadores/print', [JugadorController::class, 'print'])
    ->name('jugadores.print');

Route::get('/jugadores/pdf', [JugadorController::class, 'pdf'])
    ->name('jugadores.pdf');

    // Jugadores
    Route::resource('jugadores', JugadorController::class)
        ->parameters([
            'jugadores' => 'jugador'
        ])
        ->middleware('permiso:jugadores');
        

    Route::patch('/jugadores/{jugador}/estado', [JugadorController::class, 'cambiarEstado'])
        ->name('jugadores.estado');


    // Categorías
    Route::resource('categorias', CategoriaController::class)
        ->parameters([
            'categorias' => 'categoria'
        ])
->middleware('permiso:categorias');


    Route::patch('/categorias/{categoria}/estado', [CategoriaController::class, 'cambiarEstado'])
        ->name('categorias.estado');

        Route::get('/equipos/categoria/{categoria}', [EquipoController::class, 'porCategoria'])
    ->name('equipos.categoria');

Route::get('/equipo/{equipo}/categorias', [CategoriaController::class, 'porEquipo'])
    ->name('categorias.equipo');

        // Equipos
Route::resource('equipos', EquipoController::class)
    ->parameters([
        'equipos' => 'equipo'
    ])
    ->middleware('permiso:equipos');

Route::patch('/equipos/{equipo}/estado', [EquipoController::class, 'cambiarEstado'])
    ->name('equipos.estado');

    // Entrenadores

     Route::get('entrenadores/exportar/excel', [EntrenadorController::class, 'exportExcel'])
    ->name('entrenadores.exportExcel');

Route::get('entrenadores/print', [EntrenadorController::class, 'print'])
    ->name('entrenadores.print');
    Route::get(
    'entrenadores/pdf',
    [EntrenadorController::class, 'pdf']
)->name('entrenadores.pdf');


Route::patch('entrenadores/{entrenador}/estado', [EntrenadorController::class, 'cambiarEstado'])
    ->name('entrenadores.estado');


Route::resource('entrenadores', EntrenadorController::class)
    ->parameters([
        'entrenadores' => 'entrenador'
    ])
    ->middleware('permiso:entrenadores');
    
    
    // Entrenamientos
Route::resource('entrenamientos', EntrenamientoController::class)
    ->parameters([
        'entrenamientos' => 'entrenamiento'
    ])

    ->middleware('permiso:entrenamientos');


    Route::patch(
    'entrenamientos/{entrenamiento}/estado',
    [EntrenamientoController::class, 'cambiarEstado']
)->name('entrenamientos.estado');

Route::get(
    '/entrenamientos/{entrenamiento}/asistencia',
    [AsistenciaController::class, 'create']
)->name('asistencias.create');

Route::post(
    '/entrenamientos/{entrenamiento}/asistencia',
    [AsistenciaController::class, 'store']
)->name('asistencias.store');

Route::post('/asistencias/{entrenamiento}', [AsistenciaController::class, 'store'])
    ->name('asistencias.store');

    Route::get('/entrenamientos/{entrenamiento}/asistencia/pdf',
    [AsistenciaController::class,'pdf'])
    ->name('asistencias.pdf');

Route::get('/entrenamientos/{entrenamiento}/asistencia/excel',
    [AsistenciaController::class,'excel'])
    ->name('asistencias.excel');

Route::get('/entrenamientos/{entrenamiento}/asistencia/imprimir',
    [AsistenciaController::class,'imprimir'])
    ->name('asistencias.imprimir');

    Route::get(
    '/entrenamientos/{entrenamiento}/asistencia/excel',
    [AsistenciaController::class, 'excel']
)->name('asistencias.excel');


 // Partidos
Route::resource('partidos', PartidoController::class)
->middleware('permiso:partidos');

Route::get(
    '/partidos/{partido}/estadisticas',
    [PartidoJugadorController::class, 'create']
)->name('partidos.estadisticas');

Route::post(
    '/partidos/{partido}/estadisticas',
    [PartidoJugadorController::class, 'store']
)->name('partidos.estadisticas.store');

Route::get(
    '/partidos/{partido}/resultado',
    [PartidoController::class, 'resultado']
)->name('partidos.resultado');

Route::post(
    '/partidos/{partido}/resultado',
    [PartidoController::class, 'guardarResultado']
)->name('partidos.resultado.store');

 // Contabilidad

Route::resource('contabilidad', ContabilidadController::class)
->middleware('permiso:contabilidad');

 // Conceptos contables

Route::resource('conceptos-contables', ConceptoContableController::class)
->middleware('permiso:conceptos_contables');

// Calendario
Route::get('/calendario', [CalendarioController::class,'index'])
    ->middleware('permiso:calendario')
    ->name('calendario.index');

    
    Route::get('/historial-medico', [HistorialMedicoController::class,'index'])
    ->name('historial-medico.index')
    ->middleware('permiso:historial_medico');

Route::get('/historial-medico/create', [HistorialMedicoController::class,'create'])
    ->name('historial-medico.create')
    ->middleware('permiso:historial_medico');

Route::post('/historial-medico', [HistorialMedicoController::class,'store'])
    ->name('historial-medico.store')
->middleware('permiso:historial_medico');

Route::get('/historial-medico/{historial}/edit', [HistorialMedicoController::class,'edit'])
    ->name('historial-medico.edit')
    ->middleware('permiso:historial_medico');

Route::put('/historial-medico/{historial}', [HistorialMedicoController::class,'update'])
    ->name('historial-medico.update')
    ->middleware('permiso:historial_medico');

Route::delete('/historial-medico/{historial}', [HistorialMedicoController::class,'destroy'])
    ->name('historial-medico.destroy')
    ->middleware('permiso:historial_medico');

 Route::prefix('configuracion')
    ->middleware('permiso:configuracion')
    ->group(function () {

    Route::get('/general', [ConfiguracionController::class,'general'])->name('configuracion.general');
    Route::put('/general', [ConfiguracionController::class,'updateGeneral'])->name('configuracion.updateGeneral');

    Route::get('/redes', [ConfiguracionController::class,'redes'])->name('configuracion.redes');
    Route::put('/redes', [ConfiguracionController::class,'updateRedes'])->name('configuracion.updateRedes');

    Route::get('/deportivo', [ConfiguracionController::class,'deportivo'])->name('configuracion.deportivo');
    Route::put('/deportivo', [ConfiguracionController::class,'updateDeportivo'])->name('configuracion.updateDeportivo');

    Route::get('/sistema', [ConfiguracionController::class,'sistema'])->name('configuracion.sistema');
    Route::put('/sistema', [ConfiguracionController::class,'updateSistema'])->name('configuracion.updateSistema');


     // usuarios
    Route::resource('usuarios', UsuarioController::class)
    ->middleware('permiso:usuarios');

   
    // Roles
    Route::resource('roles', RolController::class)
    ->names('roles')
    ->middleware('permiso:roles');

    
}); 

Route::patch(
    'usuarios/{usuario}/estado',
    [UsuarioController::class, 'cambiarEstado']
)->name('usuarios.estado'); 

Route::resource('tipos-documento', TipoDocumentoController::class)
    ->middleware(['auth','permiso:documentacion']);

Route::resource('documentos', DocumentoController::class)
    ->except(['edit','update'])
    ->middleware(['auth','permiso:documentacion']);

    Route::resource('tipos-articulo', TipoArticuloController::class)
    ->middleware('permiso:tipos_articulo');

Route::resource('inventario', InventarioController::class)
    ->middleware('permiso:inventario');

Route::resource('asignaciones-inventario', AsignacionInventarioController::class)
    ->middleware('permiso:asignaciones_inventario');

    Route::post(
    'asignaciones-inventario/{asignaciones_inventario}/devolver',
    [AsignacionInventarioController::class,'devolver']
)->name('asignaciones-inventario.devolver');

Route::get(
    'inventario/{inventario}/trazabilidad',
    [InventarioController::class, 'trazabilidad']
)->name('inventario.trazabilidad');

Route::get(
    'inventario-excel',
    [InventarioController::class,'excel']
)->name('inventario.excel');

Route::get(
    'asignaciones-inventario-excel',
    [AsignacionInventarioController::class,'excel']
)->name('asignaciones-inventario.excel');

});

require __DIR__.'/auth.php';