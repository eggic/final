<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificacionController extends Controller
{
public function destroy($id)
{
    $notification = Notification::findOrFail($id);
    $notification->delete();

    return redirect()->back()->with('success', 'Notificación eliminada.');
}


    public function index()
    {
        $notifications = Notification::with('pedido.detalles.producto')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notificaciones', compact('notifications'));
    }
}
