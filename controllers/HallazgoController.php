<?php
// controllers/HallazgoController.php
require_once 'models/HallazgoModel.php';
require_once 'models/ProcesoModel.php';
require_once 'models/EstadoModel.php';
require_once 'models/UsuarioModel.php';
require_once 'models/PlanAccionModel.php';

class HallazgoController {
    private $model;
    private $procesoModel;
    private $estadoModel;
    private $usuarioModel;
    private $planAccionModel;

    public function __construct($pdo) {
        $this->model = new HallazgoModel($pdo);
        $this->procesoModel = new ProcesoModel($pdo);
        $this->estadoModel = new EstadoModel($pdo);
        $this->usuarioModel = new UsuarioModel($pdo);
        $this->planAccionModel = new PlanAccionModel($pdo);
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
        header('Location: index.php?entity=hallazgo&action=index');
    }

    //Metodos para los planes de accion

    //Metodo para mostar los planes de accion de un hallazgo
    public function planesAccion($id_hallazgo){
        $hallazgo = $this->model->getById($id_hallazgo);
        $planesAccion = $this->planAccionModel->getByRegistro($id_hallazgo, 'HALLAZGO');
        $estados = $this->estadoModel->getAll();
        $usuarios = $this->usuarioModel->getAll();
        require 'views/hallazgo/planes_accion.php';
    }

     // Metodo para insertar un plan de accion a un hallazgo
    public function insertPlanAccion($id_hallazgo, $data) {
        $id_plan_accion = $this->planAccionModel->insert($data);
        if ($id_plan_accion) {
            $this->planAccionModel->linkToRegistro($id_plan_accion, $id_hallazgo, 'HALLAZGO');
        }
        header('Location: index.php?entity=hallazgo&action=planes_accion&id=' . $id_hallazgo);
    }

    //Metodo para actualizar un plan de accion de un hallazgo
    public function updatePlanAccion($id_hallazgo, $id_plan_accion, $data){
        $this->planAccionModel->update($id_plan_accion, $data);
        header('Location: index.php?entity=hallazgo&action=planes_accion&id=' . $id_hallazgo);
    }

    // Metodo para eliminar un plan de accion de un hallazgo
    public function deletePlanAccion($id_hallazgo, $id_plan_accion) {
        $this->planAccionModel->unlinkFromRegistro($id_plan_accion, $id_hallazgo, 'HALLAZGO');
        $this->planAccionModel->delete($id_plan_accion);
        header('Location: index.php?entity=hallazgo&action=planes_accion&id=' . $id_hallazgo);
    }

}
?>