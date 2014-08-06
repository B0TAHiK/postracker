<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once 'init.php';
        require_once 'db_con.php';
        var_dump($db->query('SELECT `quantity` FROM `invControlTowerResources`'));
        
//        class test {
//            public $num;
//            
//            function __construct($num = NULL) {
//                if ($num == 0) {
//                    throw new RuntimeException("CAN'T SHOW ZERO!");
//                }
//            }
//            public function testException($num) {
//                try {
//                    $num = $this->$num;
//                    return "this is num: $num";
//                } catch (Exception $e) {
//                    $num = $num+1;
//                }
//            }
//        }
//        $testVar = new test(0);
//        echo $testVar->testException();
        ?>
    </body>
</html>
