<?php 
///////////////////
///some settings///
//////////Rigaard//
///////16.03.2021//
$__sqlSaveException = true;
$__sendMail = true;

////////////////////
///All exceptions///
///////////Rigaard//
////////16.03.2021//
$__hideException = true;
    $__arrayExceptions = array(
    1 => "E_ERROR", 
    2 => "E_WARNING",
    4 => "E_PARSE",
    8 => "E_NOTICE",
    16 => "E_CORE_ERROR",
    32 => "E_CORE_WARNING",
    64 => "E_COMPILE_ERROR",
    128 => "E_COMPILE_WARNING",
    256 => "E_USER_ERROR",
    512 => "E_USER_WARNING",
    1024 => "E_USER_NOTICE",
    2048 => "E_STRICT",
    4096 => "E_RECOVERABLE_ERROR",
    8192 => "E_DEPRECATED",
    16384 => "E_USER_DEPRECATED",
    32767 => "E_ALL");
    
/////////////////////////////////////
///Turning warning into exceptions///
////////////////////////////Rigaard//
/////////////////////////16.03.2021//
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // error was suppressed with the @-operator
    if (!(error_reporting() & $errno)) {
        return false;
    }
    $errstr = htmlspecialchars($errstr);
    
    SaveSendException("MariaSQL", $errno, $errstr, $errfile, $errline);
    
    //throw new ErrorException($errstr, 0, $errno, $errfile, $errline); //dafault throw
});

function SaveSendException($saveType, $errno, $errstr, $errfile, $errline) {
    
    $errInfo = $errfile . " " . $errline;
    
    switch ($saveType) {
            case "MariaSQL":           
            SaveMariaSQL($errno, $errstr, $errInfo);
            break;

            default:
            //SaveMariaSQL($errno, $errstr, $errfile, $errline);
            break;
    }

    global $__sendMail;
    
    if($__sendMail) {
        global $__arrayExceptions;
        require_once 'SendMail.php';
        $message =  sprintf('%1$s: %2$s %3$s', $__arrayExceptions[$errno], $errstr, $errInfo);
        
        //XmlSettings load
        if(file_exists("AdminMailContainer.xml")) {
            $file = file_get_contents("AdminMailContainer.xml");
            $xmlMailSettings = simplexml_load_string($file); //?? not work ?? maybe try another round
            $json = json_encode($xmlMailSettings); // convert xml to json
            $emails = json_decode($json,TRUE);
            $arrayTo = array();
                foreach ($emails as $currentEmail) {
                    if($currentEmail["sendexception"] === "true") {
                        array_push($arrayTo, $currentEmail["adress"]);
                    }
                }
            SendMail($__arrayExceptions[$errno], $arrayTo, $message);
        }
    }
}

function SaveMariaSQL($errno, $errstr, $errInfo) {
    global $__arrayExceptions;
    global $__sqlSaveException;
    if($__sqlSaveException) {
        require_once 'SqlInsert.php';

        SaveError($__arrayExceptions[$errno], $errstr, $errInfo);
    }
}

//////////////////////////
///My Exception Handler///
/////////////////Rigaard//
//////////////16.03.2021//
function ExceptionHandler ($exception) {
    echo "<b>Error: </b>" . $exception;
}

////////////////////////////////
///Set user handler exception///
///////////////////////Rigaard//
////////////////////16.03.2021//
set_exception_handler('ExceptionHandler');


//error_reporting(E_ERROR | E_PARSE ); 
error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE); // set warning
?>