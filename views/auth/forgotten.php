    <!-- Vista de enviar mensaje al olvidar el password -->
    <div class="container forgotten">
    <?php 
        include_once __DIR__. '/../templates/site-name.php';
    ?>

    <div class="container-sm">
        <p class="description-page">Get your Access Back to UpTask. Send Password Restore Email</p>
        <?php
            include_once __DIR__. '/../templates/alerts.php';
        // la variable $show=true mostrar el formulario
        if ($show){ ?> 
        <form class="form" method="POST" action="/forgotten">
            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="useremail"
                    placeholder="Email"
                >
            </div>
            <input type="submit" class="button" value="Send Instructions">
        </form>
        <?php }; ?>                   
        <div class="actions">
            <a href="/">Do you Already have an Account? Log In</a>
            <a href="/create_account">Don't you Have an Account Yet? Create an Account</a>
        </div>
    </div><!-- .container-sm -->
</div><!-- .container -->