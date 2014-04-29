<?php
echo<<<_END
    <div id='head'><img class='logo' src=lg2.png>
    <span id='toptext'>POS tracker</span>
    </div>
    <ul id='bar'>
        <li
_END;
    if ($thisPage=="index")echo " id=\"currentpage\"";
      echo "><a href=\"/\">Main</a></li>"
   . "<li";
    if ($thisPage=="login")
        echo " id=\"currentpage\"";
        echo "><a href=\"login.php\">Login</a></li>
        <li";
        if ($thisPage=="reg")
            echo " id=\"currentpage\"";
      echo "><a href=\"reg.php\">Register</a></li>
        <li";
      if ($thisPage=="admin") 
      echo " id=\"currentpage\"";
      echo"><a href=\"admin.php\">Admin</a></li>";
      echo "</ul>";
?>
