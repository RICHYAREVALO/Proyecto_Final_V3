<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pazysalvo_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

$sql = "SELECT * FROM departamentos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $departamentos = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $departamentos = array();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departamentos Beyonder</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-img {
            width: 300px; /* Cambia el tamaño de la imagen según lo necesites */
            height: 300px; /* Cambia la altura de la imagen según lo necesites */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Areas Beyonder</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" href="../admin/admin.php">Lista de Usuarios</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="../login/logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container text-center">
        <img src="../../imagen/beyonder.jpeg" alt="Imagen de fondo" class="img-fluid rounded mb-4 custom-img">
    </div>
        <h2 class="mt-4">Bienvenido, <?php echo isset($username) ? htmlspecialchars($username) : ''; ?>!</h2>
        <h3 class="mt-3">Lista de Departamentos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Departamento</th>
                    <!-- Agrega más columnas si es necesario -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departamentos as $departamento) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($departamento['ID']); ?></td>
                        <td><?php echo htmlspecialchars($departamento['Nombre']); ?></td>
                        <!-- Agrega más columnas si es necesario -->
                        <td>
                            <a href="editar_departamento.php?id=<?php echo $departamento['ID']; ?>" class="btn btn-primary">Editar</a>
                            <a href="eliminar_departamento.php?id=<?php echo $departamento['ID']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este departamento?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="crear_departamento.php" class="btn btn-success">Crear Nuevo Departamento</a>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
