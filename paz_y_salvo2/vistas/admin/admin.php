<?php
session_start();

// Datos de conexión a la base de datos
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Redirigir si no hay sesión activa
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Inicializar variables de búsqueda
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// Paginación
$limit = 10; // Número de usuarios por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Consulta SQL con filtro de búsqueda y límite
$sql = "SELECT u.*, t.tipodocumento, d.Nombre AS NombreDepartamento
        FROM usuarios_empleados u
        LEFT JOIN tipodocumento t ON u.tipodocumento_ID = t.ID
        LEFT JOIN departamentos d ON u.departamento_ID = d.ID
        WHERE u.Nombre LIKE ? 
           OR u.Apellido LIKE ? 
           OR u.NombreUsuario LIKE ?
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("sssii", $search_param, $search_param, $search_param, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Obtener los datos de los usuarios
$usuarios = ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Contar el número total de usuarios para la paginación
$count_sql = "SELECT COUNT(*) AS total FROM usuarios_empleados u
              WHERE u.Nombre LIKE ? 
                 OR u.Apellido LIKE ? 
                 OR u.NombreUsuario LIKE ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("sss", $search_param, $search_param, $search_param);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Cerrar conexión
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistemam Usuarios</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        /* Agregar estilos para la paginación */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #005466;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #005466;
            color: white;
        }
        .pagination a:hover {
            background-color: #004d61;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Sistema Usuarios</div>
        <div class="user-info">
            Bogota, <?php echo date('d \d\e F \d\e Y'); ?> | <?php echo htmlspecialchars($username); ?>
            <span class="user-icon">👤</span>
            <a href="../login/logout.php" class="logout-icon">⏻</a>
        </div>
    </header>
    <nav class="main-nav">
        <ul>
            <li><a href="#" class="active">USUARIOS</a></li>
            <li><a href="../departamentos/listar_departamentos.php">DEPARTAMENTOS</a></li>
        </ul>
    </nav>

    <main>
        <h1>Lista de usuarios</h1>

        <!-- Formulario de búsqueda -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="search-form">
            <input type="text" name="search" placeholder="Buscar por nombre, apellido o usuario" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Buscar</button>
        </form>

        <a href="crear_usuario.php" class="create-user">Crear usuario</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Documento Identidad</th>
                    <th>Area</th>
                    <th>Nombre Usuario</th>
                    <th>Rol</th>
                    <th>Tipo Documento</th>
                    <th>Correo Electrónico</th>
                    <th>Fecha Contratación</th>
                    <th>Fecha Retiro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['ID']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['Nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['Apellido']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['DocumentoIdentidad']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['NombreDepartamento']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['NombreUsuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['Rol']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['tipodocumento']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['CorreoElectronico']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['FechaContratacion']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['FechaRetiro']); ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $usuario['ID']; ?>" class="edit">Editar</a> |
                            <a href="eliminar_usuario.php?id=<?php echo $usuario['ID']; ?>" class="delete">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">« Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Siguiente »</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
