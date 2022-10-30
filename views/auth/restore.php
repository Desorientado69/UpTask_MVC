    <!-- Vista de introducir dos veces el password para restablecerlo -->
    <div class="container restore">
    <?php           //Template con el nombre del sitio--------------------------
        include_once __DIR__. '/../templates/site-name.php';
    ?>

    <div class="container-sm">
        <p class="description-page">Restore UpTask Password. Enter the New Password</p>
        <?php           //Alertas de error o de éxito--------------------------
        include_once __DIR__. '/../templates/alerts.php';
        // la variable $show=true mostrar el formulario
        if ($show){ ?> 
        <form class="form" method="POST">
            <div class="field">
                <label for="password">New Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="New Password"
                >
            </div>
            <div class="field">
                <label for="repeated_password">Repeat Password</label>
                <input
                    type="password"
                    id="repeated_password"
                    name="repeated_password"
                    autocomplete="new-password"
                    placeholder="Repeat Password"
                >
            </div>
            <input type="submit" class="button" value="Restore Password">
        </form>
        <?php }; ?>
        <div class="actions">
            <a href="/">Do you Already have an Account? Log In</a>
            <a href="/create_account">Don't you Have an Account Yet? Create an Account</a>
        </div>
    </div><!-- .container-sm -->
</div><!-- .container -->