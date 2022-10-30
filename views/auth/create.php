    <!-- Vista de Crear una Cuenta -->
    <div class="container create">
    <?php
        include_once __DIR__. '/../templates/site-name.php';
    ?>

    <div class="container-sm">
        <p class="description-page">Create UpTask Account</p>
        <?php
        include_once __DIR__. '/../templates/alerts.php';
        // la variable $show=true mostrar el formulario
        if ($show){ ?> 
            <form class="form" method="POST" action="/create_account">
                <div class="field">
                    <label for="name">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        autocomplete="username"
                        placeholder="Name"
                        value="<?php echo $user->name; ?>"
                    >
                </div>
                <div class="field">
                    <label for="surnames">Surnames</label>
                    <input
                        type="text"
                        id="surnames"
                        name="surnames"
                        autocomplete="usersurnames"
                        placeholder="Surnames"
                        value="<?php echo $user->surnames; ?>"
                    >
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        autocomplete="useremail"
                        placeholder="Email"
                        value="<?php echo $user->email; ?>"
                    >
                </div>            
                <div class="field">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        placeholder="Password"
                    >
                </div>
                <div class="field">
                    <label for="repeated_password">Repeat Password</label>
                    <input
                        type="password"
                        id="repeated_password"
                        name="repeated_password"
                        autocomplete="current-password"
                        placeholder="Repeat Password"
                    >
                </div>
                <input type="submit" class="button" value="Create Account">
            </form>
        <?php } else {
            $title = 'Email Send';
        }; ?>
        <div class="actions">
            <a href="/">Do you Already have an Account? Log In</a>
            <a href="/forgotten">Did you Forget your Password? Restore Password</a>
        </div>
    </div><!-- .container-sm -->
</div><!-- .container -->