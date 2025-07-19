<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Hospital Regional de Sampaka</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f2f1, #ffffff);
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .login-image {
            flex: 1;
            background: url('img/logo.jpeg') center center no-repeat;
            background-size: cover;
        }

        .login-form {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
        }

        .card {
            width: 100%;
            max-width: 400px;
            border-radius: 1rem;
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.75);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
        }

        .card-title {
            color: #00796b;
            font-weight: bold;
        }

        .btn-login {
            background-color: #00796b;
            color: white;
            font-weight: 500;
        }

        .btn-login:hover {
            background-color: #004d40;
        }

        .hospital-logo {
            width: 60px;
            margin-bottom: 1rem;
        }

        .form-control:focus {
            border-color: #009688;
            box-shadow: 0 0 0 0.2rem rgba(0, 150, 136, 0.25);
        }

        .toggle-password {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .login-image {
                height: 200px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="login-image"></div>
        <div class="login-form">
            <div class="card p-4">
                <div class="text-center">
                    <img src="img/logo.jpeg" alt="Logo Hospital" class="hospital-logo">
                    <h4 class="card-title">Hospital Regional de Sampaka</h4>
                    <p class="text-muted mb-4">Acceso al sistema</p>
                </div>
                <form id="loginForm" method="POST" action="api/login.php" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="username" name="usuario"
                                placeholder="Ingrese su usuario" required>
                        </div>
                        <div id="userError" class="form-text text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="contrasena"
                                placeholder="Ingrese su contraseña" required>
                            <span class="input-group-text toggle-password" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                        <div id="passError" class="form-text text-danger"></div>
                    </div>
                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-login"><i class="bi bi-box-arrow-in-right"></i> Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        }

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const userError = document.getElementById('userError');
            const passError = document.getElementById('passError');
            let valid = true;

            userError.textContent = '';
            passError.textContent = '';

            if (username.value.trim() === '') {
                userError.textContent = 'Por favor, ingresa tu nombre de usuario.';
                valid = false;
            }

            if (password.value.trim() === '') {
                passError.textContent = 'Por favor, ingresa tu contraseña.';
                valid = false;
            }

            if (!valid) e.preventDefault();
        });
    </script>
</body>

</html>
