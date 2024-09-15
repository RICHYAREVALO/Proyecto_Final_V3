<?php
session_start();

// Datos de conexión a la base de datos (Recomendado moverlos a un archivo separado)
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

// Establece la conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica si hay errores en la conexión
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]);
    exit();
}

// Verifica que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lee los datos del cuerpo de la solicitud
    $input = file_get_contents('php://input');
    
    // Verifica si los datos vienen en formato JSON o x-www-form-urlencoded
    if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode($input, true);
    } else {
        parse_str($input, $data);
    }

    // Verifica que se hayan enviado las credenciales
    if (isset($data['username']) && isset($data['password'])) {
        $username = trim($data['username']);
        $password = trim($data['password']);

        // Verifica que los campos no estén vacíos
        if (empty($username) || empty($password)) {
            echo json_encode(["success" => false, "message" => "Nombre de usuario o contraseña no proporcionados."]);
            exit();
        }

        // Prepara la consulta para verificar las credenciales
        $stmt = $conn->prepare("SELECT ID, Contrasena, Rol FROM usuarios_empleados WHERE NombreUsuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Si se encuentra el usuario, verifica la contraseña
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $stored_password, $role);
            $stmt->fetch();

            // Verifica la contraseña
            if (password_verify($password, $stored_password)) {
                // Inicio de sesión exitoso, crea la sesión
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Respuesta exitosa
                echo json_encode([
                    "success" => true,
                    "message" => "Inicio de sesión exitoso",
                    "role" => $role
                ]);
            } else {
                // Contraseña incorrecta
                echo json_encode(["success" => false, "message" => "Nombre de usuario o contraseña incorrectos."]);
            }
        } else {
            // Usuario no encontrado
            echo json_encode(["success" => false, "message" => "Nombre de usuario o contraseña incorrectos."]);
        }
        $stmt->close();
    } else {
        // Si no se proporcionan datos de inicio de sesión
        echo json_encode(["success" => false, "message" => "Solicitud inválida."]);
    }
}

// Cierra la conexión
$conn->close();
?>
