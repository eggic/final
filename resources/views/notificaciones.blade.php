@extends('layouts.app')

@section('content')
<main class="container py-4">
    <div class="card shadow-sm p-4 mx-auto" style="max-width: 700px;">
        <h2 class="text-center mb-4" style="color: #c26c8e;">Notificaciones</h2>

        @if ($notifications->isEmpty())
            <div class="alert alert-info text-center">
                No tienes notificaciones nuevas.
            </div>
        @else
            @foreach ($notifications as $notification)
                @php
                    $estado = strtolower($notification->pedido->estado ?? 'desconocido');
                    $badgeColor = match($estado) {
                        'entregado' => 'bg-success',
                        'pendiente' => 'bg-danger',
                        'caminando', 'en camino' => 'bg-warning text-dark',
                        'cancelado' => 'bg-secondary',
                        default => 'bg-info',
                    };
                @endphp

                <div class="card mb-3 border-0 shadow-sm position-relative" style="border-radius: 12px;">
                    
                    {{-- Mostrar botón eliminar SOLO si el pedido está entregado --}}
                    @if ($estado === 'entregado')
                        <form method="POST" action="{{ route('notificacion.destroy', $notification->id) }}" class="position-absolute top-0 end-0 m-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar notificación" onclick="return confirm('¿Eliminar esta notificación?')">
                                ✖
                            </button>
                        </form>
                    @endif

                    <div class="card-header d-flex justify-content-between align-items-center"
                        onclick="toggleDetails('details-{{ $notification->id }}')"
                        style="cursor:pointer; background-color: #fce4ec; border-radius: 12px 12px 0 0;">
                        
                        <div>
                            <strong style="color:#c26c8e;">Pedido #{{ $notification->order_id }}</strong>
                            <div class="small mt-1">
                                Estado:
                                <span class="badge {{ $badgeColor }}">{{ ucfirst($estado) }}</span>
                            </div>
                        </div>
                        <span style="font-size: 1.2rem; color: #c26c8e;">&#x25BC;</span>
                    </div>

                    <div id="details-{{ $notification->id }}" class="card-body bg-light text-dark" style="display: none; border-radius: 0 0 12px 12px;">
                        @if ($notification->pedido && $notification->pedido->detalles)
                            <ul class="list-group list-group-flush">
                                @foreach ($notification->pedido->detalles as $detalle)
                                    <li class="list-group-item">
                                        {{ $detalle->producto->nombre ?? 'Producto eliminado' }} 
                                        x{{ $detalle->cantidad }} - ${{ number_format($detalle->precio_unitario, 2) }}
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
