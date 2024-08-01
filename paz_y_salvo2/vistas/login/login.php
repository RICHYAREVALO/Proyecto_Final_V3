<?php
session_start();

// Establece la conexión a la base de datos
$servername = "bkqu3uk3ewxyehltqf2t-mysql.services.clever-cloud.com";
$username = "uwwounruhaizndvh";
$password = "91JGBP3BP37TC6be2NIi";
$dbname = "bkqu3uk3ewxyehltqf2t";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica si hay errores en la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta la base de datos para verificar las credenciales del usuario
    $stmt = $conn->prepare("SELECT ID, Contrasena, Rol FROM usuarios_empleados WHERE NombreUsuario = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $stored_password, $role);
        $stmt->fetch();

        // Verifica si la contraseña ingresada coincide con la contraseña almacenada en la base de datos
        if (password_verify($password, $stored_password)) {
            // Las credenciales son válidas, inicio de sesión exitoso
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Redirige según el rol del usuario
            if ($role === 'administrador') {
                header('Location: ../admin/admin.php'); // Redirige a la página de administración para administradores
            } elseif ($role === 'empleado') {
                header('Location: ../empleado/empleados.php'); // Redirige a la página de perfil de empleado
            } elseif ($role === 'recursos_humanos') {
                header('Location: ../recursos_humanos/recurso_humano.php'); // Redirige a la página de recursos humanos
            } else {
                // Agrega lógica adicional para otros roles si es necesario
                header('Location: otra_pagina.php');
            }

            exit;
        } else {
            // Contraseña incorrecta, muestra un mensaje de error
            $error = "Contraseña incorrecta. Por favor, verifica tus credenciales.";
        }
    } else {
        // Nombre de usuario no encontrado, muestra un mensaje de error
        $error = "Usuario no encontrado. Por favor, verifica tus credenciales.";
    }

    // Cierre de la conexión a la base de datos
    $stmt->close();
}

// Cierra la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de inicio de sesión</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['username'])) : ?>
            <h2 class="welcome-text">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <?php else : ?>
            <h2 class="error-text">Error de inicio de sesión</h2>
            <?php if (isset($error)) : ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <a href="../../index.php" class="login-link">Volver al inicio de sesión</a>
        <?php endif; ?>
    </div>
</body>
</html>
