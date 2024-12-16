class MainComponent extends Fronty.RouterComponent {
    constructor() {
        super('fairshareapp', Handlebars.templates.main, 'maincontent');
        //super('fairshareapp', Handlebars.templates.main);

        this.projectModel = new ProjectModel();
        this.userModel = new UserModel(); 
        this.paymentModel = new PaymentModel();
        this.debtModel = new DebtModel();
        this.userService = new UserService();

        this.router = this.getRouterModel();

        // Función para cargar CSS dinámico
        const loadDynamicStyle = (page) => {
            const existingStyle = document.getElementById('dynamic-style');
            if (existingStyle) {
                existingStyle.remove();
            }

            let stylePath = '';
            if (page === 'login' || page === 'register') {
                stylePath = 'css/styleLogin.css';
            } else if (page === 'dashboard') {
                stylePath = 'css/styleLoggedIn.css';
            } else if (page === 'project-detail') {
                stylePath = 'css/styleProjectDetail.css';
            }

            if (stylePath) {
                const link = document.createElement('link');
                link.id = 'dynamic-style';
                link.rel = 'stylesheet';
                link.href = stylePath;
                document.head.appendChild(link);
                console.log(`CSS cargado: ${stylePath}`);
            }
        };

        // Observador de cambios en la ruta
        this.getRouterModel().addObserver(() => {
            const currentPage = this.getRouterModel().currentPage;
            console.log("Cambio en la ruta:", currentPage);
            loadDynamicStyle(currentPage);
        });

        // Cargar estilo inicial al iniciar la aplicación
        const initialPage = this.getRouterModel().currentPage || 'login';
        loadDynamicStyle(initialPage);

        super.setRouterConfig({
            login: {
                component: new LoginComponent(this.userModel, this),
                title: 'login'
            },
            register: {
                component: new RegisterComponent(this.userModel, this),
                title: 'register'
            },
            dashboard: {
                component: new DashboardComponent(this.projectModel, this.userModel, this),
                title: "dashboard"
            },
            /*
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

        });
    }

/*
    start() {
        console.log(this.getRouterModel());

        this.userService.loginWithSessionData()
        .then((loggedUser) => {
            if (loggedUser) {
            this.userModel.setLoggeduser(loggedUser);
    
            const currentPage = this.getRouterModel().currentPage;
    
            if (currentPage === "dashboard") {
                this.getRouterModel().goToPage('dashboard');
            } else {
                this.getRouterModel().goToPage('dashboard');
            }
            } else {
            this.getRouterModel().goToPage('login');
            }
    
            console.log("MainComponent started");
            super.start();
        })
        .catch(() => {
            console.log("Error verificando sesión");
            this.getRouterModel().goToPage('login');
            super.start();
        });
    }
  */
      
  
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
  