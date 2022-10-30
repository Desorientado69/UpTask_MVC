    <!-- Vista de Index DashBoard -->
    <!-- header -->
    <?php
        include_once __DIR__. '/header-dashboard.php';
    ?>

    <?php
        if(count($projects) === 0) {?>
        <p class = "no-projects">No Projects Yet<a href = "/dashboard/create_project">Start by Creating a New Project</a></p>
    <?php } else {?>
        <ul class = "list-projects">
            <?php foreach ($projects as $key => $project) {?>
                <a href = "/dashboard/project?url=<?php echo $project->url; ?>">
                    <li class = "project">
                        <?php echo $project->name; ?>
                    </li>
                </a>
            <?php };?>
        </ul>
    <?php };?>
    <?php
        include_once __DIR__. '/footer-dashboard.php';
    ?>