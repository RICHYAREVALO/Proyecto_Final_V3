<?php
session_start();

// Datos de conexión a la base de datos
$servername = "bjgxtiqfs78pgiy7qzux-mysql.services.clever-cloud.com";
$username = "udb0mb339gpdtxkh";
$password = "0PRRJnHNJEdZdU9pCHYR";
$dbname = "bjgxtiqfs78pgiy7qzux";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica si hay errores en la conexión
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]);
    exit();
}

// Verifica que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se están enviando datos en formato JSON o x-www-form-urlencoded
    $input = file_get_contents('php://input');
    
    // Si es JSON, decodifícalo
    if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode($input, true);
    } else {
        parse_str($input, $data);
    }
    
    // Verifica si se obtuvieron los datos necesarios
    if (isset($data['username']) && isset($data['password'])) {
        $username = trim($data['username']);
        $password = trim($data['password']);
        
        // Verifica que los campos no estén vacíos
        if (empty($username) || empty($password)) {
            echo json_encode(["success" => false, "message" => "Nombre de usuario o contraseña no proporcionados."]);
            exit();
        }

        // Consulta la base de datos para verificar las credenciales del usuario
        $stmt = $conn->prepare("SELECT ID, Contrasena, Rol FROM usuarios_empleados WHERE NombreUsuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $stored_password, $role);
            $stmt->fetch();

            // Verifica si la contraseña ingresada coincide con la contraseña encriptada
            if (password_verify($password, $stored_password)) {
                // Inicio de sesión exitoso, se configura la sesión
                session_regenerate_id(true); // Regenera el ID de sesión
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Respuesta con éxito y redirección
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
            // Nombre de usuario no encontrado
            echo json_encode(["success" => false, "message" => "Nombre de usuario o contraseña incorrectos."]);
        }
        $stmt->close();
    } else {
        // Datos necesarios no proporcionados
        echo json_encode(["success" => false, "message" => "Nombre de usuario o contraseña no proporcionados."]);
    }
}
//link prueba api 
// http://localhost/Proyecto_Final_V3/paz_y_salvo2/vistas/login/login.php
/*{
    "username": "p1",
    "password": "123"
}
    */
$conn->close();
?>