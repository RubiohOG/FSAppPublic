<!-- views/loginForm.php -->
<!DOCTYPE html>
<html>
<?php include 'includes/head_login.php'; ?>
<body>
    <!-- Toasts -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="logoutToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <b class="me-auto"><?= i18n("Cierre de sesión")?></b>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?= i18n ("¡Has cerrado sesión correctamente!")?>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="registerToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <b class="me-auto"><?= i18n ("Registro")?></b>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?= i18n ("Te has registrado correctamente! Ahora puedes iniciar sesión.")?>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="logo.svg" alt="Fair Share" width="40" height="40"> FairShare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=register"><?= i18n ("Registrarse")?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?url=login"><?= i18n ("Iniciar Sesión")?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section id="login">
            <h2><?= i18n ("Iniciar Sesión")?></h2>
            <form action="index.php?url=login" method="POST">
                <div class="form-group mb-3">
                    <label for="login-username" class="form-label"><?= i18n ("Usuario")?></label>
                    <input type="text" class="form-control" id="login-username" name="username" required>
                </div>
                <div class="form-group mb-3">
                    <label for="login-password" class="form-label"><?= i18n ("Contraseña")?></label>
                    <input type="password" class="form-control" id="login-password" name="password" required>
                </div>
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary"><?= i18n ("Entrar")?></button>
                </div>
            </form>
        </section>
    </main>

    <?php include 'includes/footer_welcome.php'; ?>
    <!-- Esto debe estar aquí, al final del body -->
    <script src="../js/toasts.js"></script>
</body>
</html>
