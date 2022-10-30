    <!-- Vista de Crear Proyecto en DashBoard -->
    <!-- header -->
    <?php include_once __DIR__. '/header-dashboard.php';?>
    <div class = "container-sm">
        <?php include_once __DIR__. '/../templates/alerts.php';?>
        <form class = "form" method = "POST" action = "/dashboard/create_project">
            <?php include_once __DIR__. '/project-form.php';?>
            <input type = "submit" value = "Create Project">
        </form>
    </div>


    <?php
        include_once __DIR__. '/footer-dashboard.php';
    ?>