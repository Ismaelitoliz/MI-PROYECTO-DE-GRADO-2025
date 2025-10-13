<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Chocopasteles</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <?php include("includes/sidebar.php"); ?>
  <main class="dashboard">
    <h1>¡GESTIONA TUS PRODUCTOS TU MISMO!</h1>
    <p>Selecciona una opción del menú lateral.</p>
  </main>
</body>
</html>
