<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    /**
     * Listado de noticias del club.
     */
    public function index()
    {
         $clubId = auth()->user()->club_id;

        $noticias = Noticia::where('club_id', $clubId)
            ->orderByDesc('fecha_publicacion')
            ->orderByDesc('id')
            ->get();

        return view('noticias.index', compact('noticias'));
    }

    /**
     * Formulario para crear noticia.
     */
    public function create()
    {
        return view('noticias.create');
    }

    /**
     * Guardar noticia.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'fecha_publicacion' => 'nullable|date',
        ]);

        $rutaImagen = null;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')
                ->store('noticias', 'public');
        }

        Noticia::create([
             'club_id' => auth()->user()->club_id,
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'imagen' => $rutaImagen,
            'fecha_publicacion' => $request->fecha_publicacion ?? now()->toDateString(),
            'publicada' => true,
        ]);

        return redirect()
            ->route('noticias.index')
            ->with('success', 'Noticia publicada correctamente.');
    }

    /**
     * Mostrar noticia.
     */
    public function show(Noticia $noticia)
    {
       if ($noticia->club_id != auth()->user()->club_id) {
    abort(403);
}

        return view('noticias.show', compact('noticia'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Noticia $noticia)
    {
        if ($noticia->club_id != auth()->user()->club_id) {
    abort(403);
}

        return view('noticias.edit', compact('noticia'));
    }

    /**
     * Actualizar noticia.
     */
    public function update(Request $request, Noticia $noticia)
    {
       if ($noticia->club_id != auth()->user()->club_id) {
    abort(403);
}

        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'fecha_publicacion' => 'nullable|date',
        ]);

        $datos = [
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'fecha_publicacion' => $request->fecha_publicacion,
            'publicada' => $request->boolean('publicada'),
        ];

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')
                ->store('noticias', 'public');
        }

        $noticia->update($datos);

        return redirect()
            ->route('noticias.index')
            ->with('success', 'Noticia actualizada correctamente.');
    }

    /**
     * Eliminar noticia.
     */
    public function destroy(Noticia $noticia)
    {
        if ($noticia->club_id != auth()->user()->club_id) {
    abort(403);
}

        $noticia->delete();

        return redirect()
            ->route('noticias.index')
            ->with('success', 'Noticia eliminada correctamente.');
    }
}