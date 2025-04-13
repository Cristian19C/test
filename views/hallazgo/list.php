<!-- views/hallazgo/list.php -->
<?php include 'views/layout/header.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Hallazgos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/hallazgos.css">

</head>

<body>
    <div class="container-wide mt-4 mb-5">

        <div id="alertMessage" class="alert alert-float" role="alert"></div>

        <h1>Lista de Hallazgos</h1>

        <!-- Filtro de sede -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="index.php" method="GET" class="form-inline">
                    <input type="hidden" name="entity" value="hallazgo">
                    <input type="hidden" name="action" value="index">
                    <div class="form-group mr-2">
                        <label for="sede" class="mr-2">Filtrar por Sede:</label>
                        <select class="form-control" id="sede" name="sede">
                            <option value="">Todas las sedes</option>
                            <?php foreach ($sedes as $sede): ?>
                                <option value="<?= $sede['id'] ?>" <?= (isset($_GET['sede']) && $_GET['sede'] == $sede['id']) ? 'selected' : '' ?>>
                                    <?= $sede['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <?php if (isset($_GET['sede']) && $_GET['sede']): ?>
                        <a href="index.php?entity=hallazgo&action=index" class="btn btn-outline-secondary ml-2">Limpiar filtro</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <a href="index.php?entity=hallazgo&action=create" class="btn btn-success">Crear Hallazgo</a>
            </div>
        </div>
        <div class="card">
            <table class="table table-striped table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                        <th>Sede</th>
                        <th>Procesos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($hallazgos)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">No se encontraron hallazgos</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($hallazgos as $hallazgo): ?>
                            <tr id="hallazgo-row-<?= $hallazgo['id'] ?>">
                                <td><?= $hallazgo['id'] ?></td>
                                <td><?= $hallazgo['titulo'] ?></td>
                                <td><?= $hallazgo['descripcion'] ?></td>
                                <td>
                                    <!-- Selector de estado con actualización AJAX -->
                                    <div class="estado-container">
                                        <select class="form-control estado-select"
                                            data-hallazgo-id="<?= $hallazgo['id'] ?>"
                                            aria-label="Cambiar estado">
                                            <?php foreach ($estados as $estado): ?>
                                                <option value="<?= $estado['id'] ?>" <?= ($estado['id'] == $hallazgo['id_estado']) ? 'selected' : '' ?>>
                                                    <?= $estado['nombre'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <!-- Spinner de carga -->
                                        <div class="spinner-border text-primary ml-2" id="spinner-<?= $hallazgo['id'] ?>" role="status">
                                            <span class="sr-only">Actualizando...</span>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $hallazgo['usuario_nombre'] ?></td>
                                <td><?= $hallazgo['sede_nombre'] ?? 'No asignada' ?></td>
                                <td>
                                    <ul>
                                        <?php foreach ($hallazgo['procesos'] as $proceso): ?>
                                            <li><small><?= $proceso['nombre'] ?></small></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-action" role="group">
                                        <a href="index.php?entity=hallazgo&action=show&id=<?= $hallazgo['id'] ?>" class="btn btn-info btn-action" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="index.php?entity=hallazgo&action=edit&id=<?= $hallazgo['id'] ?>" class="btn btn-warning btn-action" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?entity=hallazgo&action=delete&id=<?= $hallazgo['id'] ?>" class="btn btn-danger btn-action" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este hallazgo?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <a href="index.php?entity=hallazgo&action=planes_accion&id=<?= $hallazgo['id'] ?>" class="btn btn-success btn-action" title="Planes de acción">
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

    <!-- Scripts de jQuery, Bootstrap y código AJAX para actualización de estado -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/hallazgos.js"></script>
</body>

</html>