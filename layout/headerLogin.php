<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Hospital Regional de Sampaka</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #e0f7fa, #ffffff);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .card-title {
      color: #00695c;
    }

    .form-control:focus {
      border-color: #26a69a;
      box-shadow: 0 0 0 0.2rem rgba(38, 166, 154, 0.25);
    }

    .btn-login {
      background-color: #00897b;
      color: white;
    }

    .btn-login:hover {
      background-color: #00695c;
    }

    .toggle-password {
      cursor: pointer;
    }

    .hospital-logo {
      width: 60px;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>