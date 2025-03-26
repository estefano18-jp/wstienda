<?php
require_once 'Usuario.php';

$usuario = new Usuario();

// Obtener los datos del POST (suponiendo que usas JSON)
$data = json_decode(file_get_contents('php://input'), true);

$result = $usuario->register($data); // Llama al método de registro

// Devuelve la respuesta en formato JSON
echo json_encode($result);
?>
