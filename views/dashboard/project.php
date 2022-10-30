    <!-- Vista de introducir datos en un Proyecto -->
    <!-- header -->
    <?php include_once __DIR__. '/header-dashboard.php';?>

        <div class = "container-sm">
            <div class = "container-new-task">
                <button
                    type = "button"
                    class = "add-task"
                    id = "add-task"
                >&#43; New Task
                </button>
            </div>
            <div class = "filters" id = "filters">
                <div class = "filters-inputs">
                    <h2>Filters:</h2>
                    <div class = "field">
                        <label for = "all">All</label>
                        <input
                            type = "radio"
                            id = "all"
                            name = "filter"
                            value = ""
                            checked
                        >
                    </div>
                    <div class = "field">
                        <label for = "all">Completed</label>
                        <input
                            type = "radio"
                            id = "completed"
                            name = "filter"
                            value = "1"
                        >
                    </div>               
                    <div class = "field">
                        <label for = "all">Pending</label>
                        <input
                            type = "radio"
                            id = "pending"
                            name = "filter"
                            value = "0"
                        >
                    </div>
                </div>
            </div>
            <ul id = "tasks-list" class = "tasks-list">

            </ul>
        </div>

    <?php
        include_once __DIR__. '/footer-dashboard.php';
    ?>
    <?php //Añadimos más script .=
    $script .= '
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="/build/js/tasks.js"></script>
    ';
    ?>