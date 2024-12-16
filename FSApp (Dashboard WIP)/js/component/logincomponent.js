class LoginComponent extends Fronty.ModelComponent {
    constructor(userModel, router) {
        super(Handlebars.templates.login, userModel);
        this.userModel = userModel;
        this.userService = new UserService();
        this.router = router;

        this.addEventListener('click', '#loginbutton', () => {
            const username = document.getElementById('login-username').value;
            const password = document.getElementById('login-password').value;

            const userService = new UserService();
            userService.login(username, password)
                .then(() => {
                    alert('Entrando a ' + username);
                    this.router.goToPage('dashboard');
                })
                .catch((error) => {
                    this.userModel.set((model) => {
                        userModel.loginError = error.message;
                    });
                });
            });

        this.addEventListener('click', '#register', () => {
            this.userModel.set((model) => {
            this.router.goToPage('register');
            });
        });
    }
}
  