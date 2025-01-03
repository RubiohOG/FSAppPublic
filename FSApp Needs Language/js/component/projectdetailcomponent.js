class ProjectDetailComponent extends Fronty.ModelComponent {
    constructor(projectModel, userModel, paymentModel,  debtModel, router) {
        console.log("ProjectDetailComponent");
        super(Handlebars.templates.projectdetail, projectModel);
        this.projectModel = projectModel;
        this.paymentModel = paymentModel;
        this.debtModel = debtModel;
        this.userModel = userModel;
        this.router = router;
        this.projectService = new ProjectService();
        this.paymentService = new PaymentService();
        this.debtService = new DebtService();

        this.addEventListener('click', '#logoutbutton', () => {
            const userService = new UserService();
            userService.logout();
            localStorage.removeItem('currentProjectId');
            this.router.goToPage('login');
        });
    
        this.projectModel.addObserver(() => {
            if (typeof this.updateView === 'function') {
                this.updateView();
            } else {
                console.error('updateView no está definido en el componente');
            }
        });

        // Evento para cargar otro proyecto
        this.addEventListener('click', '.project-link', (event) => {
            console.log("Click en un enlace de proyecto");
            event.preventDefault();
            const projectLink = event.target.closest('.project-link');
            if (projectLink) {
                const projectId = projectLink.dataset.id;
                console.log("ID del proyecto:", projectId);
                if (projectId) {
                    this.projectModel.set((model) => {
                        model.currentProjectId = projectId;
                        this.cargarProyecto();
                    });
                    this.router.goToPage(`project-details`);
                }
            } else {
                console.error("No se encontró un enlace válido.");
            }
        });
        
        // Evento para añadir un pago
        this.addEventListener('submit', '#createPaymentForm', (event) => {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(event.target);
            const paymentData = {
                paymentName: formData.get('payment_name'),
                amount: parseFloat(formData.get('amount')),
                payer: formData.get('payer_username'),
                beneficiaries: formData.getAll('beneficiaries[]')
            };
        
            this.paymentService.createPayment(localStorage.getItem('currentProjectIdStorage'), paymentData)
                .then(() => {
                    //alert('Pago creado con éxito');
                    this.cargarProyecto();
                    form.reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createPaymentModal'));
                    if (modal) {
                        modal.hide();
                    }

                    const message = document.getElementById("userMessage");
                    if (message) {
                        setTimeout(() => {
                            message.style.transition = "opacity 1s";
                            message.style.opacity = 0;
                            setTimeout(() => message.remove(), 1000);
                        }, 3000);
                    }
                })
                .catch((err) => alert('Error al crear el pago: ' + err.message));
        });
        
        
        // Evento para añadir un usuario al proyecto
        this.addEventListener('submit', '#addUserForm', (event) => {
            event.preventDefault();
        
            const form = event.target;
            const email = form.querySelector('#email').value;
            const projectId = localStorage.getItem('currentProjectIdStorage');
        
            if (!email) {
                alert('Por favor, introduce un email válido.');
                return;
            }
        
            this.projectService
                .addUserToProject(projectId, email)
                .then(() => {
                    alert('Usuario añadido con éxito.');        
                    form.reset();
                    const modalElement = document.getElementById('addUserModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                    this.cargarProyecto();
                })
                .catch((err) => {
                    console.error('Error al añadir el usuario:', err.message);
                    alert('Error al añadir el usuario: ' + err.message);
                });
        });

        // Evento para cargar los datos para eliminar un usuario del proyecto
        this.addEventListener('click', '.delete-payment-btn', (event) => {
            const paymentId = event.target.dataset.paymentId;
            if (paymentId) {
                document.getElementById('paymentIdToDelete').value = paymentId;
            }
        });

        // Evento para eliminar un usuario del proyecto
        this.addEventListener('submit', '#deletePaymentForm', (event) => {
            event.preventDefault();
        
            const paymentId = document.getElementById('paymentIdToDelete').value;
            const projectId = localStorage.getItem('currentProjectIdStorage');

            console.log('ID del pago a eliminar:', paymentId, ' en el proyecto:', projectId);
        
            if (paymentId && projectId) {
                this.paymentService
                    .deletePayment(projectId, paymentId)
                    .then(() => {
                        alert('Pago eliminado con éxito.');
                        const modalElement = document.getElementById('confirmDeleteModal');
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                        this.cargarProyecto();
                    })
                    .catch((err) => {
                        console.error('Error al eliminar el pago:', err.message);
                        alert('Error al eliminar el pago: ' + err.message);
                    });
            } else {
                alert('No se pudo determinar el ID del proyecto o el pago.');
            }
        });

        // Evento para cargar los datos para editar un pago
        this.addEventListener('click', '.edit-payment-btn', (event) => {
            const button = event.target;

            const paymentId = button.dataset.paymentId;
            const paymentName = button.dataset.paymentName;
            const amount = button.dataset.amount;
            const beneficiaries = JSON.parse(button.dataset.beneficiaries || '[]');

            document.getElementById('editPaymentId').value = paymentId;
            document.getElementById('editPaymentName').value = paymentName;
            document.getElementById('editAmount').value = amount;

            const checkboxes = document.querySelectorAll('#editPaymentForm .form-check-input');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = beneficiaries.includes(checkbox.value);
            });
        });

        // Evento para editar un pago
        this.addEventListener('submit', '#editPaymentForm', (event) => {
            event.preventDefault();
        
            const paymentId = document.getElementById('editPaymentId').value;
            const projectId = localStorage.getItem('currentProjectIdStorage');
            const paymentName = document.getElementById('editPaymentName').value;
            const amount = document.getElementById('editAmount').value;
            const payer = document.getElementById('payer_username').value;
        
            const beneficiaries = Array.from(document.querySelectorAll('#editPaymentForm .form-check-input:checked'))
                .map((checkbox) => checkbox.value);
        
            if (paymentId && paymentName && amount && beneficiaries.length > 0) {
                this.paymentService
                    .editPayment(projectId, paymentId, { paymentName, amount, beneficiaries, payer })
                    .then(() => {
                        alert('Pago editado con éxito.');
                        const modalElement = document.getElementById('editPaymentModal');
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                        this.cargarProyecto();
                    })
                    .catch((err) => {
                        console.error('Error al editar el pago:', err.message);
                        alert('Error al editar el pago: ' + err.message);
                    });
            } else {
                alert('Por favor, completa todos los campos del formulario.');
            }
        });
        
        
        

    }

    onStart() {
        this.cargarProyecto();
    }

    cargarProyecto() {
        const projectId = this.projectModel.currentProjectId || localStorage.getItem('currentProjectIdStorage');
        //const projectId = this.projectModel.currentProjectId;
        //const currentProjectIdStorage = localStorage.getItem('currentProjectIdStorage');
        //console.log('ID del proyecto en localStorage:', currentProjectIdStorage);
        if (projectId || currentProjectIdStorage) {
            this.projectService
                .getProjectDetails(projectId)
                .then((project) => {
                    this.projectModel.set((model) => {
                        model.currentProject = project;
                        model.payments = project.payments;
                        model.debts = project.debts;
                        model.users = project.users || [];
                    });
                    return this.projectService.getProjects();
                })
                .then((otherProjects) => {
                    this.projectModel.set((model) => {
                        model.otherProjects = otherProjects;
                    });
                    this.projectModel.notifyObservers();
                })
                .catch((err) => console.error('Error al cargar los datos del proyecto:', err));
        } else {
            console.error('No se encontró un ID de proyecto en el modelo.');
        }
    }

    updateView() {
        this.render();
    }
    
      
  }
  