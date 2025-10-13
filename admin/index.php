<?php
session_start();
require_once("../config/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $clave = $_POST['clave'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (password_verify($clave, $row['password'])) {
            $_SESSION['admin'] = $row['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El correo no está registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login Administrador</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-page">
  <div class="login-container">
    <h2>Acceso Administrador</h2>
    <?php if (isset($error)) echo "<script>Swal.fire('Error', '$error', 'error')</script>"; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Correo electrónico" required>
      <input type="password" name="clave" placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
    </form>
  </div>
</body>
</html>
