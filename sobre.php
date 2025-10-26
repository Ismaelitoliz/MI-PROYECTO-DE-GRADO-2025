<?php
session_start();
require_once("config/database.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Chocopasteles - Inicio</title>
  <?php include __DIR__ . "/includes/head.php"; ?>
</head>

<body>
<header>
   <?php include("includes/header.php"); ?>
   <link rel="stylesheet" href="assets/css/styles.css">
</header>
<main class="sobre-main">
  <section class="sobre-intro">
    <div class="sobre-texto">
      <h1>Sobre Chocopasteles</h1>
      <p class="sobre-descripcion">
        Chocopasteles nació del amor por la repostería y el deseo de endulzar los momentos especiales de cada familia.
        Somos una tienda dedicada a la elaboración de tortas, postres y dulces artesanales, usando ingredientes frescos y recetas tradicionales.
      </p>
      <img src="assets/img/1759068802_MIL HOJAS.webp" alt="Postre 1">
    </div>
    <div class="sobre-texto">
      <h1>Pasión por lo que hacemos</h1>
      <p>
      Cada postre está elaborado con dedicación artesanal y un profundo respeto por la repostería clásica, buscando siempre transmitir alegría y dulzura en cada bocado. Para nosotros, hornear es un acto de amor y una forma de celebrar los pequeños grandes momentos de la vida.🍰✨
      </p>
    </div>
  </section>

  <section class="sobre-valores">
    <div class="valor">
      <h2>Compromiso social</h2>
      <p>Nos comprometemos a trabajar con proveedores locales para impulsar la economía de nuestra comunidad y a implementar prácticas sostenibles en nuestro proceso de producción para reducir el desperdicio.
    </div>
    <div class="valor">
      <h2>Misión</h2>
      <p>Cada postre está elaborado con dedicación artesanal y un profundo respeto por la repostería clásica, buscando siempre transmitir alegría y dulzura en cada bocado. Para nosotros, hornear es un acto de amor y una forma de celebrar los pequeños grandes momentos de la vida.</p>
    </div>
    <div class="valor">
      <h2>Visión</h2>
      <p>Ser la pastelería favorita de nuestra ciudad, reconocida por la creatividad, la calidad inquebrantable y el cariño genuino que se pone en cada postre, logrando expandir nuestra dulzura a nuevos hogares.
    </div>
  </section>
</main>

<footer style="background:#5D4037;color:#fff;">
<?php include("includes/footer.php"); ?>
</footer>

<!-- Carrito flotante -->
<div id="carrito-flotante">
  <div id="carrito-header">
    <span>🛒 Carrito (<span id="carrito-cantidad">0</span>)</span>
    <button id="carrito-cerrar">×</button>
  </div>
  <div id="carrito-lista"></div>
  <div id="carrito-total"></div>
  <button id="carrito-whatsapp">Enviar por WhatsApp</button>
</div>
<button id="carrito-toggle">🛒</button>
<script src="assets/js/main.js"></script>

<script>
// Productos desde PHP para JS
const productosData = {};
<?php
$prods = $conn->query("SELECT id, nombre, precio FROM productos");
while($p = $prods->fetch_assoc()):
?>
productosData[<?= $p['id'] ?>] = {
  nombre: "<?= addslashes($p['nombre']) ?>",
  precio: <?= $p['precio'] ?>
};
<?php endwhile; ?>

let carrito = JSON.parse(localStorage.getItem('carritoChoco')) || {};

function actualizarCarrito() {
  let lista = '';
  let total = 0;
  let cantidad = 0;
  for (let id in carrito) {
    let prod = productosData[id];
    let cant = carrito[id];
    total += prod.precio * cant;
    cantidad += cant;
    lista += `<div>
      <b>${prod.nombre}</b> x${cant} - S/ ${(prod.precio * cant).toFixed(2)}
      <button onclick="cambiarCantidad(${id},-1)">-</button>
      <button onclick="cambiarCantidad(${id},1)">+</button>
      <button onclick="eliminarProducto(${id})">🗑️</button>
    </div>`;
  }
  document.getElementById('carrito-lista').innerHTML = lista || '<em>Carrito vacío</em>';
  document.getElementById('carrito-total').innerHTML = 'Total: S/ ' + total.toFixed(2);
  document.getElementById('carrito-cantidad').textContent = cantidad;
}
function cambiarCantidad(id, delta) {
  carrito[id] = (carrito[id] || 0) + delta;
  if (carrito[id] <= 0) delete carrito[id];
  localStorage.setItem('carritoChoco', JSON.stringify(carrito));
  actualizarCarrito();
}
function eliminarProducto(id) {
  delete carrito[id];
  localStorage.setItem('carritoChoco', JSON.stringify(carrito));
  actualizarCarrito();
}
document.querySelectorAll('.agregar-carrito').forEach(btn => {
  btn.onclick = () => {
    let id = btn.dataset.id;
    carrito[id] = (carrito[id] || 0) + 1;
    localStorage.setItem('carritoChoco', JSON.stringify(carrito));
    actualizarCarrito();
    document.getElementById('carrito-flotante').style.display = 'block';
  };
});
document.getElementById('carrito-toggle').onclick = () => {
  document.getElementById('carrito-flotante').style.display = 'block';
};
document.getElementById('carrito-cerrar').onclick = () => {
  document.getElementById('carrito-flotante').style.display = 'none';
};
document.getElementById('carrito-whatsapp').onclick = () => {
  let mensaje = "¡Hola! Quiero pedir:\n";
  for (let id in carrito) {
    let prod = productosData[id];
    mensaje += `• ${prod.nombre} x${carrito[id]} - S/ ${(prod.precio * carrito[id]).toFixed(2)}\n`;
  }
  mensaje += "\nTotal: S/ " + Object.keys(carrito).reduce((t, id) => t + productosData[id].precio * carrito[id], 0).toFixed(2);
  window.open("https://wa.me/59164939922?text=" + encodeURIComponent(mensaje), "_blank");
  carrito = {};
  localStorage.removeItem('carritoChoco');
  actualizarCarrito();
  document.getElementById('carrito-flotante').style.display = 'none';
};
actualizarCarrito();
</script>
 <?php include __DIR__ . "/includes/adminModal.php"; ?>
</body>
</html>