class DashboardComponent extends Fronty.ModelComponent {
    constructor(projectModel, userModel, router) {
        super(Handlebars.templates.projectlist, projectModel);
        this.projectModel = projectModel;
        this.userModel = userModel;
        this.projectService = new ProjectService();
        this.router = router;

        // Observa el cambio en la ruta utilizando this.router
        this.router.getRouterModel().addObserver(() => {
            if (this.router.getRouterModel().currentPage === "dashboard") {
                this.loadProjects();
            }
        });

        // Carga inicial de proyectos
        if (this.userModel.loggeduser) {
            this.loadProjects();
        }
    }

    loadProjects() {
        this.projectService
            .getProjects()
            .then((projects) => {
                console.log("Proyectos cargados:", projects); // Para depurar
                this.projectModel.set((model) => {
                    model.projects = projects;
                });
            })
            .catch((error) => {
                console.error("Error al cargar proyectos:", error);
                this.projectModel.set((model) => {
                    model.projects = [];
                });
            });
    }
    
}
