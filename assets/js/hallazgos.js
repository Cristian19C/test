
$(document).ready(function () {
  // Variable para controlar el temporizador del mensaje
  let messageTimer;

  // Función para mostrar mensajes
  function showMessage(message, type) {
    // Limpiar temporizador anterior si existe
    if (messageTimer) {
      clearTimeout(messageTimer);
    }

    const alertEl = $('#alertMessage');

    // Establecer tipo y mensaje
    alertEl.removeClass('alert-success alert-danger alert-warning')
      .addClass('alert-' + type)
      .html(message)
      .fadeIn();

    // Configurar temporizador para ocultar el mensaje
    messageTimer = setTimeout(function () {
      alertEl.fadeOut();
    }, 3000);
  }

  // Manejar cambio en el selector de estado
  $('.estado-select').change(function () {
    const select = $(this);
    const hallazgoId = select.data('hallazgo-id');
    const estadoActual = select.data('estado-actual');
    const nuevoEstadoId = select.val();
    const spinner = $('#spinner-' + hallazgoId);

    // No hacer nada si se selecciona el mismo estado
    if (nuevoEstadoId == estadoActual) {
      return;
    }

    // Confirmar cambio de estado
    if (!confirm('¿Esta seguro de cambiar el estado del hallazgo?')) {
      // Restaurar valor anterior si se cancela
      select.val(estadoActual);
      return;
    }

    // Desactivar selector y mostrar spinner durante la actualización
    select.prop('disabled', true).addClass('estado-updating');
    spinner.show();

    // Enviar solicitud AJAX
    $.ajax({
      url: 'index.php?entity=hallazgo&action=updateEstado&id=' + hallazgoId,
      method: 'POST',
      data: { id_estado: nuevoEstadoId },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          // Actualizar el estado actual en el data attribute
          select.data('estado-actual', nuevoEstadoId);

          // Mostrar mensaje de éxito
          showMessage('<i class="fas fa-check-circle"></i> ' + response.message, 'success');
        } else {
          // Mostrar mensaje de error
          showMessage('<i class="fas fa-exclamation-triangle"></i> ' + response.message, 'danger');

          // Revertir el cambio en el selector
          select.val(estadoActual);
        }
      },
      error: function () {
        // Mostrar mensaje de error
        showMessage('<i class="fas fa-exclamation-triangle"></i> Error de conexión. Intente nuevamente.', 'danger');

        // Revertir el cambio en el selector
        select.val(estadoActual);
      },
      complete: function () {
        // Habilitar selector y ocultar spinner cuando se complete
        select.prop('disabled', false).removeClass('estado-updating');
        spinner.hide();
      }
    });
  });
});
