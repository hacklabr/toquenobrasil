<?php
class UE_HTML extends A_UserExporter{
    public static function getFileExtension(){
        return 'html';
    }
    
    public static function getFileExtensionDescription(){
        return __('HTML Table','wp-users-exporter');
    }
    
    protected function printHeader(){
       echo '<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>';
         
        
        echo '<table style="font-size:10px;" border=1><tr>';
        foreach ($this->cols as $col)
            echo "<th>".htmlentities(utf8_decode($this->descriptions[$col]))."</th>";
            
        echo '</tr><tbody>
    </body>
</html>';
    }
    
    protected function printFooter(){
        echo '</tbody></table>';
    }
    
    protected function printUser($user){
        echo '<tr>';
        foreach ($this->cols as $col){
           $data = htmlentities(utf8_decode($user->$col));
           echo "<td>$data</td>"; 
        }
        echo '</tr>';
    }
}