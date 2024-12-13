class MainComponent extends Fronty.RouterComponent {
    constructor() {
        super('fairshareapp', Handlebars.templates.main, 'maincontent');
        //super('fairshareapp', Handlebars.templates.main);


        this.projectModel = new ProjectModel();
        this.userModel = new UserModel(); 
        this.paymentModel = new PaymentModel();
        this.debtModel = new DebtModel();
        this.userService = new UserService();

        super.setRouterConfig({
            login: {
                component: new LoginComponent(this.userModel, this),
                title: 'Login'
            },/*
            register: {
                component: new RegisterComponent(this.userModel, this),
                title: 'Register'
            },
            dashboard: {
                component: new ProjectListComponent(this.projectModel, this.userModel, this),
                title: 'Dashboard'
            },
            'project-detail': {
                component: new ProjectDetailComponent(
                    this.projectModel,
                    this.paymentModel,
                    this.debtModel,
                    this.userModel,
                    this
                ),
                title: 'Project Detail'
            },*/
            defaultRoute: 'login'
        });
  
        Handlebars.registerHelper('currentPage', () => {
            return super.getCurrentPage();
        });
  
        this.addChildComponent(this._createUserBarComponent());
        this.addChildComponent(this._createLanguageComponent());
    }
  
    start() {
      
        this.userService.loginWithSessionData().then((logged) => {
            if (logged != null) {
                this.userModel.setLoggeduser(logged);
            }
            console.log("Starting MainComponent");
            super.start();
            console.log("MainComponent started");

            //super.start();
        });
    }
  
    _createUserBarComponent() {
        var userbar = new Fronty.ModelComponent(Handlebars.templates.user, this.userModel, 'userbar');
        userbar.addEventListener('click', '#logoutbutton', () => {
            this.userModel.logout();
            this.userService.logout();
        });
        return userbar;
    }
  
    _createLanguageComponent() {
        var languageComponent = new Fronty.ModelComponent(Handlebars.templates.language, this.routerModel, 'languagecontrol');
        languageComponent.addEventListener('click', '#englishlink', () => {
            I18n.changeLanguage('default');
            document.location.reload();
        });
        languageComponent.addEventListener('click', '#spanishlink', () => {
            I18n.changeLanguage('es');
            document.location.reload();
        });
  
        return languageComponent;
    }
  }
  