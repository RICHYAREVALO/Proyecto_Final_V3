<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pazysalvo_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar variables
$mensaje = $error = "";

// Verificar si se ha enviado el formulario de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["departamento_id"])) {
    $departamento_id = intval($_POST["departamento_id"]);

    // Eliminar el departamento de la base de datos
    $sql = "DELETE FROM departamentos WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $departamento_id);

    if ($stmt->execute()) {
        $mensaje = "Departamento eliminado exitosamente.";
    } else {
        $error = "Error al eliminar el departamento: " . $conn->error;
    }

    $stmt->close();

    // Cerrar la conexión a la base de datos
    $conn->close();

    // Redirigir a la lista de departamentos después de 1.5 segundos
    if ($mensaje) {
        echo "<script>
            setTimeout(function() {
                window.location.href = 'listar_departamentos.php';
            }, 1500);
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Departamento</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-3">Eliminar Departamento</h2>
        <?php if (isset($mensaje)) : ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="departamento_id" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
            <p>¿Estás seguro de que quieres eliminar este departamento?</p>
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
        <!-- Botón para regresar a la lista de departamentos -->
        <a href="listar_departamentos.php" class="btn btn-secondary mt-3">Cancelar</a>
    </div>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
