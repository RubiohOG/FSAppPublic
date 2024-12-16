class ProjectService {
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
  }
  