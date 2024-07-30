<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="vistas/login/styles.css">
</head>
<body>
<br> <br> <br> <br> <br> <br>
    <div class="container-fluid bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <h2 class="text-center mb-4">Iniciar sesión</h2>
                    <!-- Imagen -->
                    <img src="imagen/beyonder.jpeg" alt="Imagen de fondo" class="img-fluid rounded mb-4 custom-img">
                    <?php
                    // Muestra un mensaje de error si existe
                    if (isset($error)) {
                        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                    }
                    ?>
                    <form action="vistas/login/login.php" method="post" autocomplete="off">
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

    <!-- Bootstrap JS y jQuery (necesario para Bootstrap) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>
