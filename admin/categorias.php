<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
require_once("../config/database.php");

// Crear categoría
if (isset($_POST['nueva_categoria'])) {
    $nombre = trim($_POST['nombre']);
    if ($nombre != "") {
        $stmt = $conn->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: categorias.php");
    exit;
}

// Editar categoría
if (isset($_POST['editar_categoria'])) {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    if ($nombre != "") {
        $stmt = $conn->prepare("UPDATE categorias SET nombre=? WHERE id=?");
        $stmt->bind_param("si", $nombre, $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: categorias.php");
    exit;
}

// Eliminar categoría
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM categorias WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: categorias.php");
    exit;
}

// Obtener todas las categorías
$categorias = $conn->query("SELECT * FROM categorias ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías - Admin Chocopasteles</title>
    <link rel="stylesheet" href="../assets/css/styles.php">
</head>
<body>
<?php include("includes/sidebar.php"); ?>
<main class="dashboard">
    <h2>Categorías</h2>
    <form method="POST" style="margin-bottom:20px;">
        <input type="text" name="nombre" placeholder="Nueva categoría" required>
        <button type="submit" name="nueva_categoria">Agregar</button>
    </form>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php while($cat = $categorias->fetch_assoc()): ?>
        <tr>
            <form method="POST">
                <td><?= $cat['id'] ?></td>
                <td>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($cat['nombre']) ?>" required>
                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                </td>
                <td>
                    <button type="submit" name="editar_categoria">Editar</button>
                    <a href="?eliminar=<?= $cat['id'] ?>" onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</a>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</main>
</body>
</html>