<?php
/**
 * Description of logs
 *
 * @author Григорий
 */
class logs {
    function endlog($m) {
        echo $m . "\n[" . str_repeat("=",100) . "]\n";
        exit;
    }
}
