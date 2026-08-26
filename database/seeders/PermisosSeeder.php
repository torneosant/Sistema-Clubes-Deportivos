<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permiso;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [

            // =========================================================
            // DASHBOARD
            // =========================================================

            ['nombre' => 'Dashboard - Ver', 'slug' => 'dashboard.ver'],


            // =========================================================
            // CLUB
            // =========================================================

            ['nombre' => 'Club - Ver', 'slug' => 'club.ver'],
            ['nombre' => 'Club - Crear', 'slug' => 'club.crear'],
            ['nombre' => 'Club - Editar', 'slug' => 'club.editar'],
            ['nombre' => 'Club - Eliminar', 'slug' => 'club.eliminar'],


            // =========================================================
            // JUGADORES
            // =========================================================

            ['nombre' => 'Jugadores - Ver', 'slug' => 'jugadores.ver'],
            ['nombre' => 'Jugadores - Crear', 'slug' => 'jugadores.crear'],
            ['nombre' => 'Jugadores - Editar', 'slug' => 'jugadores.editar'],
            ['nombre' => 'Jugadores - Eliminar', 'slug' => 'jugadores.eliminar'],


            // =========================================================
            // CATEGORÍAS
            // =========================================================

            ['nombre' => 'Categorías - Ver', 'slug' => 'categorias.ver'],
            ['nombre' => 'Categorías - Crear', 'slug' => 'categorias.crear'],
            ['nombre' => 'Categorías - Editar', 'slug' => 'categorias.editar'],
            ['nombre' => 'Categorías - Eliminar', 'slug' => 'categorias.eliminar'],


            // =========================================================
            // EQUIPOS
            // =========================================================

            ['nombre' => 'Equipos - Ver', 'slug' => 'equipos.ver'],
            ['nombre' => 'Equipos - Crear', 'slug' => 'equipos.crear'],
            ['nombre' => 'Equipos - Editar', 'slug' => 'equipos.editar'],
            ['nombre' => 'Equipos - Eliminar', 'slug' => 'equipos.eliminar'],


            // =========================================================
            // ENTRENADORES
            // =========================================================

            ['nombre' => 'Entrenadores - Ver', 'slug' => 'entrenadores.ver'],
            ['nombre' => 'Entrenadores - Crear', 'slug' => 'entrenadores.crear'],
            ['nombre' => 'Entrenadores - Editar', 'slug' => 'entrenadores.editar'],
            ['nombre' => 'Entrenadores - Eliminar', 'slug' => 'entrenadores.eliminar'],


            // =========================================================
            // ENTRENAMIENTOS
            // =========================================================

            ['nombre' => 'Entrenamientos - Ver', 'slug' => 'entrenamientos.ver'],
            ['nombre' => 'Entrenamientos - Crear', 'slug' => 'entrenamientos.crear'],
            ['nombre' => 'Entrenamientos - Editar', 'slug' => 'entrenamientos.editar'],
            ['nombre' => 'Entrenamientos - Eliminar', 'slug' => 'entrenamientos.eliminar'],


            // =========================================================
            // ASISTENCIAS
            // =========================================================

            ['nombre' => 'Asistencias - Ver', 'slug' => 'asistencias.ver'],
            ['nombre' => 'Asistencias - Crear', 'slug' => 'asistencias.crear'],
            ['nombre' => 'Asistencias - Editar', 'slug' => 'asistencias.editar'],
            ['nombre' => 'Asistencias - Eliminar', 'slug' => 'asistencias.eliminar'],


            // =========================================================
            // PARTIDOS
            // =========================================================

            ['nombre' => 'Partidos - Ver', 'slug' => 'partidos.ver'],
            ['nombre' => 'Partidos - Crear', 'slug' => 'partidos.crear'],
            ['nombre' => 'Partidos - Editar', 'slug' => 'partidos.editar'],
            ['nombre' => 'Partidos - Eliminar', 'slug' => 'partidos.eliminar'],


            // =========================================================
            // CONTABILIDAD
            // =========================================================

            ['nombre' => 'Contabilidad - Ver', 'slug' => 'contabilidad.ver'],
            ['nombre' => 'Contabilidad - Crear', 'slug' => 'contabilidad.crear'],
            ['nombre' => 'Contabilidad - Editar', 'slug' => 'contabilidad.editar'],
            ['nombre' => 'Contabilidad - Eliminar', 'slug' => 'contabilidad.eliminar'],


            // =========================================================
            // CONCEPTOS CONTABLES
            // =========================================================

            ['nombre' => 'Conceptos Contables - Ver', 'slug' => 'conceptos_contables.ver'],
            ['nombre' => 'Conceptos Contables - Crear', 'slug' => 'conceptos_contables.crear'],
            ['nombre' => 'Conceptos Contables - Editar', 'slug' => 'conceptos_contables.editar'],
            ['nombre' => 'Conceptos Contables - Eliminar', 'slug' => 'conceptos_contables.eliminar'],


            // =========================================================
            // CALENDARIO
            // =========================================================

            ['nombre' => 'Calendario - Ver', 'slug' => 'calendario.ver'],
            ['nombre' => 'Calendario - Crear', 'slug' => 'calendario.crear'],
            ['nombre' => 'Calendario - Editar', 'slug' => 'calendario.editar'],
            ['nombre' => 'Calendario - Eliminar', 'slug' => 'calendario.eliminar'],


            // =========================================================
            // HISTORIAL MÉDICO
            // =========================================================

            ['nombre' => 'Historial Médico - Ver', 'slug' => 'historial-medico.ver'],
            ['nombre' => 'Historial Médico - Crear', 'slug' => 'historial-medico.crear'],
            ['nombre' => 'Historial Médico - Editar', 'slug' => 'historial-medico.editar'],
            ['nombre' => 'Historial Médico - Eliminar', 'slug' => 'historial-medico.eliminar'],


            // =========================================================
            // CONFIGURACIÓN
            // =========================================================

            ['nombre' => 'Configuración - Ver', 'slug' => 'configuracion.ver'],
            ['nombre' => 'Configuración - Editar', 'slug' => 'configuracion.editar'],


            // =========================================================
            // USUARIOS
            // =========================================================

            ['nombre' => 'Usuarios - Ver', 'slug' => 'usuarios.ver'],
            ['nombre' => 'Usuarios - Crear', 'slug' => 'usuarios.crear'],
            ['nombre' => 'Usuarios - Editar', 'slug' => 'usuarios.editar'],
            ['nombre' => 'Usuarios - Eliminar', 'slug' => 'usuarios.eliminar'],


            // =========================================================
            // ROLES
            // =========================================================

            ['nombre' => 'Roles - Ver', 'slug' => 'roles.ver'],
            ['nombre' => 'Roles - Crear', 'slug' => 'roles.crear'],
            ['nombre' => 'Roles - Editar', 'slug' => 'roles.editar'],
            ['nombre' => 'Roles - Eliminar', 'slug' => 'roles.eliminar'],


            // =========================================================
            // REPORTES
            // =========================================================

            ['nombre' => 'Reportes - Ver', 'slug' => 'reportes.ver'],
            ['nombre' => 'Reportes - Crear', 'slug' => 'reportes.crear'],


            // =========================================================
            // DOCUMENTACIÓN
            // =========================================================

            ['nombre' => 'Documentación - Ver', 'slug' => 'documentacion.ver'],
            ['nombre' => 'Documentación - Crear', 'slug' => 'documentacion.crear'],
            ['nombre' => 'Documentación - Editar', 'slug' => 'documentacion.editar'],
            ['nombre' => 'Documentación - Eliminar', 'slug' => 'documentacion.eliminar'],


            // =========================================================
            // TIPOS DE DOCUMENTO
            // =========================================================

            ['nombre' => 'Tipos de Documento - Ver', 'slug' => 'tipos_documento.ver'],
            ['nombre' => 'Tipos de Documento - Crear', 'slug' => 'tipos_documento.crear'],
            ['nombre' => 'Tipos de Documento - Editar', 'slug' => 'tipos_documento.editar'],
            ['nombre' => 'Tipos de Documento - Eliminar', 'slug' => 'tipos_documento.eliminar'],


            // =========================================================
            // INVENTARIO
            // =========================================================

            ['nombre' => 'Inventario - Ver', 'slug' => 'inventario.ver'],
            ['nombre' => 'Inventario - Crear', 'slug' => 'inventario.crear'],
            ['nombre' => 'Inventario - Editar', 'slug' => 'inventario.editar'],
            ['nombre' => 'Inventario - Eliminar', 'slug' => 'inventario.eliminar'],


            // =========================================================
            // TIPOS DE ARTÍCULOS
            // =========================================================

            ['nombre' => 'Tipos de Artículos - Ver', 'slug' => 'tipos_articulo.ver'],
            ['nombre' => 'Tipos de Artículos - Crear', 'slug' => 'tipos_articulo.crear'],
            ['nombre' => 'Tipos de Artículos - Editar', 'slug' => 'tipos_articulo.editar'],
            ['nombre' => 'Tipos de Artículos - Eliminar', 'slug' => 'tipos_articulo.eliminar'],


            // =========================================================
            // ASIGNACIONES DE INVENTARIO
            // =========================================================

            ['nombre' => 'Asignaciones de Inventario - Ver', 'slug' => 'asignaciones_inventario.ver'],
            ['nombre' => 'Asignaciones de Inventario - Crear', 'slug' => 'asignaciones_inventario.crear'],
            ['nombre' => 'Asignaciones de Inventario - Editar', 'slug' => 'asignaciones_inventario.editar'],
            ['nombre' => 'Asignaciones de Inventario - Eliminar', 'slug' => 'asignaciones_inventario.eliminar'],


            // =========================================================
            // NOTICIAS
            // =========================================================

            ['nombre' => 'Noticias - Ver', 'slug' => 'noticias.ver'],
            ['nombre' => 'Noticias - Crear', 'slug' => 'noticias.crear'],
            ['nombre' => 'Noticias - Editar', 'slug' => 'noticias.editar'],
            ['nombre' => 'Noticias - Eliminar', 'slug' => 'noticias.eliminar'],


            // =========================================================
            // INSCRIPCIONES
            // =========================================================

            ['nombre' => 'Inscripciones - Ver', 'slug' => 'inscripciones.ver'],
            ['nombre' => 'Inscripciones - Crear', 'slug' => 'inscripciones.crear'],
            ['nombre' => 'Inscripciones - Editar', 'slug' => 'inscripciones.editar'],
            ['nombre' => 'Inscripciones - Eliminar', 'slug' => 'inscripciones.eliminar'],
            ['nombre' => 'Inscripciones - Aprobar', 'slug' => 'inscripciones.aprobar'],
            ['nombre' => 'Inscripciones - Denegar', 'slug' => 'inscripciones.denegar'],

        ];

        foreach ($permisos as $permiso) {

            Permiso::updateOrCreate(
                ['slug' => $permiso['slug']],
                [
                    'nombre' => $permiso['nombre'],
                    'activo' => true,
                ]
            );

        }
    }
}