<!-- views/hallazgo/planes_accion.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planes de Acción del Hallazgo <?= $hallazgo['id'] ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<?php include 'views/layout/header.php'; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Planes de Acción para el Hallazgo</h2>
        <a href="index.php?entity=hallazgo&action=index>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a los Hallazgos
        </a>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Información del Hallazgo</h5>
        </div>
        <div class="card-body">
            <h6><?= $hallazgo['titulo'] ?></h6>
            <p><strong>Descripción:</strong> <?= $hallazgo['descripcion'] ?></p>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Estado:</strong> <?= $hallazgo['estado_nombre'] ?></p>
                </div>
                <div class="col-md-4">
                    <p><strong>Usuario Responsable:</strong> <?= $hallazgo['usuario_nombre'] ?></p>
                </div>
                <div class="col-md-4">
                    <p><strong>Sede:</strong> <?= $hallazgo['sede_nombre'] ?? 'No asignada' ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botón para crear un nuevo plan de acción -->
    <div class="mb-3">
        <button class="btn btn-success" data-toggle="modal" data-target="#modalCrearPlan">
            <i class="fas fa-plus-circle"></i> Crear Plan de Acción
        </button>
    </div>
    
    <!-- Tabla de planes de acción -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Planes de Acción Asociados</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($planesAccion)): ?>
                <div class="alert alert-info m-3">
                    No hay planes de acción asociados a este hallazgo.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Descripción</th>
                                <th>Usuario Responsable</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($planesAccion as $plan): ?>
                            <tr>
                                <td><?= $plan['id'] ?></td>
                                <td><?= $plan['descripcion'] ?></td>
                                <td><?= $plan['usuario_nombre'] ?></td>
                                <td><?= $plan['fecha_inicio'] ?></td>
                                <td><?= $plan['fecha_fin'] ?></td>
                                <td>
                                    <span class="badge <?= ($plan['id_estado'] == 1) ? 'badge-primary' : 
                                        (($plan['id_estado'] == 2) ? 'badge-warning' : 
                                        (($plan['id_estado'] == 3) ? 'badge-success' : 'badge-secondary')) ?>">
                                        <?= $plan['estado_nombre'] ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Botones de acción -->
                                    <div class="btn-group" role="group">
                                        <!-- Botón para editar plan de acción -->
                                        <button class="btn btn-warning btn-sm mr-2" data-toggle="modal" data-target="#modalEditarPlan<?= $plan['id'] ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <!-- Enlace para eliminar plan de acción -->
                                        <a href="index.php?entity=hallazgo&action=planes_accion&id=<?= $hallazgo['id'] ?>&delete_plan=<?= $plan['id'] ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('¿Está seguro de eliminar este plan de acción?')">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <!-- Modal para editar plan de acción -->
                            <div class="modal fade" id="modalEditarPlan<?= $plan['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditarPlanLabel<?= $plan['id'] ?>" aria-hidden="true">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <form action="index.php?entity=hallazgo&action=planes_accion&id=<?= $hallazgo['id'] ?>" method="POST">
                                    <input type="hidden" name="action_plan" value="edit">
                                    <input type="hidden" name="id_plan_accion" value="<?= $plan['id'] ?>">
                                    <div class="modal-header bg-warning text-dark">
                                      <h5 class="modal-title" id="modalEditarPlanLabel<?= $plan['id'] ?>">Editar Plan de Acción</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <!-- Campos del formulario -->
                                      <div class="form-group">
                                          <label for="descripcion">Descripción</label>
                                          <textarea class="form-control" name="descripcion" rows="3" required><?= $plan['descripcion'] ?></textarea>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="id_usuario">Usuario Responsable</label>
                                                  <select class="form-control" name="id_usuario" required>
                                                      <?php foreach ($usuarios as $usuario): ?>
                                                          <option value="<?= $usuario['id'] ?>" <?= ($usuario['id'] == $plan['id_usuario']) ? 'selected' : '' ?>><?= $usuario['nombre'] ?></option>
                                                      <?php endforeach; ?>
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="id_estado">Estado</label>
                                                  <select class="form-control" name="id_estado" required>
                                                      <?php foreach ($estados as $estado): ?>
                                                          <option value="<?= $estado['id'] ?>" <?= ($estado['id'] == $plan['id_estado']) ? 'selected' : '' ?>><?= $estado['nombre'] ?></option>
                                                      <?php endforeach; ?>
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="fecha_inicio">Fecha Inicio</label>
                                                  <input type="date" class="form-control" name="fecha_inicio" value="<?= $plan['fecha_inicio'] ?>" required>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="fecha_fin">Fecha Fin</label>
                                                  <input type="date" class="form-control" name="fecha_fin" value="<?= $plan['fecha_fin'] ?>" required>
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-success">Guardar Cambios</button>
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Modal para crear plan de accion -->
    <div class="modal fade" id="modalCrearPlan" tabindex="-1" role="dialog" aria-labelledby="modalCrearPlanLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <form action="index.php?entity=hallazgo&action=planes_accion&id=<?= $hallazgo['id'] ?>" method="POST">
            <input type="hidden" name="action_plan" value="create">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalCrearPlanLabel">Crear Plan de Acción</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- Campos del formulario -->
              <div class="form-group">
                  <label for="descripcion">Descripción</label>
                  <textarea class="form-control" name="descripcion" rows="3" required></textarea>
              </div>
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="id_usuario">Usuario Responsable</label>
                          <select class="form-control" name="id_usuario" required>
                              <?php foreach ($usuarios as $usuario): ?>
                                  <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre'] ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="id_estado">Estado</label>
                          <select class="form-control" name="id_estado" required>
                              <?php foreach ($estados as $estado): ?>
                                  <option value="<?= $estado['id'] ?>"><?= $estado['nombre'] ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="fecha_inicio">Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" required>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="fecha_fin">Fecha Fin</label>
                          <input type="date" class="form-control" name="fecha_fin" required>
                      </div>
                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Crear</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<!-- Scripts de jQuery, Bootstrap para los modales -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>