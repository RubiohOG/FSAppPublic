<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FairShare</title>
		<link rel="icon" type="image/svg+xml" href="logo.svg">
		<meta name="description" content="Fair Share es una aplicación de gestión de pagos en grupo que facilita la organización y el cálculo de deudas entre amigos, familiares o compañeros de trabajo.">
		<meta name="keywords" content="gestión de pagos, compartir gastos, deudas en grupo, administración financiera, Fair Share">
		<meta name="author" content="Fair Share Team">
		<meta name="robots" content="index, follow">
			
		<!-- Estilos -->
		<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">-->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
		<!--<link href="css/styleFairShareAppProject.css" rel="stylesheet">-->
		<link href="css/styleLogin.css" rel="stylesheet">
		<!--<link href="css/styleLoggedIn.css" rel="stylesheet">-->
		<!--<link href="css/styleRegister.css" rel="stylesheet">-->

		<!-- Bibliotecas -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
		<script src="js/lib/fronty.js"></script>

		<!-- Scripts de tu proyecto -->
		<script src="templates/helpers.js"></script>
		<!--<script src="js/deletePayment.js"></script>-->
		<!--<script src="js/editPayment.js"></script>-->
		<script src="js/modalAddUser.js"></script>
		<!--<script src="js/projectList.js"></script>-->
		<script src="js/toasts.js"></script>

		<!-- Modelos -->
		<script src="js/model/user.js"></script>
		<script src="js/model/project.js"></script>
		<script src="js/model/payment.js"></script>
		<script src="js/model/debt.js"></script>

		<!-- Servicios -->
		<script src="js/service/userservice.js"></script>
		<script src="js/service/projectservice.js"></script>
		<script src="js/service/paymentservice.js"></script>

		<!-- Componentes -->
		<script src="js/component/maincomponent.js"></script>
		<script src="js/component/logincomponent.js"></script>
		<script src="js/component/registercomponent.js"></script>
		<script src="js/component/navbarcomponent.js"></script>
		<script src="js/component/projectlistcomponent.js"></script>
		<script src="js/component/projectdetailcomponent.js"></script>
		<script src="js/component/paymentformcomponent.js"></script>
		<script src="js/component/debtlistcomponent.js"></script>

		<!-- Internacionalización -->
		<script src="js/i18n/i18n.js"></script>

		<!-- Inicialización -->
		<script src="js/app.js"></script>
	</head>
	<body>

		<div id="fairshareapp">
			Loading app, please wait...
		</div>

		<!-- Toasts -->
		<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
			<div id="logoutToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="toast-header">
					<b class="me-auto"><?= i18n("Cierre de sesión")?></b>
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
				<div class="toast-body">
					<?= i18n ("¡Has cerrado sesión correctamente!")?>
				</div>
			</div>
		</div>

		<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
			<div id="registerToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="toast-header">
					<b class="me-auto"><?= i18n ("Registro")?></b>
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
				<div class="toast-body">
					<?= i18n ("Te has registrado correctamente! Ahora puedes iniciar sesión.")?>
				</div>
			</div>
		</div>
	</body>
</html>
