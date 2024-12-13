class ProjectModel extends Fronty.Model {
    constructor() {
      super();
      this.projects = []; // Lista de proyectos
      this.selectedProject = null; // Proyecto actualmente seleccionado
    }
  
    setProjects(projects) {
      this.projects = projects;
      this.notifyAll();
    }
  
    getProjects() {
      return this.projects;
    }
  
    selectProject(projectId) {
      this.selectedProject = this.projects.find(project => project.id === projectId);
      this.notifyAll();
    }
  
    getSelectedProject() {
      return this.selectedProject;
    }
  }
  