<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;

class ReporteController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('detalles')->get();

        return view('admin.reportes.index', compact('pedidos'));
    }
}


{
    //
}
