<?php
function _vd($var, $die = false){
    global $HL_DEBUG; 
   
   
    if(isset($HL_DEBUG) && $HL_DEBUG){
        $a = debug_backtrace();
        
        $F = str_replace(ABSPATH, '', $a[0]['file']);
        $L = $a[0]['line'];
        echo "<div style='text-align:left; border:2px solid red; background-color:white; color:black;'><strong>chamado em: <em>$F - (linha: $L)</em></strong><hr/>";
        echo '<div style="max-height:500px; width:100%; overflow:auto;"><pre>';
        var_dump($var);
        echo '</pre></div></div>';
        
        if($die)
            die;
    }
}


function _pr($var, $die = false){
    global $HL_DEBUG;
     
    if(isset($HL_DEBUG) && $HL_DEBUG){
        $a = debug_backtrace();
       
        $F = str_replace(ABSPATH, '', $a[0]['file']);
        $L = $a[0]['line'];
        echo "<div style='text-align:left; border:2px solid red; background-color:white; color:black; padding:7px; margin:7px;'><strong>chamado em: <em>$F - (linha: $L)</em></strong><hr/>";
        echo '<div style="max-height:500px; width:100%; overflow:auto;"><pre>';
        print_r($var);
        echo '</pre></div></div>';
        
        if($die)
            die;
    }
}