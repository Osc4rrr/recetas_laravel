<?php

namespace App\Http\Controllers;

use App\CategoriaReceta;
use App\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'search']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //auth()->user()->recetas->dd();

        //$meGusta = auth()->user()->meGusta();

        $usuario = auth()->user();
        //recetas con paginacion

        $recetas = Receta::where('user_id', $usuario->id)->paginate(10);


        //$recetas = auth()->user()->recetas;
        return view('recetas.index')->with('recetas', $recetas)->with('usuario', $usuario);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //DB::table('categoria_receta')->get()->pluck('nombre', 'id')->dd();

        //obtener las categorias (sin modelo)
        //$categorias =  DB::table('categoria_recetas')->get()->pluck('nombre', 'id');

        //con modelo
        $categorias = CategoriaReceta::all(['id', 'nombre']);
        return view('recetas.create')->with('categorias', $categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($request['imagen']->store('upload-recetas', 'public'));

        //validacion
        $data = request()->validate([
            'titulo' => 'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
            'imagen' => 'required|image'
        ]);
        //

        //ruta imagen
        $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');

        //resize de la imagen
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000, 550);
        $img->save();


        //almacenar en bd
        // DB::table('recetas')->insert([
        //     'titulo' => $data['titulo'],
        //     'preparacion' => $data['preparacion'],
        //     'ingredientes' => $data['ingredientes'],
        //     'imagen' => $ruta_imagen,
        //     'user_id' => Auth::user()->id,
        //     'categoria_id' => $data['categoria']
        // ]);

        //almacenar en bd con modelo

        auth()->user()->recetas()->create([
            'titulo' => $data['titulo'],
            'preparacion' => $data['preparacion'],
            'ingredientes' => $data['ingredientes'],
            'imagen' => $ruta_imagen,
            'categoria_id' => $data['categoria']
        ]);

        //redireccionar 
        return redirect()->action('RecetaController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {

        //obtener si el usuario actual le gusta la receta y esta autenticado
        $like = (auth()->user()) ? auth()->user()->meGusta->contains($receta->id) : false;
        //


        //cantidad de likes a la vista
        $likes = $receta->likes->count();

        return view('recetas.show', compact('receta', 'like', 'likes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        //
        $this->authorize('view', $receta);
        $categorias = CategoriaReceta::all(['id', 'nombre']);
        return view('recetas.edit', compact('categorias', 'receta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {

        //revisar el policy
        $this->authorize('update', $receta);

        //
        $data = request()->validate([
            'titulo' => 'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required'
        ]);

        //asignar valores
        $receta->titulo = $data['titulo'];
        $receta->categoria_id = $data['categoria'];
        $receta->preparacion = $data['preparacion'];
        $receta->ingredientes = $data['ingredientes'];

        //si el usuario sube nueva imagen
        if (request('imagen')) {
            //ruta imagen
            $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');

            //resize de la imagen
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000, 550);
            $img->save();

            //asignar al objeto
            $receta->imagen = $ruta_imagen;
        }

        $receta->save();

        //redireccionar 

        return redirect()->action('RecetaController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        //ejecutar el policy
        $this->authorize('delete', $receta);

        //eliminar la receta
        $receta->delete();

        return redirect()->action('RecetaController@index');
    }


    public function search(Request $request)
    {
        $busqueda = $request['buscar'];

        $recetas = Receta::where('titulo', 'like', '%' . $busqueda . '%')->paginate(5);
        $recetas->appends(['buscar' => $busqueda]);

        return view('busquedas.show', compact('recetas', 'busqueda'));
    }
}
