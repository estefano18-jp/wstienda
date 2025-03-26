<?php 
require_once "../models/Producto.php";
$producto = new Productos();  


header("Access-Control-Allow-Origin");
header( "Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, PUT, DELETE");
header("Content-type: application/json; charset=utf-8");

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'GET'){
  $registros = [];

  if (isset($_GET['q'])) {
    switch ($_GET['q']) {
      case 'findById': $registros = $producto->getById(['id' => $_GET['id']]); break;
      case 'findByTipo': $registros = $producto->getByTipo(['tipo' => $_GET['tipo']]); break;
      case 'showAll': $registros = $producto->getAll(); break;
    }
  }

  
  header('HTTP/1.1 200 OK');
  echo json_encode($registros);
}

else if ($metodo == 'POST'){

  $inputJSON = file_get_contents('php://input'); 
  $datos = json_decode($inputJSON, true); 

  $registro = [
    "tipo"   => $datos["tipo"],
    "genero" => $datos["genero"],
    "talla"  => $datos["talla"],
    "precio" => $datos["precio"]
  ];

  $status = $producto->add($registro);

  header('HTTP/1.1 200 OK');
  echo json_encode(["status" => $status]);  
}

else if ($metodo == 'PUT'){
  // Recibimos los datos JSON para actualizar el producto
  $inputJSON = file_get_contents('php://input');
  $datos = json_decode($inputJSON, true);

  $registro = [
    "id"     => $datos["id"],
    "tipo"   => $datos["tipo"],
    "genero" => $datos["genero"],
    "talla"  => $datos["talla"],
    "precio" => $datos["precio"]
  ];

  // Llamamos al método 'update' del modelo Producto
  $status = $producto->update($registro);

  // Informar el estado del servicio
  header("HTTP/1.1 200 OK");
  echo json_encode(["status" => $status]);
}

else if ($metodo == 'DELETE'){
  // Obtenemos el ID de la URL
  $requestURI = $_SERVER['REQUEST_URI'];
  $uriSegments = explode('/', $requestURI);

  // Asumiendo que la URL contiene el ID a eliminar al final
  $idEliminar = intval(end($uriSegments));

  // Llamamos al método 'delete' del modelo Producto
  $status = $producto->delete(['id' => $idEliminar]);

  // Informar el estado del servicio
  header('HTTP/1.1 200 OK');
  echo json_encode(["status" => $status]);  // Convierte objeto PHP en JSON
}
?>
