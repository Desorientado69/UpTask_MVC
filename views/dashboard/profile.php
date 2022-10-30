    <!-- Vista de Perfil e usuario en DashBoard -->
    <!-- header -->
    <?php
        include_once __DIR__. '/header-dashboard.php';
    ?>
    <!-- Se incluyen alertas para la validación del formulario -->
    <div class = "container-sm">
        <?php include_once __DIR__. '/../templates/bar.php';?>
        <?php include_once __DIR__. '/../templates/alerts.php';?>
        <a href="/dashboard/change-password" class="link">Change Password</a>
        <form action = "" method = "POST" class = "form">
            <div class = "field">
                <label for = "name">Name</label>
                <input
                    type = "text"
                    id = "name"
                    name = "name"
                    autocomplete = "username"
                    placeholder = "User Name"
                    value = "<?php echo $user->name?>"
                >
            </div>
            <div class = "field">
                <label for = "surnames">Surnames</label>
                <input
                    type = "text"
                    id = "surnames"
                    name = "surnames"
                    autocomplete = "usersurnames"
                    placeholder = "User Surnames"
                    value = "<?php echo $user->surnames?>"
                >
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input
                    type = "email"
                    id = "email"
                    name = "email"
                    autocomplete = "User email"
                    placeholder = "Email"
                    value = "<?php echo $user->email?>"
                >
            </div>
            <input type = "submit" value = "Update Profile">
        </form>
    </div>
    <?php
        include_once __DIR__. '/footer-dashboard.php';
    ?>