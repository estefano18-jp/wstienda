<?php

require_once 'Conexion.php';  

class Productos extends Conexion {
    private $conexion;

    public function __construct() {
        $this->conexion = parent::getConexion();
    }

    public function add($params = []): bool {
        $saveStatus = false;
        try {
            $sql = "INSERT INTO productos (tipo, genero, talla, precio) VALUES (?, ?, ?, ?)";
            $consulta = $this->conexion->prepare($sql);
            $saveStatus = $consulta->execute(array(
                $params['tipo'],
                $params['genero'],
                $params['talla'],
                $params['precio']
            ));
            return $saveStatus;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($params = []): bool {
        try {
            $sql = "DELETE FROM productos WHERE id = ?";
            $consulta = $this->conexion->prepare($sql);
            return $consulta->execute([$params['id']]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($params = []): bool {
        $saveStatus = false;
        try {
            $sql = "UPDATE productos SET tipo = ?, genero = ?, talla = ?, precio = ? WHERE id = ?";
            $consulta = $this->conexion->prepare($sql);
            $saveStatus = $consulta->execute(array(
                $params["tipo"],
                $params["genero"],
                $params["talla"],
                $params["precio"],
                $params["id"]
            ));
            return $saveStatus;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAll() {
        try {
            $sql = "SELECT id, tipo, genero, talla, precio FROM productos ORDER BY id DESC";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function getById($params = []): array {
        try {
            $sql = "SELECT id, tipo, genero, talla, precio FROM productos WHERE id = ?";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(array($params["id"]));
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function getByTipo($params = []): array {
        try {
            $sql = "SELECT id, tipo, genero, talla, precio FROM productos WHERE tipo = ?";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(array($params["tipo"]));
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function getByName($params = []): array {
        try {
            $sql = "SELECT id, tipo, genero, talla, precio FROM productos WHERE tipo LIKE CONCAT('%', ?, '%')";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(array($params["tipo"]));
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }
}
?>
