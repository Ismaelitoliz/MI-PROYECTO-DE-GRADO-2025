<?php
// depuración temporal
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once(__DIR__ . "/../config/database.php");

if (!isset($conn) || $conn->connect_errno) {
    die("Error conexión BD: " . ($conn->connect_error ?? 'sin $conn'));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $clave = $_POST['clave'] ?? '';

    if (!$email) {
        $error = "Correo inválido.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE email = ? LIMIT 1");
        if (!$stmt) {
            $error = "Error de base de datos: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $id = null; $hash = null;
            if (method_exists($stmt, 'get_result')) {
                $res = $stmt->get_result();
                if ($res && $res->num_rows === 1) {
                    $row = $res->fetch_assoc();
                    $id = $row['id'];
                    $hash = $row['password'];
                }
            } else {
                $stmt->bind_result($id, $hash);
                $stmt->fetch();
            }

            if (empty($hash)) {
                $error = "El correo no está registrado.";
            } else {
                if (password_verify($clave, $hash) || $clave === $hash) {
                    $_SESSION['admin'] = $id;
                    header("Location: dashboard.php");
                    exit;
                }
                $error = "Contraseña incorrecta.";
            }

            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login Administrador</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="login-page">
  <div class="login-container">
    <h2>Acceso Administrador</h2>
    <?php if ($error): ?>
      <div style="color:#c00;margin-bottom:12px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" autocomplete="off">
      <input type="email" name="email" placeholder="Correo electrónico" required>
      <input type="password" name="clave" placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
    </form>
  </div>
</body>
</html>
