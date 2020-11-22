@extends('layouts.app')

@section('botones')
    @include('ui.navegacion')
@endsection

@section('content')
    <h2 class="text-center mb-5">Recetas</h2>

    {{-- {{$recetas}} --}}

    <div class="col-md-10 mx-auto bg-white p-3">
        <table class="table">
            <thead class="bg-primary text-light">
                <tr>
                    <th scope="col">Titulo</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($recetas as $receta)
                    <tr>
                        <td> {{$receta->titulo}} </td>
                        <td> {{$receta->categoria->nombre}} </td>
                        <td> 
                            <eliminar-receta
                                receta-id={{$receta->id}}
                            ></eliminar-receta>
                           
                            <a href="{{route('recetas.edit', ['receta' => $receta->id])}}" class="btn btn-dark mr-1 d-block mb-2">Editar</a>
                            <a href="{{route('recetas.show', ['receta' => $receta->id])}}" class="btn btn-success mr-1 d-block mb-2">Ver</a>
                        </td>
                    </tr> 
                @endforeach

            </tbody>
        </table>

        <div class="col-12 mt-4 justify-content-center d-flex">
            {{$recetas->links()}}
        </div>

        {{-- {{$usuario->meGusta}} --}}

        <h2 class="text-center my-5">Recetas que te gustan</h2>
        <div class="col-md-10 mx-auto bg-white p-3">
            @if (count($usuario->meGusta) > 0)
                
            
            <ul class="list-group">
                @foreach ($usuario->meGusta as $receta)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{$receta->titulo}}

                        <a class="btn btn-outline-success" href="{{route('recetas.show', ['receta' => $receta->id])}}">Ver</a>
                    </li>
                @endforeach
            </ul>
            @else
                <p class="text-center">Aun no tienes recetas guardadas 
                    <small>Dale me gusta a las recetas y apareceran aqui</small>
                </p>

            @endif
        </div>
        
    </div>



@endsection