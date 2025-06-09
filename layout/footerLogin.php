<!-- Bootstrap JS (opcional para interactividad) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    toggleIcon.classList.toggle('bi-eye');
    toggleIcon.classList.toggle('bi-eye-slash');
  }
  // Validación simple del formulario
  document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();
    let valid = true;

    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const userError = document.getElementById('userError');
    const passError = document.getElementById('passError');

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

    if (valid) {
      // Aquí podrías enviar los datos al servidor
      alert('Formulario enviado correctamente');
      // this.submit(); // Descomentar si quieres enviar realmente
    }
  });
</script>
</body>

</html>