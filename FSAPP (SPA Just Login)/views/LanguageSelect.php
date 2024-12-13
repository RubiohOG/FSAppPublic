<?php
// file: view/layouts/language_select_element.php
?>
<div class="dropdown">
    <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <?= i18n("Seleccionar Idioma") ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        <li><a href="index.php?url=set_language&lang=es">
                <?= i18n("Español") ?>
            </a></li>
        <li><a href="index.php?url=set_language&lang=en">
                <?= i18n("Inglés") ?>
            </a></li>
    </ul>
</div>