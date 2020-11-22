@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.css" integrity="sha512-494Ejp/5WyoRNfh+nPLhSCQPHhcsbA5PoIGv2/FuEo+QLfW+L7JQGPdh8Qy2ZOmkF27pyYlALrxteMiKau1tyw==" crossorigin="anonymous" />
@endsection

@section('botones')
    <a href="{{route('recetas.index')}}" class="btn btn-outline-primary mr-2 text-uppercase font-weight-bold">
    <svg class="icono w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
     Volver </a>
@endsection

@section('content')
    <h2 class="text-center mb-5">Crear nueva receta</h2>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <form method="POST" action="{{route('recetas.store')}}" novalidate enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="titulo">Titulo Receta</label>

                    <input 
                        type="text" 
                        name="titulo" 
                        class="form-control @error('titulo') is-invalid @enderror" 
                        id="titulo" 
                        placeholder="Titulo Receta"
                        value="{{old('titulo')}}"
                    />

                    @error('titulo')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="categoria">Categoria</label>

                    <select
                        name="categoria"
                        class="form-control @error('categoria') is-invalid @enderror"
                        id="categoria"
                    >
                        <option value="">--Seleccione --</option>
                        @foreach ($categorias as $categoria)
                            <option 
                                value="{{$categoria->id}}" 
                                {{old('categoria' == $categoria->id ? 'selected' : '')}}
                                >{{$categoria->nombre}}
                            </option>
                        @endforeach
                        
                    </select>

                    @error('categoria')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="ingredientes">Ingredientes</label>
                    <input type="hidden" id="ingredientes" name="ingredientes" value="{{old('ingredientes')}}">
                    <trix-editor input="ingredientes" class="form-control @error('ingredientes') is-invalid @enderror"></trix-editor>

                    @error('ingredientes')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="preparacion">Preparacion</label>
                    <input id="preparacion" name="preparacion" value="{{old('preparacion')}}" type="hidden">
                    <trix-editor 
                        input="preparacion"
                        class="form-control @error('preparacion') is-invalid @enderror"
                        ></trix-editor>

                    @error('preparacion')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="imagen">Elige la imagen</label>

                    <input 
                        id="imagen" 
                        type="file" 
                        class="form-control @error('imagen') is-invalid @enderror"
                        name="imagen"    
                    >

                    @error('imagen')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group mt-5">
                    <input type="submit" class="btn btn-primary" value="Agregar Receta">
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.js" integrity="sha512-wEfICgx3CX6pCmTy6go+PmYVKDdi4KHhKKz5Xx/boKOZOtG7+rrm2fP7RUR2o4m/EbPdwbKWnP05dvj4uzoclA==" crossorigin="anonymous" defer></script>
    @endsection


@endsection