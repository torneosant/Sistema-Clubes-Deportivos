@extends('layouts.app')

@section('contenido')

<div class="max-w-5xl mx-auto">

<form action="{{ route('roles.store') }}" method="POST">

@include('configuracion.roles._form')

</form>

</div>

@endsection 