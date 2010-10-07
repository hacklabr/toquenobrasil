<?php
ini_set(max_execution_time,30000);
ini_set('memory_limit','128000M');
/* 
 * script para sanitize os nomes dos arquivos mp3 tanto do
 * filesystem quanto no banco de dados. tivemos que fazer isso pois o player
 * de áudio que utilizamos não consegue tocar arquivos que tenham acento no
 * nome
 */

require_once('../src/wp-config.php');
require_once('../src/wp-includes/formatting.php');

$server = 'localhost';
$db = 'toquenobrasil';
$user = 'root';
$pass = '';

$conn = mysql_connect($server, $user, $pass);
mysql_select_db($db);
if (!$conn)
    die('Problema ao conectar ao MySQL: ' . mysql_error());

mysql_query("SET NAMES 'utf8'");

$result = mysql_query("select option_value from wp_options where option_name = 'upload_path'");
$uploadsDir = mysql_result($result, 0);




if (!file_exists($uploadsDir)) {
    
    $uploadsDir =  '../src/wp-content/uploads/';
    if (!file_exists($uploadsDir)) {
        echo "\nDiretório $uploadsDir inexistente.\n\n";
        die;    
    }    
} 

if (!preg_match('|/$|', $uploadsDir))
    $uploadsDir .= '/';
 


if (!is_writable($uploadsDir)) {
    echo "\nO usuário do PHP não tem permissão para escrever no diretório $uploadsDir\n\n";
    die;
}

chdir($uploadsDir);

$result = mysql_query("select option_value from wp_options where option_name = 'siteurl'");
$siteurl = mysql_result($result, 0);
$uploadsUrl = $siteurl . $uploadsDir;

// seleciona o nome de todos os arquivos do tipo musica ou radio
$query = mysql_query("select pm.meta_value, post_id from wp_postmeta pm, wp_posts p where pm.post_id = p.ID AND pm.meta_key = '_wp_attached_file' AND p.post_type IN ('music') ORDER BY p.post_date ASC");

while ($res = mysql_fetch_row($query)) {
    preg_match('|(.+/)(.*)|', $res[0], $matches);
    $dir = $matches[1];
    $fileName = $matches[2];
    $filePath = $dir . $fileName;

    $postName = mysql_result(mysql_query("SELECT post_name FROM wp_posts WHERE ID = '{$res[1]}'"), 0);
    echo "Convertendo arquivo " . $siteurl . '/acervo/musica/' . $dir . $postName . "\n";

    $newFileName = toquenobrasil_sanitize_file_name($fileName);
    $newFilePath = $dir . $newFileName;
    
    var_dump($filePath, $newFilePath);
    
    if (file_exists($filePath)) {
        rename($filePath, $newFilePath);
        
        mysql_query("UPDATE wp_postmeta SET meta_value = '$newFilePath' WHERE meta_value = '$filePath'");
        mysql_query("UPDATE wp_posts SET guid = '$uploadsUrl$newFilePath' WHERE guid = '$uploadsUrl$filePath'");
        
    }
    /*
    // queremos mexer apenas com os arquivos que possuem acento no nome
    if (preg_match('/[áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ]/', $fileName)) {
        $postName = mysql_result(mysql_query("SELECT post_name FROM wp_posts WHERE guid = '$uploadsUrl$filePath'"), 0);
        echo "Convertendo arquivo " . $siteurl . '/acervo/musica/' . $dir . $postName . "\n";

        $newFileName = sanitize_file_name($fileName);
        $newFilePath = $dir . $newFileName;
        rename($filePath, $newFilePath);
        
        mysql_query("UPDATE wp_postmeta SET meta_value = '$newFilePath' WHERE meta_value = '$filePath'");
        mysql_query("UPDATE wp_posts SET guid = '$uploadsUrl$newFilePath' WHERE guid = '$uploadsUrl$filePath'");
    }
    * */
}

mysql_close($conn);

?>
