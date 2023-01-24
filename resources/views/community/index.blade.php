@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        {{-- Creo un archivo links.blade.php en la carpeta layouts para refactorizar el codigo y se llama aqui con @include ('layouts.links') --}}
        @include ('layouts.links')
        <div class="col-md-4">
             @include ('layouts.add-link')
        </div>
    </div>
    {{$links->links()}}
</div>
@stop
