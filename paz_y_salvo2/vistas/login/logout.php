<?php
session_start();

// Cerrar la sesión
session_destroy();

// Redirigir al usuario al inicio de sesión
header('Location: ../../index.php');
exit;
?>
