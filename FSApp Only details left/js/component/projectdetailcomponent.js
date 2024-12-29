class ProjectDetailComponent extends Fronty.ModelComponent {
    constructor(projectModel, userModel, router) {
        console.log("ProjectDetailComponent");
      super(Handlebars.templates['project-detail'], projectModel);
      this.projectModel = projectModel;
      this.userModel = userModel;
      this.router = router;
      this.projectService = new ProjectService();
      this.paymentService = new PaymentService();
      this.debtService = new DebtService();
  
      // Observa cambios en el modelo del proyecto
      this.projectModel.addObserver(() => {
        this.updateView();
      });
  
      // Maneja eventos para añadir un pago
      this.addEventListener('click', '#addPaymentBtn', () => {
        const paymentName = prompt('Nombre del pago:');
        const amount = prompt('Cantidad:');
        const payer = this.userModel.getLoggedUser();
  
        if (paymentName && amount && payer) {
          this.paymentService
            .createPayment({
              projectId: this.projectModel.currentProject.id,
              paymentName,
              amount,
              payer,
            })
            .then(() => {
              alert('Pago creado con éxito');
              this.loadProjectData();
            })
            .catch((err) => alert('Error al crear el pago: ' + err.message));
        }
      });
  
      // Maneja eventos para añadir un usuario
      this.addEventListener('click', '#addUserBtn', () => {
        const email = prompt('Email del usuario:');
  
        if (email) {
          this.projectService
            .addUserToProject(this.projectModel.currentProject.id, email)
            .then(() => {
              alert('Usuario añadido con éxito');
              this.loadProjectData();
            })
            .catch((err) => alert('Error al añadir el usuario: ' + err.message));
        }
      });
    }
  
    loadProjectData() {
        if (!this.projectModel.currentProject || !this.projectModel.currentProject.id) {
          console.error("No hay un proyecto seleccionado.");
          return;
        }
        const projectId = this.projectModel.currentProject.id;
        this.projectService
            .getProjectDetails(projectId)
            .then((project) => {
            this.projectModel.set((model) => {
              model.currentProject = project;
              model.payments = project.payments;
              model.debts = project.debts;
            });
        })
        .catch((err) => console.error("Error al cargar los datos del proyecto:", err));
    }
      

    updateView() {
        this.render();
    }
    
  
    onStart() {
        const projectId = this.router.getRouteParams()?.id;
        if (!projectId) {
          console.error("No se proporcionó un ID de proyecto.");
          return;
        }
        this.projectService
          .getProjectDetails(projectId)
          .then((project) => {
            this.projectModel.set((model) => {
              model.currentProject = project;
              model.payments = project.payments;
              model.debts = project.debts;
            });
          })
          .catch((err) => console.error("Error al cargar los datos del proyecto:", err));
      }
      
  }
  