<!-- views/incidente/list.php -->
<?php include 'views/layout/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Incidentes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/incidentes.css">

</head>
<body>
<div class="container mt-4">
    <h1>Lista de Incidentes</h1>
    
    <!-- Seccion de busqueda por ID -->
    <div class="search-container fade-in">
        <form action="index.php" method="GET" class="form-inline justify-content-center" id="searchForm">
            <input type="hidden" name="entity" value="incidente">
            <input type="hidden" name="action" value="index">
            
            <div class="input-group mr-sm-2 flex-grow-1" style="max-width: 300px;">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-search"></i></div>
                </div>
                <input type="text" class="form-control" id="busqueda_id" name="busqueda_id" 
                    placeholder="Buscar por ID" 
                    value="<?= isset($_GET['busqueda_id']) ? htmlspecialchars($_GET['busqueda_id']) : '' ?>"
                    pattern="[0-9]*" 
                    title="Por favor, ingrese solo números">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if (isset($_GET['busqueda_id']) && $_GET['busqueda_id'] !== ''): ?>
                    <a href="index.php?entity=incidente&action=index" class="btn btn-outline-secondary ml-2">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- Mensaje de resultado de busqueda o error -->
    <?php if (isset($error_mensaje)): ?>
        <div class="search-result-message alert <?= strpos($error_mensaje, 'No se encontró') !== false ? 'alert-warning' : 'alert-danger' ?> fade-in">
            <i class="<?= strpos($error_mensaje, 'No se encontró') !== false ? 'fas fa-exclamation-circle' : 'fas fa-exclamation-triangle' ?>"></i>
            <?= $error_mensaje ?>
        </div>
    <?php elseif (isset($_GET['busqueda_id']) && $_GET['busqueda_id'] !== '' && !empty($incidentes)): ?>
        <div class="search-result-message alert alert-success fade-in">
            <i class="fas fa-check-circle"></i> Incidente con ID: <?= htmlspecialchars($_GET['busqueda_id']) ?> encontrado.
        </div>
    <?php endif; ?>
    
    <!-- Botón para crear incidente -->
    <div class="mb-3">
        <a href="index.php?entity=incidente&action=create" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Crear Incidente
        </a>
    </div>
    
    <!-- Tabla de incidentes -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <?php if (!empty($incidentes)): ?>
                    <?php if (isset($_GET['busqueda_id']) && $_GET['busqueda_id'] !== ''): ?>
                        Resultado de búsqueda
                    <?php else: ?>
                        Incidentes Registrados
                    <?php endif; ?>
                <?php else: ?>
                    No se encontraron incidentes
                <?php endif; ?>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Fecha de Ocurrencia</th>
                            <th>Estado</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($incidentes)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No se encontraron incidentes</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($incidentes as $incidente): ?>
                                <tr class="<?= (isset($_GET['busqueda_id']) && $_GET['busqueda_id'] == $incidente['id']) ? 'highlight-row' : '' ?>">
                                    <td><?= $incidente['id'] ?></td>
                                    <td><?= $incidente['descripcion'] ?></td>
                                    <td><?= $incidente['fecha_ocurrencia'] ?></td>
                                    <td>
                                        <span class="badge <?= ($incidente['id_estado'] == 1) ? 'badge-primary' : 
                                            (($incidente['id_estado'] == 2) ? 'badge-warning' : 
                                            (($incidente['id_estado'] == 3) ? 'badge-success' : 'badge-secondary')) ?>">
                                            <?= $incidente['estado_nombre'] ?>
                                        </span>
                                    </td>
                                    <td><?= $incidente['usuario_nombre'] ?></td>
                                    <td>
                                        <div class="btn-group btn-group-action" role="group">
                                            <a href="index.php?entity=incidente&action=show&id=<?= $incidente['id'] ?>" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?entity=incidente&action=edit&id=<?= $incidente['id'] ?>" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?entity=incidente&action=delete&id=<?= $incidente['id'] ?>" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Está seguro?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <a href="index.php?entity=incidente&action=planes_accion&id=<?= $incidente['id'] ?>" class="btn btn-success btn-sm" title="Planes de acción">
                                                <i class="fas fa-tasks"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/incidentes.js"></script>

</body>
</html>