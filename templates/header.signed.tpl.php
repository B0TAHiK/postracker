<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
    <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src=img/logo.png style="margin-top: -3px;"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li{isIndex}><a href="#">POS Monitor</a></li>
                
                
                <!--<?php if ($_SESSION[groupID] > 1)echo "<li{isSupers}><a href=\"#\">Supercapitals</a></li>"; ?>
                <?php if ($_SESSION[groupID] > 2) echo "<li{isAdmin}><a href=\"#\">Admin</a></li>"; ?>-->
                <li><a href="https://redalliance.pw">Forum</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Logged as: <b>{charName}</b> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li{isSettings}><a href="#">Settings</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>