@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>📦 Gestión de Pedidos</h1>
    <a href="{{ route('home') }}" class="btn btn-primary mb-3">🏠 Ir al inicio</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Cambiar Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->cliente->nombre ?? 'Cliente no disponible' }}</td>
                    <td>{{ ucfirst($pedido->estado) }}</td>
                    <td>
                        <form action="{{ route('admin.pedidos.cambiarEstado', $pedido->id) }}" method="POST">
                            @csrf
                            <select name="estado" class="form-select d-inline w-auto" onchange="this.form.submit()">
                                <option value="pendiente" {{ $pedido->estado === 'pendiente' ? 'selected' : '' }}>pendiente</option>
                                <option value="cocinándose" {{ $pedido->estado === 'cocinándose' ? 'selected' : '' }}>cocinándose</option>
                                <option value="empacándose" {{ $pedido->estado === 'empacándose' ? 'selected' : '' }}>empacándose</option>
                                <option value="con el repartidor" {{ $pedido->estado === 'con el repartidor' ? 'selected' : '' }}>con el repartidor</option>
                                <option value="completado" {{ $pedido->estado === 'completado' ? 'selected' : '' }}>completado</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
