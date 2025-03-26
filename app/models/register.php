<?php
// Configuración de cabeceras para permitir solicitudes cross-origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

// Incluir la clase de Usuario
require_once 'Usuario.php';

// Manejar solo solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit;
}

try {
    // Intentar obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Si los datos están vacíos, intentar con $_POST
    if (empty($data)) {
        $data = $_POST;
    }

    // Validar que se recibieron todos los datos necesarios
    if (!isset($data['usuario']) || !isset($data['correo']) || !isset($data['contraseña'])) {
        http_response_code(400); // Bad Request
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos. Se requieren usuario, correo y contraseña'
        ]);
        exit;
    }

    // Crear instancia de Usuario
    $usuario = new Usuario();

    // Intentar registrar al usuario
    $result = $usuario->register([
        'usuario' => $data['usuario'],
        'correo' => $data['correo'],
        'contraseña' => $data['contraseña']
    ]);

    // Devolver respuesta
    if ($result['status']) {
        http_response_code(201); // Created
        echo json_encode([
            'success' => true,
            'message' => $result['msg']
        ]);
    } else {
        http_response_code(400); // Bad Request
        echo json_encode([
            'success' => false,
            'message' => $result['msg']
        ]);
    }

} catch (Exception $e) {
    // Manejar cualquier error inesperado
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
?>