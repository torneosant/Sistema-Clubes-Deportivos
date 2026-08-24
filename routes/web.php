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
use App\Http\Controllers\DocumentoJugadorController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\RegistroClubController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\InscripcionController;




// Página inicial pública
Route::get('/', function () {
    return view('inicio');
})->name('inicio');

// Registro público de clubes
Route::get('/registro-club', [RegistroClubController::class, 'create'])
    ->middleware('guest')
    ->name('registro.club');

Route::post('/registro-club', [RegistroClubController::class, 'store'])
    ->middleware('guest')
    ->name('registro.club.store');



Route::middleware(['auth'])->group(function () {

    
    // ===========================
    // Dashboard
    // ===========================
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permiso:dashboard.ver')
        ->name('dashboard');

    // ===========================
    // Club
    // ===========================
    Route::get('/club', [ClubController::class, 'index'])
        ->middleware('permiso:club.ver')
        ->name('club.index');

    Route::post('/club', [ClubController::class, 'store'])
        ->middleware('permiso:club.crear')
        ->name('club.store');


    // ===========================
    // Jugadores
    // ===========================

    Route::get('/jugadores/exportar/excel', [JugadorController::class, 'exportExcel'])
        ->middleware('permiso:jugadores.ver')
        ->name('jugadores.exportExcel');

    Route::get('/jugadores/print', [JugadorController::class, 'print'])
        ->middleware('permiso:jugadores.ver')
        ->name('jugadores.print');

    Route::get(
    '/jugadores/plantilla',
    [JugadorController::class, 'plantillaExcel']
)
->middleware('permiso:jugadores.crear')
->name('jugadores.plantilla');    

    Route::get('/jugadores/pdf', [JugadorController::class, 'pdf'])
        ->middleware('permiso:jugadores.ver')
        ->name('jugadores.pdf');

    Route::post(
    '/jugadores/importar',
    [JugadorController::class, 'importar']
)
->middleware('permiso:jugadores.crear')
->name('jugadores.importar');


    Route::resource('jugadores', JugadorController::class)
        ->parameters([
            'jugadores' => 'jugador'
        ])
        ->middleware('permiso:jugadores');

    Route::patch('/jugadores/{jugador}/estado', [JugadorController::class, 'cambiarEstado'])
        ->middleware('permiso:jugadores.editar')
        ->name('jugadores.estado');

// ==========================================
// Documentos del Jugador
// ==========================================

Route::get(
    '/jugadores/{jugador}/documentos',
    [DocumentoJugadorController::class, 'index']
)
->middleware('permiso:jugadores.editar')
->name('jugadores.documentos');

Route::post(
    '/jugadores/{jugador}/documentos',
    [DocumentoJugadorController::class, 'store']
)
->middleware('permiso:jugadores.editar')
->name('jugadores.documentos.store');

Route::delete(
    '/jugadores/documentos/{documento}',
    [DocumentoJugadorController::class, 'destroy']
)
->middleware('permiso:jugadores.editar')
->name('jugadores.documentos.destroy');



        
    // Categorías

    // ===========================
// Categorías
// ===========================

Route::resource('categorias', CategoriaController::class)
    ->parameters([
        'categorias' => 'categoria'
    ])
    ->middleware('permiso:categorias');

Route::patch(
    '/categorias/{categoria}/estado',
    [CategoriaController::class, 'cambiarEstado']
)
    ->middleware('permiso:categorias.editar')
    ->name('categorias.estado');

Route::get(
    '/equipos/categoria/{categoria}',
    [EquipoController::class, 'porCategoria']
)
    ->middleware('permiso:equipos.ver')
    ->name('equipos.categoria');

Route::get(
    '/equipo/{equipo}/categorias',
    [CategoriaController::class, 'porEquipo']
)
    ->middleware('permiso:categorias.ver')
    ->name('categorias.equipo');


        
// ===========================
// Equipos
// ===========================

Route::resource('equipos', EquipoController::class)
    ->parameters([
        'equipos' => 'equipo'
    ])
    ->middleware('permiso:equipos');

Route::patch(
    '/equipos/{equipo}/estado',
    [EquipoController::class, 'cambiarEstado']
)
    ->middleware('permiso:equipos.editar')
    ->name('equipos.estado');


 // ===========================
// Entrenadores
// ===========================

Route::get(
    'entrenadores/exportar/excel',
    [EntrenadorController::class, 'exportExcel']
)
    ->middleware('permiso:entrenadores.ver')
    ->name('entrenadores.exportExcel');


Route::get(
    'entrenadores/pdf',
    [EntrenadorController::class, 'pdf']
)
    ->middleware('permiso:entrenadores.ver')
    ->name('entrenadores.pdf');

Route::patch(
    'entrenadores/{entrenador}/estado',
    [EntrenadorController::class, 'cambiarEstado']
)
    ->middleware('permiso:entrenadores.editar')
    ->name('entrenadores.estado');

Route::resource('entrenadores', EntrenadorController::class)
    ->parameters([
        'entrenadores' => 'entrenador'
    ])
    ->middleware('permiso:entrenadores');
    
    
    
    // ===========================
// Entrenamientos
// ===========================

Route::resource('entrenamientos', EntrenamientoController::class)
    ->parameters([
        'entrenamientos' => 'entrenamiento'
    ])
    ->middleware('permiso:entrenamientos');

Route::patch(
    'entrenamientos/{entrenamiento}/estado',
    [EntrenamientoController::class, 'cambiarEstado']
)
    ->middleware('permiso:entrenamientos.editar')
    ->name('entrenamientos.estado');


// ===========================
// Asistencia
// ===========================

Route::get(
    '/entrenamientos/{entrenamiento}/asistencia',
    [AsistenciaController::class, 'create']
)
    ->middleware('permiso:asistencias.ver')
    ->name('asistencias.create');

Route::post(
    '/entrenamientos/{entrenamiento}/asistencia',
    [AsistenciaController::class, 'store']
)
    ->middleware('permiso:asistencias.crear')
    ->name('asistencias.store');

Route::post(
    '/asistencias/{entrenamiento}',
    [AsistenciaController::class, 'store']
)
    ->middleware('permiso:asistencias.crear')
    ->name('asistencias.store');

Route::get(
    '/entrenamientos/{entrenamiento}/asistencia/pdf',
    [AsistenciaController::class,'pdf']
)
    ->middleware('permiso:asistencias.ver')
    ->name('asistencias.pdf');

Route::get(
    '/entrenamientos/{entrenamiento}/asistencia/excel',
    [AsistenciaController::class,'excel']
)
    ->middleware('permiso:asistencias.ver')
    ->name('asistencias.excel');

Route::get(
    '/entrenamientos/{entrenamiento}/asistencia/imprimir',
    [AsistenciaController::class,'imprimir']
)
    ->middleware('permiso:asistencias.ver')
    ->name('asistencias.imprimir');


// Noticias
// ===========================

Route::resource('noticias', NoticiaController::class)
    ->parameters([
        'noticias' => 'noticia'
    ])
    ->middleware('permiso:noticias');




 // ===========================
// Partidos
// ===========================

Route::resource('partidos', PartidoController::class)
    ->middleware('permiso:partidos');

Route::get(
    '/partidos/{partido}/estadisticas',
    [PartidoJugadorController::class, 'create']
)
    ->middleware('permiso:partidos.ver')
    ->name('partidos.estadisticas');

Route::post(
    '/partidos/{partido}/estadisticas',
    [PartidoJugadorController::class, 'store']
)
    ->middleware('permiso:partidos.editar')
    ->name('partidos.estadisticas.store');

Route::get(
    '/partidos/{partido}/resultado',
    [PartidoController::class, 'resultado']
)
    ->middleware('permiso:partidos.ver')
    ->name('partidos.resultado');

Route::post(
    '/partidos/{partido}/resultado',
    [PartidoController::class, 'guardarResultado']
)
    ->middleware('permiso:partidos.editar')
    ->name('partidos.resultado.store');


 // ===========================
// Contabilidad
// ===========================

Route::resource('contabilidad', ContabilidadController::class)
    ->middleware('permiso:contabilidad');


// ===========================
// Conceptos Contables
// ===========================

Route::resource('conceptos-contables', ConceptoContableController::class)
    ->middleware('permiso:conceptos_contables');


// ===========================
// Calendario
// ===========================

Route::get(
    '/calendario',
    [CalendarioController::class,'index']
)
    ->middleware('permiso:calendario.ver')
    ->name('calendario.index');


    // ===========================
// // ===========================
// Historial Médico
// ===========================

Route::get(
    '/historial-medico',
    [HistorialMedicoController::class,'index']
)
    ->middleware('permiso:historial-medico.ver')
    ->name('historial-medico.index');

Route::get(
    '/historial-medico/create',
    [HistorialMedicoController::class,'create']
)
    ->middleware('permiso:historial-medico.crear')
    ->name('historial-medico.create');

Route::post(
    '/historial-medico',
    [HistorialMedicoController::class,'store']
)
    ->middleware('permiso:historial-medico.crear')
    ->name('historial-medico.store');

Route::get(
    '/historial-medico/{historial}/edit',
    [HistorialMedicoController::class,'edit']
)
    ->middleware('permiso:historial-medico.editar')
    ->name('historial-medico.edit');

Route::put(
    '/historial-medico/{historial}',
    [HistorialMedicoController::class,'update']
)
    ->middleware('permiso:historial-medico.editar')
    ->name('historial-medico.update');

Route::delete(
    '/historial-medico/{historial}',
    [HistorialMedicoController::class,'destroy']
)
    ->middleware('permiso:historial-medico.eliminar')
    ->name('historial-medico.destroy');



 // ===========================
// Configuración
// ===========================

Route::prefix('configuracion')
    ->group(function () {

    // General
    Route::get('/general', [ConfiguracionController::class,'general'])
        ->middleware('permiso:configuracion.ver')
        ->name('configuracion.general');

    Route::put('/general', [ConfiguracionController::class,'updateGeneral'])
        ->middleware('permiso:configuracion.editar')
        ->name('configuracion.updateGeneral');

    // Redes
    Route::get('/redes', [ConfiguracionController::class,'redes'])
        ->middleware('permiso:configuracion.ver')
        ->name('configuracion.redes');

    Route::put('/redes', [ConfiguracionController::class,'updateRedes'])
        ->middleware('permiso:configuracion.editar')
        ->name('configuracion.updateRedes');

    // Deportivo
    Route::get('/deportivo', [ConfiguracionController::class,'deportivo'])
        ->middleware('permiso:configuracion.ver')
        ->name('configuracion.deportivo');

    Route::put('/deportivo', [ConfiguracionController::class,'updateDeportivo'])
        ->middleware('permiso:configuracion.editar')
        ->name('configuracion.updateDeportivo');

    // Sistema
    Route::get('/sistema', [ConfiguracionController::class,'sistema'])
        ->middleware('permiso:configuracion.ver')
        ->name('configuracion.sistema');

    Route::put('/sistema', [ConfiguracionController::class,'updateSistema'])
        ->middleware('permiso:configuracion.editar')
        ->name('configuracion.updateSistema');



     // ===========================
// Usuarios
// ===========================

Route::resource('usuarios', UsuarioController::class)
    ->middleware('permiso:usuarios');

Route::patch(
    'usuarios/{usuario}/estado',
    [UsuarioController::class, 'cambiarEstado']
)
    ->middleware('permiso:usuarios.editar')
    ->name('usuarios.estado');


   
    // ===========================
// Roles
// ===========================

Route::resource('roles', RolController::class)
    ->names('roles')
    ->middleware('permiso:roles');
    
}); 

Route::patch(
    'usuarios/{usuario}/estado',
    [UsuarioController::class, 'cambiarEstado']
)
    ->middleware('permiso:usuarios.editar')
    ->name('usuarios.estado');


// ===========================
// Documentación
// ===========================

Route::resource('tipos-documento', TipoDocumentoController::class)
    ->middleware(['auth','permiso:documentacion']);

Route::resource('documentos', DocumentoController::class)
    ->except(['edit','update'])
    ->middleware(['auth','permiso:documentacion']);


// ===========================
// Tipos de artículo
// ===========================

Route::resource('tipos-articulo', TipoArticuloController::class)
    ->middleware('permiso:tipos_articulo');


// ===========================
// Inventario
// ===========================

Route::resource('inventario', InventarioController::class)
    ->middleware('permiso:inventario');

Route::get(
    'inventario/{inventario}/trazabilidad',
    [InventarioController::class, 'trazabilidad']
)
    ->middleware('permiso:inventario.ver')
    ->name('inventario.trazabilidad');

Route::get(
    'inventario-excel',
    [InventarioController::class,'excel']
)
    ->middleware('permiso:inventario.ver')
    ->name('inventario.excel');


// ===========================
// Asignaciones Inventario
// ===========================

Route::resource('asignaciones-inventario', AsignacionInventarioController::class)
    ->middleware('permiso:asignaciones_inventario');

Route::post(
    'asignaciones-inventario/{asignaciones_inventario}/devolver',
    [AsignacionInventarioController::class,'devolver']
)
    ->middleware('permiso:asignaciones_inventario.editar')
    ->name('asignaciones-inventario.devolver');

Route::get(
    'asignaciones-inventario-excel',
    [AsignacionInventarioController::class,'excel']
)
    ->middleware('permiso:asignaciones_inventario.ver')
    ->name('asignaciones-inventario.excel');


// ===========================
// Módulos
// ===========================

Route::resource('modulos', App\Http\Controllers\ModuloController::class)
    ->middleware('permiso:modulos');

    // Perfil del usuario
Route::get('/perfil', [PerfilController::class, 'index'])
    ->middleware('auth')
    ->name('perfil');

// Cambiar contraseña
Route::get('/perfil/cambiar-contrasena', [PerfilController::class, 'password'])
    ->middleware('auth')
    ->name('perfil.password');

Route::put('/perfil/cambiar-contrasena', [PerfilController::class, 'updatePassword'])
    ->middleware('auth')
    ->name('perfil.password.update');

// Inscripciones
Route::resource(
    'inscripciones',
    InscripcionController::class
);


});

require __DIR__.'/auth.php';