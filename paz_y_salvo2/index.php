<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="vistas/login/styles.css">
</head>
<body>
    <br> <br> <br> <br> <br> <br>
    <div class="container-fluid bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <h2 class="text-center mb-4">Iniciar sesión</h2>
                    <img src="imagen/beyonder.jpeg" alt="Imagen de fondo" class="img-fluid rounded mb-4 custom-img">

                    <!-- Div para mostrar mensajes de error o éxito -->
                    <div id="error-message" class="alert alert-danger d-none"></div>
                    
                    <form id="login-form" method="post" autocomplete="off">
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <div class="input-group">
                                    <img src="imagen/iconos/userlogin.png" alt="Icono de usuario" width="20">
                                    <span class="input-text">Usuario</span>
                                </div>
                            </label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <div class="input-group">
                                    <img src="imagen/iconos/contraseñaicono.png" alt="Icono de contraseña" width="20">
                                    <span class="input-text">Contraseña</span>
                                </div>
                            </label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                    </form>

                    <div class="d-flex justify-content-between mt-3">
                        <p class="mb-0"><a href="vistas/registro/registro.html" class="text-white">¿No tienes una cuenta? Regístrate aquí</a>.</p>  
                        <p class="mb-0"><a href="vistas/autenticacion/forgot_password.php" class="text-white">Olvidé mi contraseña</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

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
                if (data.success) {
                    if (data.role === 'administrador') {
                        window.location.href = "vistas/admin/admin.php";
                    } else if (data.role === 'empleado') {
                        window.location.href = "vistas/empleado/empleados.php";
                    } else if (data.role === 'recursos_humanos') {
                        window.location.href = "vistas/recursos_humanos/recurso_humano.php";
                    }
                } else {
                    document.getElementById('error-message').classList.remove('d-none');
                    document.getElementById('error-message').textContent = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>