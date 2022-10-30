<aside class = "sidebar">
    <div class = "sidebar-container">
        <h2>UpTask</h2>
        <div class = "close-menu">
            <img id= "close-menu" src = "/build/img/close.svg" alt = "Close menu image"/>
        </div>
    </div>
    <nav class = "sidebar-nav">
        <a class = "<?php echo ($title === 'Proyectos') ? 'active': ''; ?>" href = "/dashboard">Projects</a>
        <a class = "<?php echo ($title === 'Crear Proyecto') ? 'active': ''; ?>" href = "/dashboard/create_project">Create Projects</a>
        <a class = "<?php echo ($title === 'Perfil') ? 'active': ''; ?>" href = "/dashboard/profile">Profile</a>
    </nav>
    <div class = "logout-mobile">
        <a href = "/logout" class = "logout">Log Out</a>
    </div>
</aside> 