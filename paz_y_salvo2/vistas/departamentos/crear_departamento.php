<?php
// Conexión a la base de datos
$servername = "bkqu3uk3ewxyehltqf2t-mysql.services.clever-cloud.com";
$username = "uwwounruhaizndvh";
$password = "91JGBP3BP37TC6be2NIi";
$dbname = "bkqu3uk3ewxyehltqf2t";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar variables
$mensaje = $error = "";

// Verificar si se ha enviado el formulario de creación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_departamento"])) {
    $nombre_departamento = trim($_POST["nombre_departamento"]);

    // Insertar el nuevo departamento en la base de datos
    $sql = "INSERT INTO departamentos (Nombre) VALUES (?)"; // Usar el nombre de columna correcto
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre_departamento);

    if ($stmt->execute()) {
        $mensaje = "Departamento creado exitosamente.";
        // Redirigir después de un breve retraso para mostrar el mensaje
        echo "<script>
            setTimeout(function() {
                window.location.href = 'listar_departamentos.php';
            }, 1500);
        </script>";
    } else {
        $error = "Error al crear el departamento: " . $conn->error;
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
    <div class="container mt-4">
        <h2>Crear Nuevo Departamento</h2>
        <?php if ($mensaje) : ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <?php if ($error) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="nombre_departamento" class="form-label">Nombre del Departamento:</label>
                <input type="text" name="nombre_departamento" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
        <!-- Agregar botón para volver a la lista de departamentos -->
        <a href="listar_departamentos.php" class="btn btn-secondary mt-3">Volver a la lista de departamentos</a>
    </div>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
