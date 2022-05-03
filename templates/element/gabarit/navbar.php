<!-- Start Top Bar -->
<div class="top-bar">
    <div class="top-bar-left">
        <ul class="menu">
            <li class="menu-text">Marketing Site</li>
            <li><a href="#">One</a></li>
            <li><a href="#">Two</a></li>
            <?php if( $this->getRequest()->getSession()->read('Auth.name') != null){
                echo '<h5> Bienvenue '. $this->getRequest()->getSession()->read('Auth.name'). '</h5>';};
            ?>
        </ul>
    </div>

    <div>



    </div>

    <div class="top-bar-right">
        <ul class="menu">
            <li><a href="#">Three</a></li>
            <li><a href="#">Four</a></li>
            <li><a href="#">Five</a></li>
            <?php if( $this->getRequest()->getSession()->read('Auth.email') != null){
                echo '<li><a href="/users/logout">logout</a></li>';
            }else {
                echo '<li><a href="/users/login">login</a></li>';
            };
            ?>
        </ul>
    </div>
</div>
<!-- End Top Bar -->
