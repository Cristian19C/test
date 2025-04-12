<!-- views/hallazgo/show.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Hallazgo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <h1>Detalle del Hallazgo</h1>
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">ID: <?= $hallazgo['id'] ?> - <?= $hallazgo['titulo'] ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Descripción:</strong></p>
                    <p class="card-text"><?= nl2br($hallazgo['descripcion']) ?></p>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header bg-light">Información General</div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Estado:</strong> <?= $hallazgo['estado_nombre'] ?></li>
                            <li class="list-group-item"><strong>Usuario Responsable:</strong> <?= $hallazgo['usuario_nombre'] ?></li>
                            <!-- Mostrar sede (nuevo) -->
                            <li class="list-group-item"><strong>Sede:</strong> <?= $hallazgo['sede_nombre'] ?? 'No asignada' ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <h6>Procesos Asociados:</h6>
                <?php if (empty($hallazgo['procesos'])): ?>
                    <p class="text-muted">No hay procesos asociados a este hallazgo.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($hallazgo['procesos'] as $proceso): ?>
                            <li class="list-group-item"><?= $proceso['nombre'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            
            <div class="mt-4">
                <a href="index.php?entity=hallazgo&action=edit&id=<?= $hallazgo['id'] ?>" class="btn btn-warning">Editar</a>
                <a href="index.php?entity=hallazgo&action=delete&id=<?= $hallazgo['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este hallazgo?')">Eliminar</a>
                <a href="index.php?entity=hallazgo&action=index" class="btn btn-secondary">Volver a la lista</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>