<?php
session_start();

// Datos de conexión a la base de datos
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Verificar que se ha enviado el ID del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Preparar la consulta de eliminación
    $stmt = $conn->prepare("DELETE FROM usuarios_empleados WHERE ID = ?");
    $stmt->bind_param("i", $id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a admin.php después de eliminar
        header('Location: admin.php');
        exit();
    } else {
        echo "Error al eliminar el usuario: " . $stmt->error;
    }

    $stmt->close();
} else {
    
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h3>Eliminar Usuario</h3>
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> ¿Estás seguro de que deseas eliminar este usuario? Esta acción es irreversible.
        </div>

        <div class="mt-3">
            <a href="admin.php" class="btn btn-secondary me-2"><i class="bi bi-arrow-left"></i> Volver</a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"><i class="bi bi-trash"></i> Eliminar Usuario</button>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este usuario? Esta acción es irreversible.
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" action="eliminar_usuario.php">
                        <input type="hidden" name="id" id="userIdToDelete">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Obtener el ID del usuario desde los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');

    // Manejar el evento de apertura del modal
    var confirmDeleteModal = document.getElementById('confirmDeleteModal');
    confirmDeleteModal.addEventListener('show.bs.modal', function () {
        var userIdInput = document.getElementById('userIdToDelete');
        userIdInput.value = userId;
    });
    </script>
</body>
</html>
