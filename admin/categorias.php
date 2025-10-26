<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
require_once(__DIR__ . "/../config/database.php");

// Crear categoría
if (isset($_POST['nueva_categoria'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    if ($nombre !== "") {
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
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    if ($id > 0 && $nombre !== "") {
        $stmt = $conn->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $nombre, $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: categorias.php");
    exit;
}

// Eliminar categoría (mejor con POST, pero mantengo GET por compatibilidad)
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: categorias.php");
    exit;
}

// Obtener todas las categorías
$categorias = $conn->query("SELECT * FROM categorias ORDER BY id ASC");
if (!$categorias) {
    die("Error al obtener categorías: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías - Admin Chocopasteles</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<?php include __DIR__ . "/includes/sidebar.php"; ?>
<main class="dashboard">
    <h2>Categorías</h2>

    <form method="POST" style="margin-bottom:20px;">
        <input type="text" name="nombre" placeholder="Nueva categoría" required>
        <button type="submit" name="nueva_categoria">Crear</button>
    </form>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%;max-width:800px;">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php while($cat = $categorias->fetch_assoc()): ?>
            <tr>
                <td><?php echo (int)$cat['id']; ?></td>
                <td><?php echo htmlspecialchars($cat['nombre']); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo (int)$cat['id']; ?>">
                        <input type="text" name="nombre" value="<?php echo htmlspecialchars($cat['nombre']); ?>" required>
                        <button type="submit" name="editar_categoria">Editar</button>
                    </form>
                    <a href="categorias.php?eliminar=<?php echo (int)$cat['id']; ?>" onclick="return confirm('Eliminar categoría?')">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</main>
</body>
</html>