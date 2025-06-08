@extends('layouts.app')
@section('title', 'Mi Perfil')

@section('content')
<div class="container py-4">
    <h2>👤 Mi Perfil</h2>
    <p><strong>Nombre:</strong> {{ $usuario->name }}</p>
    <p><strong>Correo:</strong> {{ $usuario->email }}</p>
    <p><strong>Rol:</strong> {{ ucfirst($usuario->rol) }}</p>
    <p><strong>Desde:</strong> {{ $usuario->created_at->format('d/m/Y') }}</p>

    <a href="{{ route('perfil.edit') }}" class="btn btn-warning mt-3">✏️ Editar perfil</a>
</div>
@endsection
