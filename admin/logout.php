<?php
session_start();
session_destroy();
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  icon: 'success',
  title: 'Sesión cerrada',
  text: 'Has cerrado sesión correctamente',
  timer: 2000,
  showConfirmButton: false
}).then(() => {
  window.location.href = "../index.php";
});
</script>
