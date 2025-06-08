@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>📊 Reportes de Pedidos</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->user->name ?? 'N/A' }}</td>
                    <td>{{ $pedido->created_at->format('Y-m-d') }}</td>
                    <td>${{ number_format($pedido->total, 2) }}</td>
                    <td>{{ $pedido->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
