<?php
require_once 'Conexion.php';

class Usuario extends Conexion {
    private $conexion;

    public function __construct() {
        // Obtiene la conexión a la base de datos desde la clase base
        $this->conexion = parent::getConexion();

        // Verifica que la conexión haya sido establecida correctamente
        if (!$this->conexion) {
            throw new Exception("No se pudo establecer una conexión con la base de datos.");
        }
    }

    // Registrar usuario
    public function register($params = []): array {
        try {
            // Verifica si los parámetros necesarios están presentes
            if (empty($params['usuario']) || empty($params['correo']) || empty($params['contraseña'])) {
                return ['status' => false, 'msg' => 'Datos incompletos'];
            }

            // Verificar si el usuario o correo ya existen
            $sql = "SELECT * FROM usuarios WHERE usuario = ? OR correo = ?";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([$params['usuario'], $params['correo']]);

            if ($consulta->rowCount() > 0) {
                return ['status' => false, 'msg' => 'El usuario o correo ya están registrados'];
            }

            // Si todo está bien, insertar el nuevo usuario
            $sql = "INSERT INTO usuarios (usuario, correo, contraseña) VALUES (?, ?, ?)";
            $consulta = $this->conexion->prepare($sql);
            $hashPassword = password_hash($params['contraseña'], PASSWORD_DEFAULT); // Cifra la contraseña
            $consulta->execute([$params['usuario'], $params['correo'], $hashPassword]);

            return ['status' => true, 'msg' => 'Usuario registrado con éxito'];
        } catch (PDOException $e) {
            error_log($e->getMessage()); // Registra el error para depuración
            return ['status' => false, 'msg' => 'Error al registrar usuario'];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['status' => false, 'msg' => 'Error al registrar usuario'];
        }
    }

    // Método para login de usuario
    public function login($username, $password) {
        // Preparar la consulta para verificar las credenciales
        $sql = "SELECT * FROM usuarios WHERE usuario = :username";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar la contraseña
            if (password_verify($password, $usuario['contraseña'])) {
                return $usuario; // Retorna los datos del usuario si la contraseña es correcta
            }
        }
        return false; // Si no se encuentran las credenciales o la contraseña es incorrecta
    }

    // Método para listar todos los usuarios
    public function getAll(): array {
        try {
            $sql = "SELECT id, usuario, correo FROM usuarios";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC); // Devuelve solo la lista de usuarios
        } catch (PDOException $e) {
            error_log($e->getMessage()); // Registra el error para depuración
            return []; // Devuelve un array vacío si hay error
        }
    }
}
?>
