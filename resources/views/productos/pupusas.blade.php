<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pupuserías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/tarjetas.css') }}">
    <style>
        /* Botones rosa fuerte */
        .btn-rosa {
            background-color: #e91e63; /* rosa fuerte */
            color: white;
            border-color: #e91e63;
        }
        .btn-rosa:hover,
        .btn-rosa:focus {
            background-color: #c2185b;
            border-color: #c2185b;
            color: white;
        }
        .btn-rosa:active {
            background-color: #ad1457;
            border-color: #ad1457;
        }
    </style>
</head>
<body class="bg-light">

{{-- Incluir el navbar --}}
@include('layouts.navbar')

<main class="container py-4">
    <h1 class="text-center mb-4">Pupuserías Destacadas</h1>

    <div class="comidas-apiladas">
        @forelse($productos as $producto)
        <div class="mb-4">
            <div class="card shadow-sm">
                <img src="{{ asset($producto->imagen) }}" class="img-pequena" alt="Imagen pupusería" />
                <div class="card-body">
                    <h5 class="card-title">{{ $producto->nombre }}</h5>
                    <p class="card-text">{{ $producto->descripcion }}</p>
                    <p><strong>Precio por unidad:</strong> ${{ number_format($producto->precio, 2) }}</p>

                    <form action="{{ route('carrito.agregar') }}" method="POST" class="d-flex flex-column align-items-center gap-2">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="cambiarCantidad({{ $loop->index }}, -1)">-</button>
                            <span id="cantidad-{{ $loop->index }}">1</span>
                            <button type="button" class="btn btn-outline-secondary" onclick="cambiarCantidad({{ $loop->index }}, 1)">+</button>
                        </div>
                        <input type="hidden" name="cantidad" id="input-cantidad-{{ $loop->index }}" value="1" />
                        <button type="submit" class="btn btn-rosa">Agregar al carrito</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">No hay productos disponibles.</p>
        @endforelse
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('carrito.index') }}" class="btn btn-secondary px-5 py-2">Hacer pedido</a>
    </div>
</main>

<!-- Scripts -->
<script>
    function cambiarCantidad(index, cambio) {
        const spanCantidad = document.getElementById('cantidad-' + index);
        const inputCantidad = document.getElementById('input-cantidad-' + index);

        let cantidad = parseInt(spanCantidad.textContent);
        cantidad += cambio;
        if (cantidad < 1) cantidad = 1;

        spanCantidad.textContent = cantidad;
        inputCantidad.value = cantidad;
    }
</script>

</body>
</html>
