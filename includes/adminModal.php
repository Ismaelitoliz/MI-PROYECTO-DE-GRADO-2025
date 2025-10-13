<div id="adminLoginModal" style="display:none;position:fixed;z-index:2000;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:30px 24px;border-radius:12px;max-width:340px;width:90%;margin:auto;position:relative;">
        <button onclick="document.getElementById('adminLoginModal').style.display='none'" style="position:absolute;top:8px;right:12px;background:none;border:none;font-size:1.5em;color:#5D4037;">×</button>
        <h2 style="color:#5D4037;text-align:center;">Acceso Administrador</h2>
        <form method="POST" action="admin/index.php">
            <input type="text" name="usuario" placeholder="Usuario" required style="width:100%;padding:8px;margin-bottom:10px;">
            <input type="password" name="clave" placeholder="Contraseña" required style="width:100%;padding:8px;margin-bottom:10px;">
            <button type="submit" style="width:100%;padding:10px;background:#5D4037;color:#fff;border:none;border-radius:8px;">Ingresar</button>
        </form>
    </div>
</div>