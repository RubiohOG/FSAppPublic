class UserService {
  constructor() {

  }

  loginWithSessionData() {
    var self = this;
    return new Promise((resolve, reject) => {
      if (window.sessionStorage.getItem('login') &&
        window.sessionStorage.getItem('pass')) {
        self.login(window.sessionStorage.getItem('login'), window.sessionStorage.getItem('pass'))
          .then(() => {
            resolve(window.sessionStorage.getItem('login'));
          })
          .catch(() => {
            reject();
          });
      } else {
        resolve(null);
      }
    });
  }

  login(login, pass) {
    return new Promise((resolve, reject) => {
      $.get({
          url: AppConfig.backendServer+'/rest/login',
          beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
          }
        })
        .then(() => {
          //keep this authentication forever
          window.sessionStorage.setItem('login', login);
          window.sessionStorage.setItem('pass', pass);
          $.ajaxSetup({
            beforeSend: (xhr) => {
              xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
            }
          });
          resolve();
        })
        .fail((error) => {
          window.sessionStorage.removeItem('login');
          window.sessionStorage.removeItem('pass');
          $.ajaxSetup({
            beforeSend: (xhr) => {}
          });
          reject(error);
        });
    });
  }
/*
  login(login, pass) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: AppConfig.backendServer + '/rest/login',
        method: 'GET',
        beforeSend: (xhr) => {
          xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
        }
      })
        .then((response) => {
          window.sessionStorage.setItem('login', login);
          window.sessionStorage.setItem('pass', pass);

          $.ajaxSetup({
            beforeSend: (xhr) => {
              xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
            }
          });
          resolve(response);
        })
        .fail((xhr) => {
          window.sessionStorage.removeItem('login');
          window.sessionStorage.removeItem('pass');
          reject(new Error(xhr.responseJSON?.error || "Error de autenticación"));
        });
    });
  }    
*/
  logout() {
    window.sessionStorage.removeItem('login');
    window.sessionStorage.removeItem('pass');
    $.ajaxSetup({
      beforeSend: (xhr) => {}
    });
  }

  register(user) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/register',
      method: 'POST',
      data: JSON.stringify(user),
      contentType: 'application/json'
    });
  }
}