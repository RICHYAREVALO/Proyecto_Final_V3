<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "paz_y_salvo2";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Validating the ID
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        echo "ID de usuario no válido";
        exit;
    }

    // Verifying if the ID exists in the database
    $check_stmt = $conn->prepare("SELECT ID FROM Usuarios WHERE ID = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows === 0) {
        echo "ID de usuario no encontrado en la base de datos";
        exit;
    }

    // Verifying if there are employees associated with this user
    $check_employees_stmt = $conn->prepare("SELECT ID FROM Empleados WHERE Usuario_ID = ?");
    $check_employees_stmt->bind_param("i", $id);
    $check_employees_stmt->execute();
    $check_employees_stmt->store_result();

    if ($check_employees_stmt->num_rows > 0) {
        echo "No puedes eliminar este usuario porque tiene empleados asociados. Elimina los empleados primero.";
        exit;
    }

    // Deleting the user
    $delete_stmt = $conn->prepare("DELETE FROM Usuarios WHERE ID = ?");
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        // Showing success message
        echo "Usuario eliminado exitosamente";

        // Redirecting with an appropriate HTTP response code
        header('Location: admin.php', true, 303);
        exit;
    } else {
        echo "Error al procesar la solicitud: " . $delete_stmt->error;
    }

    $check_stmt->close();
    $check_employees_stmt->close();
    $delete_stmt->close();
    $conn->close();
} else {
    echo "Acceso no autorizado";
}
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
    <div class="container">
        <h3 class="mt-4">Eliminar Usuario</h3>
        <div class="alert alert-warning mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> No puedes eliminar este usuario porque tiene empleados asociados. Elimina los empleados primero.!
        </div>
        <div class="mt-3">
            <a href="admin.php" class="btn btn-secondary me-2"><i class="bi bi-arrow-left"></i> Volver</a>
            <button id="confirmDeleteBtn" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar Usuario</button>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (confirm('No puedes eliminar este usuario porque tiene empleados asociados. ¿Deseas ir a la lista de empleados?')) {
            window.location.href = 'lista_empleados.php';
        }
    });
</script>
</body>
</html>
