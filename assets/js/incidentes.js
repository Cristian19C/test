
// Validar que solo se ingresen numeros en el campo de busqueda
document.getElementById('busqueda_id').addEventListener('input', function (e) {
  // Eliminar cualquier caracter que no sea numero
  this.value = this.value.replace(/[^0-9]/g, '');
});

// Crear un focus en el campo de busqueda al cargar la pagina
document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('busqueda_id').focus();
});
