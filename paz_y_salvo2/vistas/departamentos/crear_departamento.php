<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paz_y_salvo2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario de creación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_departamento"])) {
    $nombre_departamento = $_POST["nombre_departamento"];
    $descripcion = $_POST["descripcion"]; // Nuevo campo de descripción

    // Insertar el nuevo departamento en la base de datos
    $sql = "INSERT INTO departamentos (Nombre_Departamento, Descripcion) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nombre_departamento, $descripcion);

    if ($stmt->execute()) {
        // Redireccionar a la lista de departamentos
        header('Location: listar_departamentos.php');
        exit;
    } else {
        echo "Error al crear el departamento: " . $conn->error;
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
    <title>Crear Departamento</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-3">Crear Nuevo Departamento</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="nombre_departamento" class="form-label">Nombre del Departamento:</label>
                <input type="text" name="nombre_departamento" class="form-control" required>
            </div>
            <!-- Nuevo campo de descripción -->
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
        <!-- Agregar botón para volver a la lista de departamentos -->
        <a href="listar_departamentos.php" class="btn btn-secondary mt-3">Volver a la lista de departamentos</a>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
