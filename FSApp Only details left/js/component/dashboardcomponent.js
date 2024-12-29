class DashboardComponent extends Fronty.ModelComponent {
    constructor(projectModel, userModel, router) {
        super(Handlebars.templates.projectlist, projectModel);
        this.projectModel = projectModel;
        this.userModel = userModel;
        this.projectService = new ProjectService();
        this.router = router;

        this.addEventListener('click', '#logoutbutton', () => {
            const userService = new UserService();
            userService.logout();
            this.router.goToPage('login');
        });

        this.addEventListener("submit", "#createProjectForm", (event) => {
            event.preventDefault();
            const projectName = document.getElementById("projectName").value;
            this.projectService
                .createProject({ project_name: projectName })
                .then(() => {
                    this.loadProjects();
                    $("#createProjectModal").modal("hide");
                    document.getElementById("createProjectForm").reset();
                })
                .catch((error) => {
                    console.error("Error al crear el proyecto:", error);
                });
        });

    }
    onStart() {
        
        if (this.userModel.currentUser) {
            this.loadProjects();
        }
    }
    loadProjects() {
        
        this.projectService
            .getProjects()
            .then((projects) => {
                console.log("Proyectos cargados:", projects);
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
