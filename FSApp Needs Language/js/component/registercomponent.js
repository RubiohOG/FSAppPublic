class RegisterComponent extends Fronty.ModelComponent {
    constructor(userModel, router) {
        super(Handlebars.templates.register, userModel);
        this.userService = new UserService();
        this.router = router;

        this.addEventListener('click', '#registerbutton', () => {
            const username = document.getElementById('register-username').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;

            this.userService
                .register({ username, email, password })
                .then(() => {
                    alert('Usuario registrado con éxito. Ahora puedes iniciar sesión.');
                    this.router.goToPage('login');
                })
                .catch((error) => {
                    alert('Error al registrar el usuario: ' + error.message);
                });
        });

        this.addEventListener('click', '#login-button', () => {
            this.router.goToPage('login');
        });
    }
}
