class ProjectService {
    constructor(){
        this.userService = new UserService();
        this.apiUrl = '/rest/project-details';
    }

    addUserToProject(projectId, email) {
      console.log("Email del usuario que se añadirá:", email);
      console.log("ID del proyecto en el que se añadirá el usuario:", projectId);
  
      return new Promise((resolve, reject) => {
          $.ajax({
              url: `/rest/project-details/${projectId}/add-user`,
              method: 'POST',
              contentType: 'application/json',
              data: JSON.stringify({
                  project_id: projectId,
                  email: email,
              }),
              success: resolve,
              error: (xhr) => {
                  console.error('Error al añadir el usuario:', xhr.responseText);
                  reject(new Error(xhr.responseText || 'Error desconocido al añadir el usuario'));
              }
          });
      });
  }
  

    getProjects() {
      return $.ajax({
        url: AppConfig.backendServer + "/rest/projects",
        method: "GET",
        beforeSend: (xhr) => {
            const login = window.sessionStorage.getItem("login");
            const pass = window.sessionStorage.getItem("pass");
            if (login && pass) {
            xhr.setRequestHeader(
                "Authorization",
                "Basic " + btoa(login + ":" + pass)
            );
            }
            
        },
      });
    }

    getProjectDetails(projectId) {
      return $.ajax({
          url: `${AppConfig.backendServer}/rest/project-details/${projectId}`,
          method: 'GET',
          dataType: 'json'
      });
  }

    createProject(project) {
      return $.ajax({
          url: AppConfig.backendServer + "/rest/projects",
          method: "POST",
          data: JSON.stringify(project),
          contentType: "application/json",
      });
    }
  
  }
  