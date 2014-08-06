<?php
if ($loggedIN == 0) {
    echo<<<_END
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
              <li
_END;
        if ($thisPage=="index")echo " class=\"active\"";
          echo "><a href=\"/pos\">POS Monitor</a></li>"
       . "<li";
        if ($thisPage=="login")
            echo " class=\"active\"";
            echo "><a href=\"login.php\">Login</a></li>
            <li";
            if ($thisPage=="reg")
                echo " class=\"active\"";
          echo "><a href=\"reg.php\">Register</a></li>";
          echo "</ul>";
          echo<<<_END
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
      </nav>
_END;
} else {
    $db->openConnection();
    $cookieSID = $db->sanitizeMySQL($_COOKIE[SID]);
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $result = $db->query($query);
    $charInfo = $db->fetchAssoc($result);
    $groupID = $charInfo[groupID];
    $query = "SELECT * FROM `groups` WHERE `id` = '$groupID' LIMIT 1";
    $result = $db->query($query);
    $groups = $db->fetchAssoc($result);
    $groupName = $groups[name];
     echo<<<_END
        <div id='head'><img class='logo' src=img/lg2.png>
        <span id='toptext'>POS tracker</span>
            <div id='char'>
                <img src=https://image.eveonline.com/Character/$charInfo[characterID]_64.jpg>
                <span id='char'>
                    Welcome,<br>
                    $charInfo[char]!<br>
                    [$groupName]
                </span>
            </div>
        </div>
        <ul id='bar'>
            <li
_END;
        if ($thisPage=="index")echo " id=\"currentpage\"";
          echo "><a href=\"/pos\">pos monitor</a></li>";
          if ($_SESSION[groupID] > 2) {
            echo "<li";
            if ($thisPage=="admin")
            echo " id=\"currentpage\"";
            echo"><a href=\"admin.php\">admin</a></li>";
          }
          if ($_SESSION[groupID] > 1) {
            echo "<li";
            if ($thisPage=="supers")
            echo " id=\"currentpage\"";
            echo"><a href=\"superCapitalMonitoring.php\">supercapitals</a></li>";
          }
          echo "<li";
          if ($thisPage=="settings")echo " id=\"currentpage\"";
          echo "><a href=\"settings.php\">settings</a></li>";
          echo "<li><a href=\"https://redalliance.pw\">forum</a></li><li";
          echo "><a href=\"logout.php\">logout</a></li>";
          echo "</ul>";
}
?>
