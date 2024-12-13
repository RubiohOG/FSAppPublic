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
            this.router.goToPage('dashboard');
          })
          .catch((error) => {
            this.model.set((model) => {
              model.loginError = error.message;
            });
          });
      });   

      this.addEventListener('click', '#registerlink', () => {
        this.userModel.set((model) => {
          model.registerMode = true;
        });
      });
  
      this.addEventListener('click', '#registerbutton', () => {
        const username = $('#registerusername').val();
        const password = $('#registerpassword').val();
  
        this.userService
          .register({ username, password })
          .then(() => {
            alert(I18n.translate('User registered! Please login'));
            this.userModel.set((model) => {
              model.registerErrors = {};
              model.registerMode = false;
            });
          })
          .catch((xhr) => {
            if (xhr.status === 400) {
              this.userModel.set((model) => {
                model.registerErrors = xhr.responseJSON;
              });
            } else {
              alert(
                'An error has occurred during request: ' +
                  xhr.statusText +
                  '.' +
                  xhr.responseText
              );
            }
          });
      });
    }
  }
  