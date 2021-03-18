<?php     
    function SaveError($Type, $Message, $ErrInfo) {
        include 'PHPtools/Config.php'; //get sql connection
        $qvr = "INSERT INTO errorlog (Type, Message, ErrFile) VALUES ('$Type','$Message','$ErrInfo')";
        $db->query($qvr);
        $db->close();
    }
?>