<?php

    ///////////////////
    ///\u0 to string///
    //////////Rigaard//
    ///////16.03.2021//
    function __decodeU($inputvalue) {
        $js = json_decode('["'.$inputvalue.'"]');
        $result = array_pop($js);
        return $result;
    }
    
    ////////////////////////
    ///? to normal string///
    ///////////////Rigaard//
    ////////////16.03.2021//
    function __encodeCP($inputvalue){
        $result = preg_replace_callback('/\\\\u([a-f0-9]{4})/i', function ($m) { return chr(hexdec($m[1])-1072+224);}, $inputvalue);
    return iconv('cp1251', 'utf-8', $result);
    }
    
?>