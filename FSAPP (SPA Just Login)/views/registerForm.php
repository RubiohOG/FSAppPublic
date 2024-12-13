<!-- views/registerForm.php -->
<!DOCTYPE html>
<html>
<?php include 'includes/head_register.php'; ?>
<body>
    <!-- Toasts -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="registerToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Registro</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?= i18n ("¡Te has registrado correctamente! Ahora puedes iniciar sesión.")?>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="logo.svg" alt="Fair Share" width="40" height="40"> Fair Share
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=login"><?= i18n ("Iniciar Sesión")?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?url=register"><?= i18n ("Iniciar Sesión")?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section id="registro">
            
            <h2><?= i18n ("Registro de Usuario")?></h2>

            <?php if (isset($error)): ?>
                <p class="text-danger"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form action="index.php?url=register" method="POST">
                <div class="form-group mb-3">
                    <label for="register-username" class="form-label"><?= i18n ("Nombre de usuario")?></label>
                    <input type="text" class="form-control" id="register-username" name="username" required>
                </div>
                <div class="form-group mb-3">
                    <label for="register-email" class="form-label"><?= i18n ("Correo Electrónico")?></label>
                    <input type="email" class="form-control" id="register-email" name="email" required>
                </div>
                <div class="form-group mb-3">
                    <label for="register-password" class="form-label"><?= i18n ("Contraseña")?></label>
                    <input type="password" class="form-control" id="register-password" name="password" required>
                </div>
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary"><?= i18n ("Registrarse")?></button>
                </div>
            </form>
        </section>
    </main>

    <?php include 'includes/footer_welcome.php'; ?>
    <!-- Esto debe estar aquí, al final del body -->
    <script src="js/toasts.js"></script>
</body>
</html>
