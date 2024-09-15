<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Hoja de estilos personalizada -->
    <link rel="stylesheet" href="vistas/login/styles.css">
</head>
<body class="custom-background">
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
        <div class="form-container position-relative">
            <!-- Luz animada -->
            <div class="border-glow"></div>

            <!-- Título del formulario -->
            <h2 class="text-center mb-4">Iniciar sesión</h2>
            
            <!-- Imagen de fondo -->
            <img src="imagen/beyonder.jpeg" alt="Imagen de fondo" class="img-fluid rounded mb-4 custom-img">

            <!-- Mensaje de bienvenida -->
            <div id="welcome-message" class="alert alert-success d-none text-center mb-4 welcome-message">
                <strong>Bienvenid@ a Paz y Salvo Beyonder</strong>
            </div>

            <!-- Div para mostrar mensajes de error -->
            <div id="error-message" class="alert alert-danger d-none"></div>
            
            <!-- Formulario de inicio de sesión -->
            <form id="login-form" method="post" autocomplete="off">
                <!-- Campo de usuario -->
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <div class="input-group">
                            <img src="imagen/iconos/userlogin.png" alt="Icono de usuario" class="icono">
                            <span class="input-text">Usuario</span>
                        </div>
                    </label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <!-- Campo de contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <div class="input-group">
                            <img src="imagen/iconos/contraseñaicono.png" alt="Icono de contraseña" class="icono">
                            <span class="input-text">Contraseña</span>
                        </div>
                    </label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <!-- Botón para iniciar sesión -->
                <div class="text-center mb-4">
                    <button type="submit" id="submit-btn" class="btn">Iniciar sesión</button>
                </div>

                <!-- Indicador de carga mientras se procesa la solicitud -->
                <div id="spinner" class="spinner-border text-primary d-none" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </form>

            <!-- Enlaces adicionales -->
            <div class="mt-3 text-center">
                <p class="mb-1"><a href="vistas/registro/registro.html" class="text-blue">¿No tienes una cuenta? Regístrate aquí</a></p>
                <p class="mb-0"><a href="vistas/autenticacion/forgot_password.php" class="text-blue">Olvidé mi contraseña</a></p>
            </div>
        </div>
    </div>

    <!-- jQuery, Popper y Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de manejo del formulario -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mostrar el mensaje de bienvenida
            const welcomeMessage = document.getElementById('welcome-message');
            welcomeMessage.classList.remove('d-none');
            setTimeout(() => {
                welcomeMessage.classList.add('d-none');
            }, 8000); // Oculta el mensaje después de 8 segundos

            document.getElementById('login-form').addEventListener('submit', function(event) {
                event.preventDefault(); // Evita la recarga de la página
                
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                // Deshabilitar el botón y mostrar spinner
                document.getElementById('submit-btn').disabled = true;
                document.getElementById('spinner').classList.remove('d-none');

                // Envío de la solicitud
                fetch('vistas/login/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        username: username,
                        password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Rehabilitar botón y ocultar spinner
                    document.getElementById('submit-btn').disabled = false;
                    document.getElementById('spinner').classList.add('d-none');

                    // Manejo de la respuesta
                    if (data.success) {
                        // Redirección según el rol del usuario
                        switch (data.role) {
                            case 'administrador':
                                window.location.href = "vistas/admin/admin.php";
                                break;
                            case 'empleado':
                                window.location.href = "vistas/empleado/empleados.php";
                                break;
                            case 'recursos_humanos':
                                window.location.href = "vistas/recursos_humanos/recurso_humano.php";
                                break;
                            default:
                                throw new Error('Rol de usuario desconocido.');
                        }
                    } else {
                        // Mostrar mensaje de error
                        const errorMessage = document.getElementById('error-message');
                        errorMessage.classList.remove('d-none');
                        errorMessage.textContent = data.message;
                    }
                })
                .catch(error => {
                    // Manejo de errores
                    console.error('Error:', error);
                    const errorMessage = document.getElementById('error-message');
                    errorMessage.classList.remove('d-none');
                    errorMessage.textContent = "Ocurrió un error al intentar iniciar sesión.";
                });
            });
        });
    </script>
</body>
</html>
