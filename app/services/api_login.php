<?php
// Activa la visualización de errores (solo para pruebas, desactívala en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

// Incluimos únicamente la clase Usuario para el login
include_once "../models/Usuario.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario();

    // Obtenemos los datos de la solicitud
    $data = $_POST;
    if (empty($data)) {
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);
    }

    // Agregamos un log para ver los datos recibidos
    error_log("Datos recibidos: " . print_r($data, true));

    if (isset($data['usuario']) && isset($data['contraseña'])) {
        $resultado = $usuario->login($data['usuario'], $data['contraseña']);

        if ($resultado) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Inicio de sesión exitoso",
                "user" => [
                    "id"      => $resultado['id'],
                    "usuario" => $resultado['usuario'],
                    "correo"  => $resultado['correo']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Credenciales incorrectas"
            ]);
        }
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
