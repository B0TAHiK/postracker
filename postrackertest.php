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
        $postracker = new postracker;
        $insert = array(
            "time" => "1990-05-13, 13:23:05",
            "systemID" => "123456",
            "ownerID" => "CHANGE",
            "typeID" => "242",
            "rfType" => "1",
            "authorID" => "9"
        );
        $id = array(
            'id' => 2,
            'id' => 8,
            'ownerID' => 'CHANGE'
        );
//        $dbDump = $postracker->addToDB($id, $insert);
//        $db->delete('postracker', $id, 'OR');
        var_dump($db);
        ?>
    </body>
</html>
