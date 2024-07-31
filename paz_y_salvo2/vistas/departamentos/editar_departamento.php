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
$departamento_id = $nuevo_nombre_departamento = "";

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["departamento_id"], $_POST["nuevo_nombre_departamento"])) {
    $departamento_id = intval($_POST["departamento_id"]);
    $nuevo_nombre_departamento = trim($_POST["nuevo_nombre_departamento"]);

    // Actualizar el nombre del departamento en la base de datos
    $sql = "UPDATE departamentos SET Nombre = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_nombre_departamento, $departamento_id);

    if ($stmt->execute()) {
        $mensaje = "Nombre del departamento actualizado exitosamente.";
    } else {
        $error = "Error al actualizar el nombre del departamento: " . $conn->error;
    }

    $stmt->close();
} elseif (isset($_GET['id'])) {
    // Obtener el ID del departamento de la URL
    $departamento_id = intval($_GET['id']);

    // Consulta SQL para obtener los datos actuales del departamento
    $sql = "SELECT Nombre FROM departamentos WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $departamento_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Obtener los datos del departamento
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nuevo_nombre_departamento = htmlspecialchars($row["Nombre"]);
    } else {
        $error = "No se encontró el departamento con el ID especificado.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Departamento</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Actualizar Nombre del Departamento</h2>
        <?php if ($mensaje) : ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
            <script>
                setTimeout(function() {
                    window.location.href = 'listar_departamentos.php';
                }, 1500);
            </script>
        <?php endif; ?>
        <?php if ($error) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="departamento_id" value="<?php echo htmlspecialchars($departamento_id); ?>">
            <div class="mb-3">
                <label for="nuevo_nombre_departamento" class="form-label">Nuevo Nombre del Departamento:</label>
                <input type="text" name="nuevo_nombre_departamento" class="form-control" value="<?php echo htmlspecialchars($nuevo_nombre_departamento); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
        <!-- Botón para regresar a la lista de departamentos -->
        <a href="listar_departamentos.php" class="btn btn-secondary mt-3">Regresar a la lista de departamentos</a>
    </div>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
