// Load external resources
function loadTextFile(url) {
    return new Promise((resolve, reject) => {
      $.get({
        url: url,
        cache: true,
        beforeSend: function (xhr) {
          xhr.overrideMimeType("text/plain");
        }
      }).then((source) => {
        resolve(source);
      }).fail(() => reject());
    });
  }
  
  // Configuration
  var AppConfig = {
    backendServer: 'http://localhost'
  };
  
  Handlebars.templates = {};
  Promise.all([
    
      I18n.initializeCurrentLanguage('js/i18n'),
      loadTextFile('templates/components/main.hbs').then((source) =>
        Handlebars.templates.main = Handlebars.compile(source)),
      loadTextFile('templates/components/language.hbs').then((source) =>
        Handlebars.templates.language = Handlebars.compile(source)),
      loadTextFile('templates/components/login.hbs').then((source) =>
        Handlebars.templates.login = Handlebars.compile(source)),
      loadTextFile('templates/components/register.hbs').then((source) =>
        Handlebars.templates.register = Handlebars.compile(source)),
      loadTextFile('templates/components/navbar.hbs').then((source) =>
        Handlebars.templates.navbar = Handlebars.compile(source)),
      loadTextFile('templates/components/dashboard.hbs').then((source) =>
        Handlebars.templates.projectlist = Handlebars.compile(source)),
      loadTextFile('templates/components/project-detail.hbs').then((source) =>
        Handlebars.templates.projectdetail = Handlebars.compile(source)),
      loadTextFile('templates/components/payment.hbs').then((source) =>
        Handlebars.templates.paymentform = Handlebars.compile(source)),
      loadTextFile('templates/components/debts.hbs').then((source) =>
        Handlebars.templates.debtlist = Handlebars.compile(source)),
      loadTextFile('templates/components/otherProjects.hbs').then((source) =>
        Handlebars.templates.debtlist = Handlebars.compile(source)),
      loadTextFile('templates/components/asociatedUsers.hbs').then((source) =>
        Handlebars.templates.debtlist = Handlebars.compile(source)),
      console.log(Handlebars.templates)
      ])
    .then(() => {
      $(() => {
        // new MainComponent().start();
        window.fairshareapp = new MainComponent();
        window.fairshareapp.start(); // Iniciar la aplicación
        //console.log(document.getElementById('fairshareapp'));

      });
    }).catch((err) => {
      console.error('FATAL: could not start app', err);
      alert('Error al iniciar la aplicación.');
    });
  