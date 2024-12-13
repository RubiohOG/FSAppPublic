<!DOCTYPE html>
<html>
<?php include 'includes/head_projectDetails.php'; ?>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="logo.svg" alt="Fair Share" width="40" height="40">                
                FairShare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=logout"><?= i18n ("Cerrar Sesión")?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=project"><?= i18n ("Proyectos")?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">

        <!-- Modal para Añadir Usuario -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel"><?= i18n ("Añadir Usuario al Proyecto")?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="index.php?url=add_user_to_project" method="POST">
                            <input type="hidden" name="projectId" value="<?= htmlspecialchars($projectId) ?>">
                            <div class="mb-3">
                                <label for="email" class="form-label"><?= i18n ("Email del nuevo usuario:")?></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary"><?= i18n ("Añadir Usuario")?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear un nuevo pago -->
        <div class="modal fade" id="createPaymentModal" tabindex="-1" aria-labelledby="createPaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createPaymentModalLabel"><?= i18n ("Crear Nuevo Pago")?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createPaymentForm" action="index.php?url=create_payment" method="POST">
                            <!-- Campo oculto para project_id -->
                            <input type="hidden" name="project_id" value="<?= htmlspecialchars($projectId) ?>">

                            <div class="mb-3">
                                <label for="payer_username" class="form-label"><?= i18n ("Usuario que paga:")?></label>
                                <select class="form-select" id="payer_username" name="payer_username" required>
                                    <?php if (empty($users)): ?>
                                        <option disabled><?= i18n ("No hay usuarios disponibles")?></option>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?= htmlspecialchars($user['user_name']) ?>"><?= htmlspecialchars($user['user_name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="payment_name" class="form-label"><?= i18n ("Nombre del pago:")?></label>
                                <input type="text" class="form-control" id="payment_name" name="payment_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label"><?= i18n ("Cantidad:")?>:</label>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><?= i18n ("Beneficiarios:")?></label>
                                <?php if (empty($users)): ?>
                                    <p><?= i18n ("No hay usuarios disponibles para seleccionar como beneficiarios.")?></p>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="beneficiaries[]" value="<?= htmlspecialchars($user['user_name']) ?>" id="beneficiary_<?= htmlspecialchars($user['user_name']) ?>">
                                            <label class="form-check-label" for="beneficiary_<?= htmlspecialchars($user['user_name']) ?>"><?= htmlspecialchars($user['user_name']) ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary"><?= i18n ("Crear Pago")?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal de Confirmación para Eliminar Pago -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel"><?= i18n ("Confirmar Eliminación")?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?= i18n ("¿Estás seguro de que deseas eliminar este pago? Esta acción no se puede deshacer.")?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= i18n ("Cancelar")?></button>
                        <form id="deletePaymentForm" action="index.php?url=delete_payment" method="POST">
                            <input type="hidden" name="payment_id" id="paymentIdToDelete">
                            <input type="hidden" name="project_id" value="<?= htmlspecialchars($projectId) ?>">
                            <button type="submit" class="btn btn-danger"><?= i18n ("Eliminar")?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para editar un pago -->
        <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPaymentModalLabel"><?= i18n ("Editar Pago")?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editPaymentForm" action="index.php?url=edit_payment" method="POST">
                            <input type="hidden" name="payment_id" id="editPaymentId">
                            <input type="hidden" name="project_id" value="<?= htmlspecialchars($projectId) ?>">

                            <div class="mb-3">
                                <label for="editPaymentName" class="form-label"><?= i18n ("Nombre del pago:")?></label>
                                <input type="text" class="form-control" id="editPaymentName" name="payment_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="editAmount" class="form-label"><?= i18n ("Cantidad:")?></label>
                                <input type="number" class="form-control" id="editAmount" name="amount" step="0.01" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><?= i18n ("Beneficiarios:")?></label>
                                <?php foreach ($users as $user): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="beneficiaries[]" 
                                            value="<?= htmlspecialchars($user['user_name']) ?>" 
                                            id="editBeneficiary_<?= htmlspecialchars($user['user_name']) ?>">
                                        <label class="form-check-label" for="editBeneficiary_<?= htmlspecialchars($user['user_name']) ?>">
                                            <?= htmlspecialchars($user['user_name']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary"><?= i18n ("Guardar Cambios")?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>





        <div class="row" style="min-height: 77.5vh">
            <!-- Sidebar izquierdo -->
            <aside class="col-xl-3 col-lg-3 col-md-12 sidebar order-3">
                <h3><?= i18n ("Otros Proyectos")?></h3>
                <ul class="list-group">
                <?php if (!empty($userProjects)) : ?>
                    <?php foreach ($userProjects as $project): ?>
                        <li class="list-group-item">
                            <a href="index.php?url=project_details&project_id=<?= htmlspecialchars($project['project_id']) ?>">
                                <?= htmlspecialchars($project['project_name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p><?= i18n ("No hay otros proyectos asociados.")?></p>
                <?php endif; ?>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <main class="col-xl-6 col-lg-6 col-md-12 main-content flex-grow-1 d-flex flex-column order-2">
                <section id="proyecto-detalle">
                    <h2><?= i18n ("Detalle del Proyecto:")?> <?= htmlspecialchars($projectDetails['project_name']) ?></h2>

                    <!-- Mostrar mensajes -->
                    <?php if (isset($message)): ?>
                        <div class="alert alert-info">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Pagos Realizados -->
                    <div class="d-flex justify-content-between align-items-center">
                        <h3><?= i18n ("Pagos Realizados")?></h3>
                        <button type="button" class="align-self-end mb-2 btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#createPaymentModal">
                            <i class="fas fa-plus"></i> <b>+</b>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?= i18n ("Usuario que Paga")?></th>
                                    <th><?= i18n ("Nombre del Pago")?></th>
                                    <th><?= i18n ("Cantidad")?></th>
                                    <th><?= i18n ("Acciones")?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($payments) && !empty($payments)): ?>
                                    <?php foreach ($payments as $payment): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($payment['payer_username']); ?></td>
                                            <td><?php echo htmlspecialchars($payment['payment_name']); ?></td>
                                            <td>$<?php echo htmlspecialchars($payment['amount']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPaymentModal" 
                                                        data-payment-id="<?= htmlspecialchars($payment['payment_id']) ?>" 
                                                        data-payment-name="<?= htmlspecialchars($payment['payment_name']) ?>" 
                                                        data-amount="<?= htmlspecialchars($payment['amount']) ?>" 
                                                        data-beneficiaries="<?= htmlspecialchars(json_encode($paymentDetails['beneficiaries'] ?? [])) ?>">
                                                    <?= i18n ("Editar")?>
                                                </button>

                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" 
                                                    data-payment-id="<?= isset($payment['payment_id']) ? htmlspecialchars($payment['payment_id']) : '' ?>">
                                                    <?= i18n ("Eliminar")?>
                                                </button>
                                                <!--<?= var_dump($payment); ?>-->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4"><?= i18n ("No se han registrado pagos aún.")?></td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="text-center my-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-user-plus"></i> <?= i18n ("Añadir Usuario")?>
                        </button>
                        <?php if (isset($userMessage)) : ?>
                            <div id="userMessage" class="mt-3 alert <?= $userMessage['type'] ?>">
                                <?= htmlspecialchars($userMessage['message']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
            </main>
            
            <!-- Sidebar Izquierda -->
            <aside class="col-xl-3 col-lg-3 col-md-12 sidebar order-1">
            <h3><?= i18n ("Deudas Pendientes")?></h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?= i18n ("Deudor")?></th>
                        <th><?= i18n ("Acreedor")?></th>
                        <th><?= i18n ("Cantidad")?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($debts)): ?>
                        <?php foreach ($debts as $debt): ?>
                            <tr>
                                <td><?= htmlspecialchars($debt['debtor_username']) ?></td>
                                <td><?= htmlspecialchars($debt['payer_username']) ?></td>
                                <td><?= htmlspecialchars($debt['amount']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                            <p><?= i18n ("No hay deudas registradas.")?></p>
                    <?php endif; ?>
                </tbody>
            </table>

            </aside>
        </div>
    </div>
    <?php include 'includes/footer_projectDetails.php'; ?>

</body>
</html>