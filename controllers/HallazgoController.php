<?php
// controllers/HallazgoController.php
require_once 'models/HallazgoModel.php';
require_once 'models/ProcesoModel.php';
require_once 'models/EstadoModel.php';
require_once 'models/UsuarioModel.php';

class HallazgoController {
    private $model;
    private $procesoModel;
    private $estadoModel;
    private $usuarioModel;

    public function __construct($pdo) {
        $this->model = new HallazgoModel($pdo);
        $this->procesoModel = new ProcesoModel($pdo);
        $this->estadoModel = new EstadoModel($pdo);
        $this->usuarioModel = new UsuarioModel($pdo);
    }

    public function index() {

        //Agregar filtro si esta presente
        $filtro_sede = isset($_GET['sede']) ? $_GET['sede']: null;

        $hallazgos = $this->model->getAll($filtro_sede);

        $sedes = $this->model->getSedes();

        //Obtener todos los estados 
        $estados = $this->estadoModel->getAll();

        require 'views/hallazgo/list.php';
    }

    public function show($id) {
        $hallazgo = $this->model->getById($id);
        require 'views/hallazgo/show.php';
    }

    public function create() {
        $procesos = $this->procesoModel->getAll();
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        $sedes = $this->model->getSedes();

        require 'views/hallazgo/create.php';
    }

    public function insert($data) {
        $titulo = $data['titulo'];
        $descripcion = $data['descripcion'];
        $proceso_ids = $data['procesos'] ?? [];
        $id_estado = $data['id_estado'];
        $id_usuario = $data['id_usuario'];
        $id_proceso_sede = $data['id_proceso_sede'] ?? null;

        $this->model->insert($titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario, $id_proceso_sede);
        header('Location: index.php?entity=hallazgo&action=index');
    }

    public function edit($id) {
        $hallazgo = $this->model->getById($id);
        $procesos = $this->procesoModel->getAll();
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        $sedes = $this->model->getSedes();
        $selectedProcesos = $this->model->getProcesos($hallazgo['id']);
        $selectedProcesoIds = array_column($selectedProcesos, 'id');
        require 'views/hallazgo/edit.php';
    }

    public function update($id, $data) {
        $titulo = $data['titulo'];
        $descripcion = $data['descripcion'];
        $proceso_ids = $data['procesos'] ?? [];
        $id_estado = $data['id_estado'];
        $id_usuario = $data['id_usuario'];
        $id_proceso_sede = $data['id_proceso_sede'] ?? null;

        $this->model->update($id, $titulo, $descripcion, $proceso_ids, $id_estado, $id_usuario, $id_proceso_sede);
        header('Location: index.php?entity=hallazgo&action=index');
    }

    // Nuevo metodo para actualizar el estado en tiempo real sin recargar la pagina mediante ajax

    public function updateEstado($id, $data){
        $id_estado = $data['id_estado'];
        $result = $this->model->updateEstado($id, $id_estado);

        // Respuesta en formato json debibo a ajax
        header('Content-Type: application/json');
        echo json_encode($result);
        exit; 
    }

    public function delete($id) {
        $this->model->delete($id);
        header('Location: index.php?action=index');
    }
}
?>