<!-- views/hallazgo/list.php -->
<?php include 'views/layout/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Hallazgos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
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
    <table class="table table-bordered">
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
            <!-- Informacion en caso de que no existan elementos -->
            <?php if(count($hallazgos) === 0): ?>
                <tr>
                    <td colspan="8" class="text-center">No se encontraron hallazgos registrados en el sistema</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($hallazgos as $hallazgo): ?>
            <tr>
                <td><?= $hallazgo['id'] ?></td>
                <td><?= $hallazgo['titulo'] ?></td>
                <td><?= $hallazgo['descripcion'] ?></td>
                <td><?= $hallazgo['estado_nombre'] ?></td>
                <td><?= $hallazgo['usuario_nombre'] ?></td>
                <td><?= $hallazgo['sede_nombre'] ?? 'No asignada' ?></td>
                <td>
                    <ul>
                        <?php foreach ($hallazgo['procesos'] as $proceso): ?>
                            <li><?= $proceso['nombre'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
                <td>
                    <div class="d-flex flex-column" style="gap: 6px;">
                        <a href="index.php?entity=hallazgo&action=show&id=<?= $hallazgo['id'] ?>" class="btn btn-info btn-sm">Ver</a>
                        <a href="index.php?entity=hallazgo&action=edit&id=<?= $hallazgo['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="index.php?entity=hallazgo&action=delete&id=<?= $hallazgo['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>