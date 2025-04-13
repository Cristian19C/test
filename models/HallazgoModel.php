<?php
// models/HallazgoModel.php
require_once 'config.php';

class HallazgoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function getAll($filtro_sede = null) {
        $main_query = "
            SELECT h.*, e.nombre as estado_nombre, u.nombre as usuario_nombre, p.id as sede_id, p.nombre as sede_nombre
            FROM Hallazgo h
            LEFT JOIN Estado e ON h.id_estado = e.id
            LEFT JOIN Usuario u ON h.id_usuario = u.id
            LEFT JOIN Proceso p ON h.id_proceso_sede = p.id
            WHERE h.isActive = TRUE
        ";

        // Añadir filtro si se proporciona 
        if($filtro_sede){
            $main_query .= "AND h.id_proceso_sede = ?";
            $stmt = $this->pdo->prepare($main_query);
            $stmt->execute([$filtro_sede]);
        }else{
            $stmt = $this->pdo->query($main_query);
        }

        $hallazgos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($hallazgos as &$hallazgo) {
            $hallazgo['procesos'] = $this->getProcesos($hallazgo['id']);
        }
        return $hallazgos;
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT h.*, e.nombre as estado_nombre, u.nombre as usuario_nombre, p.id as sede_id, p.nombre as sede_nombre
            FROM Hallazgo h
            LEFT JOIN Estado e ON h.id_estado = e.id
            LEFT JOIN Usuario u ON h.id_usuario = u.id
            LEFT JOIN Proceso p ON h.id_proceso_sede = p.id
            WHERE h.id = ? AND h.isActive = TRUE
        ");
        $stmt->execute([$id]);
        $hallazgo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($hallazgo) {
            $hallazgo['procesos'] = $this->getProcesos($hallazgo['id']);
        }
        return $hallazgo;
    }

    public function insert($titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario, $id_proceso_sede) {
        $stmt = $this->pdo->prepare("INSERT INTO Hallazgo (titulo, descripcion, id_estado, id_usuario, id_proceso_sede, isActive) VALUES (?, ?, ?, ?, ?, TRUE)");
        $result = $stmt->execute([$titulo, $descripcion, $id_estado, $id_usuario, $id_proceso_sede]);

        if ($result) {
            $hallazgo_id = $this->pdo->lastInsertId();
            $this->updateProcesos($hallazgo_id, $proceso_ids);
            return true;
        }
        return false;
    }

    public function update($id, $titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario, $id_proceso_sede) {
        $stmt = $this->pdo->prepare("UPDATE Hallazgo SET titulo = ?, descripcion = ?, id_estado = ?, id_usuario = ?, id_proceso_sede = ? WHERE id = ? AND isActive = TRUE");
        $result = $stmt->execute([$titulo, $descripcion, $id_estado, $id_usuario, $id_proceso_sede,$id]);

        if ($result) {
            $this->updateProcesos($id, $proceso_ids);
            return true;
        }
        return false;
    }

    //Nuevo metodo para actualizar solo el estado del hallazgo
    public function updateEstado($id, $id_estado){
        $stmt = $this->pdo->prepare("UPDATE Hallazgo SET id_estado = ? WHERE id =? AND isActive = TRUE");
        $result = $stmt->execute([$id_estado, $id]);

        if($result){
            // Obtener el nombre del estado para devolverlo en la respuesta
            $estadoStmt = $this->pdo->prepare("SELECT nombre FROM Estado WHERE id = ?");
            $estadoStmt->execute([$id_estado]);
            $estadoNombre = $estadoStmt->fetchColumn();

            return [
                "success" => true,
                "message" => "Estado actualizado a '$estadoNombre' con exito ",
                "estadoNombre" => $estadoNombre
            ];
        }

        return ['success' => false, 'message' => "Error al actualizar el estado"];
    }

    public function delete($id) {
        // Hacer un borrado logico
        $stmt = $this->pdo->prepare("UPDATE Hallazgo SET isActive = FALSE WHERE id = ?");
        return $stmt->execute([$id]);
    }
	
	private function updateProcesos($hallazgo_id, $proceso_ids) {
        // Eliminar procesos existentes
        $stmt = $this->pdo->prepare("DELETE FROM Hallazgo_Proceso WHERE id_hallazgo = ?");
        $stmt->execute([$hallazgo_id]);

        // Insertar procesos seleccionados
        foreach ($proceso_ids as $proceso_id) {
            $stmt = $this->pdo->prepare("INSERT INTO Hallazgo_Proceso (id_hallazgo, id_proceso) VALUES (?, ?)");
            $stmt->execute([$hallazgo_id, $proceso_id]);
        }
    }

    public function getProcesos($hallazgo_id) {
        $stmt = $this->pdo->prepare("SELECT p.* FROM Proceso p INNER JOIN Hallazgo_Proceso hp ON p.id = hp.id_proceso WHERE hp.id_hallazgo = ?");
        $stmt->execute([$hallazgo_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSedes(){
        $stmt = $this->pdo->query("SELECT id, nombre, descripcion, fecha_creacion FROM Proceso ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>