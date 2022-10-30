    <!-- Vista del Login de la página -->
<div class="container login">
    <?php 
        include_once __DIR__. '/../templates/site-name.php';
    ?>

    <div class="container-sm">
        <p class="description-page">Log In to UpTask</p>
        <?php           //Alertas de error o de éxito--------------------------
            include_once __DIR__. '/../templates/alerts.php';
        ?> 
        <form class="form" method="POST" action="/">
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
            <input type="submit" class="button" value="Log In">
        </form>
        <div class="actions">
            <a href="/create_account">Don't you Have an Account Yet? Create an Account</a>
            <a href="/forgotten">Did you Forget your Password? Restore Password</a>
        </div>
    </div><!-- .container-sm -->
</div><!-- .container -->