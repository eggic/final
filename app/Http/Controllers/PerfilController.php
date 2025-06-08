<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class PerfilController extends Controller
{


public function index()
{
    $usuario = auth()->user();
    return view('perfil.index', compact('usuario'));
}




    public function edit()
    {
        $usuario = auth()->user();
        return view('perfil.edit', compact('usuario'));
    }

    public function update(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado correctamente.');
    }
} // <--- ESTA LLAVE FINAL ES LA QUE TE FALTABA

