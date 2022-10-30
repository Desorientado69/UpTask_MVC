<div class = "bar-mobile">
    <h1>UpTask</h1>
    <div class = "menu">
        <img id = "mobile-menu" src = "/build/img/menu.svg" alt = "Menu image"/>
    </div>
</div>
<div class = "bar"> 
    <p>Hello: <span><?php echo $_SESSION['name']. " " .$_SESSION['surnames'] ;?></span></p>
    <a href = "/logout" class = "logout">Log Out</a>
</div>