<!-- views/projectList.php -->

<!DOCTYPE html>
<html>
<?php include 'includes/head_projectList.php'; ?>
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
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mt-4"><?= i18n ("Proyectos Abiertos")?></h2>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#createProjectModal"><?= i18n ("Crear Proyecto")?></button>
        </div>

        <ul id="project-list" class="list-group mt-3">
            <!-- Aquí se cargará la lista de proyectos dinámicamente -->
            <?php if (!empty($userProjects)): ?>
                <?php foreach ($userProjects as $project): ?>
                    <li class="list-group-item">
                        <a href="index.php?url=project_details&project_id=<?= htmlspecialchars($project['project_id']) ?>">
                            <?= htmlspecialchars($project['project_name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item"><?= i18n ("No hay proyectos disponibles.")?></li>
            <?php endif; ?>
        </ul>
    </main>

    <!-- Modal para crear un nuevo proyecto -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProjectModalLabel"><?= i18n ("Crear Nuevo Proyecto")?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createProjectForm" action="index.php?url=create_project" method="POST">
                        <div class="mb-3">
                            <label for="projectName" class="form-label"><?= i18n ("Nombre del Proyecto")?></label>
                            <input type="text" class="form-control" id="projectName" name="project_name" required>
                        </div>
                        <div class="col text-center">
                            <button type="submit" class="btn btn-success"><?= i18n ("Crear Proyecto")?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer_projectList.php'; ?>
</body>
</html>
