<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'message',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'order_id');
    }

    // Accesor para obtener productos con cantidad y precio desde el pedido relacionado
    public function getProductosAttribute()
    {
        if (!$this->pedido) {
            return collect(); // Retorna colección vacía si no hay pedido
        }

        return $this->pedido->detalles->map(function ($detalle) {
            return (object)[
                'nombre' => $detalle->producto->nombre,
                'cantidad' => $detalle->cantidad,
                'precio' => $detalle->precio_unitario,
            ];
        });
    }
}
