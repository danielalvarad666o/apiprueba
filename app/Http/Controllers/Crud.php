<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Validator;

class Crud extends Controller
{
    public function registro(Request $request)
    {
        return view('registro',['alerta' => null],['alertas' => null]);
    }
    public function lista(Request $request)
    {
        return view('lista');
    }
    public function loginview(Request $request)
    {
        return view('login',['alerta' => null],['alertas' => null]);
    }
    
    public function crearUser(Request $request){
       
        $validacion = Validator::make(
            $request->all(), [
                'nombre' => "required|string|max:20",
                'apellidos' => "required|string|max:30",
                
                'email' => "required|string|email:rfc,dns",
                'contraseña' => "required|string|min:4",]
        );

        if ($validacion->fails()) {
            return response()->json([
                "status" => 400,
                "msg" => "No se cumplieron las validaciones",
                "error" => $validacion->errors(),
                "data" => null,
            ], 400);
        }

        $response = Http::post('http://127.0.0.1:8081/api/crearUser', [
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'contraseña' => $request->contraseña,


        ]);

        
//dd($response->body());
        
        if($response->status()==201){
            return view('login',['alerta' => 'usuarios registrado']);
        }else{
            return view('registro',['alerta' => 'Error al crear usuario ']);
        }

        
    }

    public function login(Request $request){
        if (Session::has('api_token')) {
            // Si ya está autenticado, redirigir a la lista de usuarios u otra vista
            return redirect()->route('lista');
        }

        $validacion = Validator::make(
            $request->all(), [
                
                
                'email' => "required|string|email:rfc,dns",
                'contraseña' => "required|string|min:4",]
        );

        if ($validacion->fails()) {
            return response()->json([
                "status" => 400,
                "msg" => "No se cumplieron las validaciones",
                "error" => $validacion->errors(),
                "data" => null,
            ], 400);
        }

        
        $response = Http::post('http://127.0.0.1:8081/api/login', [
           
            'email' => $request->email,
            'contraseña' => $request->contraseña,


        ]);
        $token = $response->json('token');  // Aquí asumimos que la respuesta tiene el campo 'token'
        
        // Guardar el token en la sesión (o en otro lugar)
        Session::put('api_token', $token);

        $status=$response->status();
        if($status==200){
            return $this->traerUsuarios($request);
        }else{
            return view('login',['alertas' => 'Error en las credenciales']);
        }

    }



    public function traerUsuarios(Request $request)
    {
        $token = Session::get('api_token');
        
        // Hacer la solicitud para obtener los usuarios
        $response = Http::withToken($token)->get('http://127.0.0.1:8081/api/users');
        
        if ($response->successful()) {
            // Obtener los usuarios de la respuesta
            $usuarios = $response->json();
            
            // Pasar los usuarios a la vista 'lista'
            return view('lista', ['usuarios' => $usuarios]);
        } else {
            // Si la solicitud falla, regresar con un mensaje de error
            return view('lista', ['alerta' => 'No se pudieron cargar los usuarios']);
        }
    }
public function mostrarFormularioEdicion($id)
{
    $token = Session::get('api_token');
    // Solicitar el usuario a editar al API
    $response = Http::withToken($token)->get("http://127.0.0.1:8081/api/users/{$id}");

    if ($response->successful()) {
        $usuario = $response->json();
        return view('editar', ['usuario' => $usuario]);
    } else {
        return redirect()->back()->with('alerta', 'Error al obtener el usuario');
    }
}


    public function cerrarSesion(Request $request)
    {
        // Obtener el token de la sesión
        $token = Session::get('api_token');
    
        // Enviar una solicitud POST al servidor para cerrar sesión (revocar el token)
        $response = Http::withToken($token)->post('http://127.0.0.1:8081/api/logout');
    
        if ($response->successful()) {
            // Eliminar el token de la sesión local después del logout
            Session::forget('api_token');
            return view('login',['alerta' => null],['alertas' => "Session Cerrada"]);
            
        }
    
    }

 // Editar el usuario
public function editar(Request $request, $id)
{
    $token = Session::get('api_token');

    // Enviar la solicitud de actualización al API
    $response = Http::withToken($token)->put("http://127.0.0.1:8081/api/update/{$id}", [
        'nombre' => $request->nombre,
        'apellidos' => $request->apellidos,
        'email' => $request->email,
    ]);

    if ($response->successful()) {
        // Redirigir con mensaje de éxito
        return redirect()->route('usuarios.list')->with('alerta', 'Usuario actualizado correctamente');
    } else {
        // Redirigir con mensaje de error
        return redirect()->back()->with('alerta', 'Error al actualizar el usuario');
    }
}

public function eliminar(Request $request, $id)
{
    // Obtener el token de la sesión
    $token = Session::get('api_token');

    // Enviar la solicitud DELETE al API para eliminar el usuario
    $response = Http::withToken($token)->delete("http://127.0.0.1:8081/api/destroy/{$id}");

    // Comprobar si la solicitud fue exitosa
    if ($response->successful()) {
        // Si se eliminó correctamente, redirigir con un mensaje de éxito
        return redirect()->back()->with('alerta', 'Usuario eliminado correctamente');
    } else {
        // Manejar los errores si la solicitud falla
        return redirect()->back()->with('alerta', 'Error al eliminar el usuario');
    }
}

    
}
