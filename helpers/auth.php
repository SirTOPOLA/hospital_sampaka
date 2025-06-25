<?php
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


/**
 * Login específico para Internos.
 * Busca en la tabla `usuarios` usando un campo `usuario` y `password_hash`.
 */
function login($pdo, $usuario, $contrasena)
{
    // Consulta con JOIN a personal y verificación del estado
    $stmt = $pdo->prepare("
        SELECT 
            u.id, 
            u.nombre_usuario, 
            u.password, 
            u.rol, 
            u.estado,
            p.nombre AS nombre_personal,
            p.apellidos
        FROM usuarios_hospital u
        LEFT JOIN personal p ON p.id = u.id_personal
        WHERE u.nombre_usuario = ?
    ");

    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      /*   if (!password_verify($contrasena, $user['password'])) {
            $_SESSION['alerta'] = [
                'tipo' => 'danger',
                'mensaje' => 'Usuario o contraseña incorrectos.'
            ];
            return false;
        } */

        if ((int) $user['estado'] !== 1) {
            $_SESSION['alerta'] = [
                'tipo' => 'warning',
                'mensaje' => 'Tu cuenta está inactiva. Contacta al administrador.'
            ];
            return false;
        }

        // Inicio de sesión válido
        $_SESSION['usuario'] = [
            'id' => $user['id'],
            'username' => $user['nombre_usuario'],
            'nombre' => $user['nombre_personal'] . ' ' . $user['apellidos'],
            'rol' => strtolower($user['rol'])
        ];
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensaje' => 'Inicio de sesión exitoso.'
        ];
        return true;
    }

    // Usuario no encontrado
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => 'Usuario o contraseña incorrectos.'
    ];
    return false;
}


/**
 * Destruccion del login.
 *    .
 */
function logout()
{
    session_unset();
    session_destroy();

    header('Location: index.php?vista=login');
    exit;
}


