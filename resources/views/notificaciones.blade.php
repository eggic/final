@extends('layouts.app')

@section('content')
<main class="container py-4">
    <div class="card shadow-sm p-4 mx-auto" style="max-width: 700px;">
        <h2 class="text-center mb-4">Notificaciones</h2>

        @if ($notifications->isEmpty())
            <div class="alert alert-info text-center">
                No tienes notificaciones nuevas.
            </div>
        @else
            @foreach ($notifications as $notification)
                @php
                    $status = strtolower($notification->status);
                    $cardColor = match($status) {
                        'entregado' => 'bg-success text-white',
                        'pendiente' => 'bg-danger text-white',
                        'caminando', 'en camino' => 'bg-warning text-dark',
                        'cancelado' => 'bg-secondary text-white',
                        default => 'bg-info text-white',
                    };
                @endphp

                <div class="card mb-3 {{ $cardColor }}">
                    <div class="card-header d-flex justify-content-between align-items-center" onclick="toggleDetails('details-{{ $notification->id }}')" style="cursor:pointer;">
                        <div>
                            <strong>Pedido #{{ $notification->order_id }}</strong>
                            <div class="small">
                                Estado: 
                                <span class="badge bg-light text-dark">{{ ucfirst($notification->status) }}</span>
                            </div>
                        </div>
                        <span>&#x25BC;</span>
                    </div>

                    <div id="details-{{ $notification->id }}" class="card-body bg-white text-dark" style="display: none;">
                        @if ($notification->pedido && $notification->pedido->detalles)
                            <ul class="list-group list-group-flush">
                                @foreach ($notification->pedido->detalles as $detalle)
                                    <li class="list-group-item">
                                        {{ $detalle->producto->nombre ?? 'Producto eliminado' }} 
                                        x{{ $detalle->cantidad }} - ${{ $detalle->precio_unitario }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted"><em>Este pedido ya no está disponible.</em></p>
                        @endif

                        <small class="text-muted d-block mt-2">{{ $notification->created_at->format('h:i A, d/m/Y') }}</small>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
    window.toggleDetails = function(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
    }
</script>
@endpush
