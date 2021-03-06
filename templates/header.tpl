{% if loggedIN > 0 %}
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
                <li{{isIndex}}><a href="index.php">POS Monitor</a></li>
                {% if groupID > 1 %}<li{{isSupers}}><a href="superCapitalMonitoring.php">Supercapitals</a></li>{% endif %}
                {% if groupID > 2 %}<li{{isAdmin}}><a href="admin.php">Admin</a></li>{% endif %}
                <li><a href="https://redalliance.pw">Forum</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Logged as: <b>{{charName}}</b> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li{{isSettings}}><a href="settings.php">Settings</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>
{% else %}
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
                <li{{isIndex}}><a href="../index.php">POS Monitor</a></li>
                <li{{isLogin}}><a href="../login.php">Login</a></li>
                <li{{isReg}}><a href="../reg.php">Register</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>
{% endif %}