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

// Inicializar variables
$mensaje = $error = "";
$departamento_id = $nuevo_nombre_departamento = $nueva_descripcion = "";

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["departamento_id"], $_POST["nuevo_nombre_departamento"], $_POST["nueva_descripcion"])) {
    $departamento_id = $_POST["departamento_id"];
    $nuevo_nombre_departamento = $_POST["nuevo_nombre_departamento"];
    $nueva_descripcion = $_POST["nueva_descripcion"];

    // Actualizar el nombre del departamento y la descripción en la base de datos
    $sql = "UPDATE departamentos SET Nombre_Departamento = ?, Descripcion = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nuevo_nombre_departamento, $nueva_descripcion, $departamento_id);

    if ($stmt->execute()) {
        $mensaje = "Nombre del departamento y descripción actualizados exitosamente.";
    } else {
        $error = "Error al actualizar el nombre del departamento y descripción: " . $conn->error;
    }

    $stmt->close();
} elseif (isset($_GET['id'])) {
    // Obtener el ID del departamento de la URL
    $departamento_id = $_GET['id'];

    // Consulta SQL para obtener los datos actuales del departamento
    $sql = "SELECT Nombre_Departamento, Descripcion FROM departamentos WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $departamento_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Obtener los datos del departamento
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nuevo_nombre_departamento = $row["Nombre_Departamento"];
        $nueva_descripcion = $row["Descripcion"];
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
    <div class="container">
        <h2 class="mt-3">Actualizar Nombre y Descripción del Departamento</h2>
        <?php if ($mensaje) : ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
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
            <!-- Nuevo campo de descripción -->
            <div class="mb-3">
                <label for="nueva_descripcion" class="form-label">Nueva Descripción:</label>
                <textarea name="nueva_descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($nueva_descripcion); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
        <!-- Botón para regresar a la lista de departamentos -->
        <a href="listar_departamentos.php" class="btn btn-secondary mt-3">Regresar a la lista de departamentos</a>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
