<?php
session_start();

// Datos de conexión a la base de datos
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Inicializar variables de búsqueda
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// Paginación
$limit = 10; // Número de departamentos por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Consulta SQL con filtro de búsqueda y límite
$sql = "SELECT * FROM departamentos WHERE Nombre LIKE ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("sii", $search_param, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Obtener los datos de los departamentos
$departamentos = ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Contar el número total de departamentos para la paginación
$count_sql = "SELECT COUNT(*) AS total FROM departamentos WHERE Nombre LIKE ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("s", $search_param);
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
    <title>Departamentos Beyonder</title>
    <link rel="stylesheet" href="../admin/admin.css">
    <style>
        .custom-img {
            max-width: 100%;
            height: auto; /* Mantiene la proporción original de la imagen */
        }
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
        .container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Sistema Departamentos</div>
        <div class="user-info">
            Bogota, <?php echo date('d \d\e F \d\e Y'); ?> | <?php echo htmlspecialchars($username); ?>
            <span class="user-icon">👤</span>
            <a href="../login/logout.php" class="logout-icon">⏻</a>
        </div>
    </header>
    <nav class="main-nav">
        <ul>
            <li><a href="../admin/admin.php" class="active">USUARIOS</a></li>
            <li><a href="../departamentos/listar_departamentos.php">DEPARTAMENTOS</a></li>
        </ul>
    </nav>
    <main>
       
        
        <h1>Lista de Departamentos</h1>

        <!-- Formulario de búsqueda -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="search-form mb-4">
            <input type="text" name="search" placeholder="Buscar por nombre del departamento" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Buscar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Departamento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departamentos as $departamento) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($departamento['ID']); ?></td>
                        <td><?php echo htmlspecialchars($departamento['Nombre']); ?></td>
                        <td>
                            <a href="editar_departamento.php?id=<?php echo $departamento['ID']; ?>" class="edit">Editar</a> |
                            <a href="eliminar_departamento.php?id=<?php echo $departamento['ID']; ?>" class="delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este departamento?')">Eliminar</a>
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

        <a href="crear_departamento.php" class="create-user">Crear Nuevo Departamento</a>
    </main>
</body>
</html>
