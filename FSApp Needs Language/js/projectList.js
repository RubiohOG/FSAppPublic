// Referencia al elemento de lista en el DOM
const projectListElement = document.getElementById('project-list');
const createProjectForm = document.getElementById('createProjectForm');

// Función para cargar los proyectos del servidor
async function loadProjects() {
    try {
        // Hacer la solicitud al servidor para obtener los proyectos
        const response = await fetch('index.php?url=project');
        const projectList = await response.json();

        // Limpiar el contenido de la lista antes de agregar nuevos elementos
        projectListElement.innerHTML = '';

        // Verificar si hay proyectos disponibles
        if (projectList.length > 0) {
            projectList.forEach(project => {
                // Crear un elemento de lista para cada proyecto
                const listItem = document.createElement('li');
                listItem.classList.add('list-group-item');

                // Crear un enlace para cada proyecto
                const link = document.createElement('a');
                link.href = `index.php?url=project/${project.project_id}`;
                link.textContent = project.project_name;

                // Añadir el enlace al elemento de lista
                listItem.appendChild(link);

                // Añadir el elemento de lista al contenedor de la lista
                projectListElement.appendChild(listItem);
            });
        } else {
            const emptyMessage = document.createElement('li');
            emptyMessage.classList.add('list-group-item');
            emptyMessage.textContent = 'No hay proyectos disponibles.';
            projectListElement.appendChild(emptyMessage);
        }
    } catch (error) {
        console.error("Error al obtener los proyectos:", error);
        const errorMessage = document.createElement('li');
        errorMessage.classList.add('list-group-item', 'text-danger');
        errorMessage.textContent = 'Error al cargar los proyectos.';
        projectListElement.appendChild(errorMessage);
    }
}

// Función para enviar un nuevo proyecto al servidor
createProjectForm.addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevenir la recarga de la página
    const projectName = document.getElementById('projectName').value;

    try {
        // Hacer la solicitud al servidor para crear un nuevo proyecto
        const response = await fetch('index.php?url=create_project', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ project_name: projectName }),
        });

        const result = await response.json();

        if (result.success) {
            // Recargar los proyectos una vez que se ha creado uno nuevo
            loadProjects();
        } else {
            alert("Error al crear el proyecto: " + result.error);
        }
    } catch (error) {
        console.error("Error al crear el proyecto:", error);
        alert("Error al crear el proyecto.");
    }
});

// Cargar los proyectos cuando la página se cargue
document.addEventListener("DOMContentLoaded", function() {
    loadProjects();
});
