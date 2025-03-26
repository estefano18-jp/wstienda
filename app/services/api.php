<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

require_once "../models/Usuario.php";

// Manejo de solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario();

    // Obtener datos del cuerpo de la solicitud
    $data = $_POST;
    
    // Si está vacío, intentar con json_decode
    if (empty($data)) {
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);
    }

    // Verificar tipo de solicitud
    if (isset($data['usuario']) && isset($data['contraseña'])) {
        // Intento de login
        $resultado = $usuario->login($data['usuario'], $data['contraseña']);
        
        if ($resultado) {
            echo json_encode([
                "success" => true, 
                "message" => "Inicio de sesión exitoso",
                "user" => $resultado
            ]);
        } else {
            echo json_encode([
                "success" => false, 
                "message" => "Credenciales incorrectas"
            ]);
        }
    } elseif (isset($data['usuario']) && isset($data['correo']) && isset($data['contraseña'])) {
        // Intento de registro
        $resultado = $usuario->register([
            'usuario' => $data['usuario'],
            'correo' => $data['correo'],
            'contraseña' => $data['contraseña']
        ]);

        echo json_encode([
            "success" => $resultado['status'],
            "message" => $resultado['msg']
        ]);
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "Datos incompletos"
        ]);
    }
} else {
    echo json_encode([
        "success" => false, 
        "message" => "Método no permitido"
    ]);
}
?>