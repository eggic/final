<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoConfirmadoMailable;

class CarritoController extends Controller
{
    // Agrega productos al carrito (sesión)
    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $productoId = $request->input('producto_id');
        $cantidad = $request->input('cantidad');

        $producto = Producto::findOrFail($productoId);

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$productoId])) {
            $carrito[$productoId]['cantidad'] += $cantidad;
        } else {
            $carrito[$productoId] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $cantidad,
            ];
        }

        session(['carrito' => $carrito]);

        return redirect()->back()->with('success', 'Producto agregado al carrito');
    }

    // Mostrar carrito
    public function index()
    {
        $carrito = session('carrito', []);
        return view('carrito', compact('carrito'));
    }

    // Confirmar pedido, crear en BD y enviar correo + notificación
    public function confirmarPedido(Request $request)
    {
        $carrito = session()->get('carrito');

if (empty($carrito) || !is_array($carrito)) {
    return redirect()->back()->with('error', 'El carrito está vacío.');
}


        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($carrito as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }

            $pedido = Pedido::create([
                'usuario_id' => auth()->id(),
                'fecha_pedido' => now(),
                'estado' => 'pendiente',
                'total' => $total,
            ]);

            foreach ($carrito as $item) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                ]);
            }

            // ✅ Crear la notificación
            Notification::create([
                'user_id' => auth()->id(),
                'order_id' => $pedido->id,
                'message' => 'Tu pedido #' . $pedido->id . ' ha sido confirmado.',
            ]);

            session()->forget('carrito');
            DB::commit();

            // Cargar relación para enviar a la vista
            $pedido = Pedido::with('detalles.producto')->find($pedido->id);

            // Enviar correo con PDF adjunto
            Mail::to(auth()->user()->email)->send(new PedidoConfirmadoMailable($pedido));

            // Redirige a vista de confirmación
            return view('pedido.confirmacion', compact('pedido'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    public function generarRecibo($idPedido)
    {
        $pedido = Pedido::with('detalles.producto')->findOrFail($idPedido);

        $pdf = Pdf::loadView('pdf.recibo', compact('pedido'));

        return $pdf->download("recibo_pedido_{$pedido->id}.pdf");
    }
}
