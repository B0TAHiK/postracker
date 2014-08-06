<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Pos Monitor</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>
<body>
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
              <li class="active"><a href="#">POS Monitor</a></li>
              <li><a href="#">Supercapitals</a></li>
              <li><a href="#">Admin</a></li>
              <li><a href="#">Forum</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Logged as: <b>greg2010</b> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Settings</a></li>
                      <li><a href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
      </nav>
    <div class="container">
                <div class="page-header">
                    <h1>POS Monitor</h1>
                </div>
                <div class="panel panel-default">
                    <!-- Default panel contents -->
                    <div class="panel-heading">
                        <h5>Owner: <b>RED Logistics Union</b></h5>
                    </div>

                    <!-- Table -->
                    <table class="table table-striped table-bordered table-hover">                      <thead><tr id="title">
                                <th width="10%">System:</th>
                            <th width="20%">Type:</th>
                            <th width="15%">Moon:</th>
                            <th width="10%">State:</th>
                            <th width="15%">Fuel left<br>(Days and hours):</th>
                            <th width="15%">Stront time left<br>(reinforce timer):</th>
                            <th width="15%">Silo information</th>
                            
                        </tr></thead><tbody>                                <tr id="colored">
                                    <td>9PX2-F</td>
                                    <td>Minmatar Control Tower Medium</td>
                                    <td>9PX2-F IV - Moon 4</td>
                                    <td>Online</td>
                                    <td>16d 13h</td><td>1d 11h</td><td><table><tbody><tr><td>Hafnium:</td><td>10200/25000</td></tr></tbody></table></td></tr>                                <tr class="warning">
                                    <td>F3-8X2</td>
                                    <td>Minmatar Control Tower</td>
                                    <td>F3-8X2 VI - Moon 1</td>
                                    <td>Online</td>
                                    <td>1d 0h</td><td>1d 17h</td><td>No silo</td></tr>                                <tr class="danger">
                                    <td>U-QVWD</td>
                                    <td>Minmatar Control Tower Medium</td>
                                    <td>U-QVWD VII - Moon 2</td>
                                    <td><b>Reinforced!</b></td>
                                    <td>21d 18h</td><td>1d 13h</td><td>No silo</td></tr>                                <tr>
                                    <td>G-ME2K</td>
                                    <td>Minmatar Control Tower</td>
                                    <td>G-ME2K III - Moon 1</td>
                                    <td>Online</td>
                                    <td>27d 21h</td><td>1d 15h</td><td>No silo</td></tr>                                <tr id="colored">
                                    <td>HM-UVD</td>
                                    <td>Minmatar Control Tower Small</td>
                                    <td>HM-UVD V - Moon 1</td>
                                    <td>Online</td>
                                    <td>20d 0h</td><td>1d 12h</td><td>No silo</td></tr>                                <tr>
                                    <td>9U6-SV</td>
                                    <td>Minmatar Control Tower</td>
                                    <td>9U6-SV VIII - Moon 1</td>
                                    <td>Online</td>
                                    <td>27d 22h</td><td>1d 15h</td><td>No silo</td></tr>                                <tr id="colored">
                                    <td>DSS-EZ</td>
                                    <td>Minmatar Control Tower Medium</td>
                                    <td>DSS-EZ VII - Moon 1</td>
                                    <td>Online</td>
                                    <td>13d 13h</td><td>1d 12h</td><td><table><tbody><tr><td>Cadmium:</td><td>16700/50000</td></tr></tbody></table></td></tr>                                <tr>
                                    <td>MB4D-4</td>
                                    <td>Minmatar Control Tower Medium</td>
                                    <td>MB4D-4 VII - Moon 3</td>
                                    <td>Online</td>
                                    <td>13d 21h</td><td>1d 12h</td><td><table><tbody><tr><td>Hafnium:</td><td>7800/25000</td></tr></tbody></table></td></tr>                                <tr id="colored">
                                    <td>Y1-UQ2</td>
                                    <td>Minmatar Control Tower Small</td>
                                    <td>Y1-UQ2 VI - Moon 2</td>
                                    <td>Online</td>
                                    <td>26d 5h</td><td>1d 12h</td><td><table><tbody><tr><td>Cobalt:</td><td>5800/50000</td></tr></tbody></table></td></tr>                                <tr>
                                    <td>7R5-7R</td>
                                    <td>Minmatar Control Tower Small</td>
                                    <td>7R5-7R VIII - Moon 5</td>
                                    <td>Online</td>
                                    <td>26d 15h</td><td>1d 12h</td><td>No silo</td></tr></tbody>
                    </table>
                </div>
                    <?php include "bottom.php" ?>
        </div>
</body>
</html>