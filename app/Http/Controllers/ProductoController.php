<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $tiposPermitidos = [
            'Pupusas', 'Hamburguesas', 'Pollo', 'HotDog', 'Pizza', 'Tacos', 'Sushi', 'China', 'Arepas', 'CafeHelado',
            'LicuadodeFresa', 'JugodeNaranja', 'Horchata', 'TeVerdeFrio', 'Smoothie', 'Soda', 'CafeEspresso', 'Malteadas',
            'Gelatina', 'Flan', 'Dona', 'Brownie', 'Tres Leches', 'Cheesecake', 'Roll de Canela', 'Cupcake', 'Arroz con Leche',
            "McDonald's", 'KFC', 'Burger King', 'Subway', 'Pizza Hut', "Domino's Pizza", 'Taco Bell', 'Popeyes', 'Starbucks'
        ];

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'tipo' => 'required|in:' . implode(',', $tiposPermitidos),
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('img'), $nombreImagen);
            $rutaImagen = 'img/' . $nombreImagen;
        } else {
            $rutaImagen = null;
        }

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'tipo' => $request->tipo,
            'imagen' => $rutaImagen,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function pupusas()
    {
        $productos = Producto::where('tipo', 'Pupusas')->get();
        return view('productos.pupusas', compact('productos'));
    }

public function comida() {
    $productos = Producto::whereIn('tipo', ['Pupusas', 'Hamburguesas', 'Pizza'])->get(); // ejemplo
    return view('admin.pedidos.comida', compact('productos'));
}


    public function hamburguesas()
    {
        $productos = Producto::where('tipo', 'Hamburguesas')->get();
        return view('productos.hamburguesas', compact('productos'));
    }

    public function pedido_comida()
    {
        $productos = Producto::all();
        return view('pedido_comida', compact('productos'));
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $tiposPermitidos = [
            'Pupusas', 'Hamburguesas', 'Pollo', 'HotDog', 'Pizza', 'Tacos', 'Sushi', 'China', 'Arepas', 'CafeHelado',
            'LicuadodeFresa', 'JugodeNaranja', 'Horchata', 'TeVerdeFrio', 'Smoothie', 'Soda', 'CafeEspresso', 'Malteadas',
            'Gelatina', 'Flan', 'Dona', 'Brownie', 'Tres Leches', 'Cheesecake', 'Roll de Canela', 'Cupcake', 'Arroz con Leche',
            "McDonald's", 'KFC', 'Burger King', 'Subway', 'Pizza Hut', "Domino's Pizza", 'Taco Bell', 'Popeyes', 'Starbucks'
        ];

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'tipo' => 'required|in:' . implode(',', $tiposPermitidos),
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('img'), $nombreImagen);

            // Eliminar imagen anterior si existe
            if ($producto->imagen && file_exists(public_path($producto->imagen))) {
                unlink(public_path($producto->imagen));
            }

            $producto->imagen = 'img/' . $nombreImagen;
        }

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'tipo' => $request->tipo,
        ]);

        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto actualizado con éxito.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->imagen && file_exists(public_path($producto->imagen))) {
            unlink(public_path($producto->imagen));
        }

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }
}
