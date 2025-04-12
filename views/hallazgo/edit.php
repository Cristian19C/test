<!-- views/hallazgo/edit.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Hallazgo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4 mb-4">
    <h1>Editar Hallazgo</h1>
    <form action="index.php?entity=hallazgo&action=edit&id=<?= $hallazgo['id'] ?>" method="POST">
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?= $hallazgo['titulo'] ?>" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required><?= $hallazgo['descripcion'] ?></textarea>
        </div>
         <!-- Campo de sede (nuevo) -->
         <div class="form-group">
            <label for="id_proceso_sede">Sede</label>
            <select class="form-control" id="id_proceso_sede" name="id_proceso_sede">
                <option value="">Seleccione una sede</option>
                <?php foreach ($sedes as $sede): ?>
                    <option value="<?= $sede['id'] ?>" <?= ($sede['id'] == $hallazgo['id_proceso_sede']) ? 'selected' : '' ?>>
                        <?= $sede['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Seleccione la sede que reportó el hallazgo.</small>
        </div>
        <div class="form-group">
            <label for="id_estado">Estado</label>
            <select class="form-control" id="id_estado" name="id_estado" required>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado['id'] ?>" <?= ($estado['id'] == $hallazgo['id_estado']) ? 'selected' : '' ?>>
                        <?= $estado['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Seleccione el estado del hallazgo.</small>
        </div>
        <div class="form-group">
            <label for="id_usuario">Usuario Responsable</label>
            <select class="form-control" id="id_usuario" name="id_usuario" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>" <?= ($usuario['id'] == $hallazgo['id_usuario']) ? 'selected' : '' ?>>
                        <?= $usuario['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Seleccione el usuario responsable del hallazgo.</small>
        </div>
        <!-- Usar un checkbox en vez de un select multiple -->
        <div class="form-group">
            <label>Procesos Relacionados</label>
            <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                <?php foreach ($procesos as $proceso): ?>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="proceso_<?= $proceso['id'] ?>" name="procesos[]" value="<?= $proceso['id'] ?>" <?= in_array($proceso['id'], $selectedProcesoIds) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="proceso_<?= $proceso['id'] ?>"><?= $proceso['nombre'] ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <small class="form-text text-muted">Seleccione todos los procesos relacionados con este hallazgo.</small>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="index.php?entity=hallazgo&action=index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
