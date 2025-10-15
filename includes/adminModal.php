<?php
// 🔹 Iniciar sesión solo si aún no se ha iniciado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔹 Conectar con la base de datos (ruta corregida)
require_once(__DIR__ . "/../config/database.php");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_admin'])) {
    $email = trim($_POST['email'] ?? '');
    $clave = trim($_POST['clave'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo inválido.";
    } elseif (strlen($clave) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE email=? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows === 1) {
                $row = $res->fetch_assoc();
                if (password_verify($clave, $row['password'])) {
                    $_SESSION['admin'] = $row['id'];
                    echo "<script>window.location.href='admin/dashboard.php';</script>";
                    exit;
                } else {
                    $error = "Contraseña incorrecta.";
                }
            } else {
                $error = "El correo no está registrado.";
            }
            $stmt->close();
        }
    }
}
?>

<!-- 🔹 Modal de login de administrador -->
<div id="adminLoginModal" style="display:none;position:fixed;z-index:2000;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:30px 24px;border-radius:12px;max-width:340px;width:90%;margin:auto;position:relative;">
        <button onclick="document.getElementById('adminLoginModal').style.display='none'"
                style="position:absolute;top:8px;right:12px;background:none;border:none;font-size:1.5em;color:#5D4037;">×</button>
        <h2 style="color:#5D4037;text-align:center;">Acceso Administrador</h2>

        <form method="POST">
            <input type="hidden" name="login_admin" value="1">
            <input type="email" name="email" placeholder="Correo electrónico" required 
                   style="width:100%;padding:8px;margin-bottom:10px;border-radius:8px;border:1px solid #ccc;">
            <input type="password" name="clave" placeholder="Contraseña" required 
                   style="width:100%;padding:8px;margin-bottom:10px;border-radius:8px;border:1px solid #ccc;">
            <button type="submit" 
                    style="width:100%;padding:10px;background:#5D4037;color:#fff;border:none;border-radius:8px;font-weight:bold;">
                Ingresar
            </button>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <?php if (!empty($error)) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= $error ?>'
            });
        </script>
        <?php endif; ?>
    </div>
</div>
