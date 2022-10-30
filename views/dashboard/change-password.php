    <!-- Vista de Perfil e usuario en DashBoard -->
    <!-- header -->
    <?php
        include_once __DIR__. '/header-dashboard.php';
    ?>
    <!-- Se incluyen alertas para la validación del formulario -->
    <div class = "container-sm">
        <?php include_once __DIR__. '/../templates/bar.php';?>
        <?php include_once __DIR__. '/../templates/alerts.php';?>
        <a href="/dashboard/profile" class="link">Back to Profile</a>

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
                    disabled
                >
            </div>
            <div class = "field">
                <label for = "surnames">Surnames</label>
                <input
                    type = "text"
                    id = "surnames"
                    name = "surnames"
                    autocomplete = "User Surnames"
                    placeholder = "User Surnames"
                    value = "<?php echo $user->surnames?>"
                    disabled
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
                    disabled
                >
            </div>
            <div class="field">
                <label for="current_password">Current Password</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    autocomplete="Current Password"
                    placeholder="Current Password"
                >
            </div>
            <div class="field">
                <label for="new_password">New Password</label>
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    autocomplete="New Password"
                    placeholder="New Password"
                >
            </div>
            <div class="field">
                <label for="repeated_password">Repeat Password</label>
                <input
                    type="password"
                    id="repeated_password"
                    name="repeated_password"
                    autocomplete="New Password"
                    placeholder="Repeat New Password"
                >
            </div>
            <input type = "submit" value = "Update Profile">
        </form>
    </div>
    <?php
        include_once __DIR__. '/footer-dashboard.php';
    ?>