<?php
    require_once ('includes/vendor/autoload.php');
    use \Statickidz\GoogleTranslate;
        
    function Translation($source, $target, $text) {
        
        $trans = new GoogleTranslate();
        $result = $trans->translate($source, $target, $text);
        
        return $result;
    }
?>