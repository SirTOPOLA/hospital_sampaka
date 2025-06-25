<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 

$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? 'sin_permiso'));
 
 
 
?>
 
<!-- Main Content -->
<div id="content" class="container-fluid py-4 mt-4">

    <h2 class="mb-4 mt-4">Resumen de Actividad</h2>
    <div class="row">
        
    </div>
</div>