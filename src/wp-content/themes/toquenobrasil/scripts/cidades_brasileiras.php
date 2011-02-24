<?php
require_once('../../../../wp-load.php');

if (!current_user_can('manage_options'))
  die ('sem permissao');
else
  echo 'iniciando script<hr/>';

$origensNaoEncontradas = 0;
$residenciasNaoEncontradas = 0;

$origensEncontradas = 0;
$residenciasEncontradas = 0;

$origensEmBranco = 0;
$residenciasEmBranco = 0;

$residenciaDistritoFederal = 0;
$origemDistritoFederal = 0;


$substituicoes = array(
    'embú das artes' => 'Embu',
    'embu das artes' => 'Embu',
    'sp' => 'São Paulo',
    'rj' => 'Rio de Janeiro',
    'poa' => 'Porto Alegre',
    'ceilanfia norte' => 'brasília',
    'sao jose rio preto' => 'São José do Rio Preto',
    'taboão' => 'Taboão da Serra'

  );


function TNB_getCidade($uf, $cidade){
  global $naoEncontrados;
  global $wpdb;
  global $tnb_cidades;
  global $substituicoes;
  
  if(@isset($tnb_cidades[$uf][$cidade]))
    return $tnb_cidades[$uf][$cidade];

  $cidadeOriginal = $cidade;

  $cidade = str_ireplace('- '.$uf, '', $cidade);
  $cidade = str_ireplace('-'.$uf, '', $cidade);

  if(substr($cidade, -2) == strtoupper($uf))
          $cidade = substr($cidade,0, -2);


  foreach($substituicoes as $de => $por)
    if($de == strtolower(trim($cidade))) $cidade = $por;



  $cidade = trim($cidade);
  if($uf)
    $query = "
    SELECT
      municipio.*
    FROM
      municipio,
      uf
    WHERE
      (uf.sigla = '$uf' AND
      municipio.ufid = uf.id AND
      municipio.nome = '$cidade')";
  else
    $query = "
    SELECT
      municipio.*
    FROM
      municipio,
      uf
    WHERE
      municipio.nome = '$cidade'";


  $result = $wpdb->get_row($query);
  $tnb_cidades[$uf][$cidade] = $result;
  if(!$result){ 
  
    $naoEncontrado['uf'] = $uf;
    $naoEncontrado['cidade'] = $cidadeOriginal;
    $naoEncontrados[] = $naoEncontrado;
  }
  return $result;
}

// SE AS TABELAS uf E municipio NÃO EXISTEM...
if($wpdb->get_var("SHOW TABLES LIKE 'uf'") != 'uf' AND $wpdb->get_var("SHOW TABLES LIKE 'municipio'") != 'municipio') {
	global $wpdb;

        echo 'criando banco de dados de cidades...<br/>';
	$sql = file_get_contents('brasil.sql');
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	echo 'banco de dados criado. <br/><hr/>alterando usuários...';	
}

$table_users = $wpdb->prefix.'users';
$table_usermeta = $wpdb->prefix.'usermeta';


// SELECIONA OS USUÁRIOS QUE TENHAM AS CAPABILITIES artista E produtor
$query_users = "
	SELECT 
		$table_users.user_login,
		$table_users.user_email,
		$table_users.display_name,
		
		$table_usermeta.user_id as id, 
		$table_usermeta.meta_value as capability 
	FROM 
		$table_users,
		$table_usermeta
	WHERE 
		$table_users.id = $table_usermeta.user_id AND
		($table_usermeta.meta_value LIKE '%artista%' OR $table_usermeta.meta_value LIKE '%produtor%') AND 
		$table_usermeta.meta_key = 'wp_capabilities'";
		
$users = $wpdb->get_results($query_users);



foreach($users as $usr){
  $output = "<br />";

  list($capability) = array_keys(unserialize($usr->capability));

  unset($origem_pais);
  unset($origem_estado);
  unset($origem_cidade);
  
  
  $ometadados = $wpdb->get_results("SELECT meta_key, meta_value FROM $table_usermeta WHERE user_id = '$usr->id' AND (meta_key = 'origem_cidade' OR meta_key = 'origem_estado' OR meta_key = 'origem_pais')");
  
  foreach ($ometadados as $ometadado){
    $key = $ometadado->meta_key;
    $$key = $ometadado->meta_value;
  }
  
  
  if(!isset($origem_pais)){
    $num_origem_paises = $num_origem_paises ? $num_origem_paises+1 : 1;
    $wpdb->query("INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) VALUES ('$usr->id','origem_pais','BR')");
  }
  
  if(!isset($origem_estado)){
    $num_origem_estado = $num_origem_estado ? $num_origem_estado+1 : 1;
    $wpdb->query("INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) VALUES ('$usr->id','origem_estado','')");
  }
  
  if(!isset($origem_cidade)){
    $num_origem_cidade = $num_origem_cidade ? $num_origem_cidade+1 : 1;
    $wpdb->query("INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) VALUES ('$usr->id','origem_cidade','')");
  }
  // PEGA A CIDADE E ESTADO DE ORIGEM CADASTRADOS
  //$origem_cidade = $wpdb->get_var("SELECT meta_value FROM $table_usermeta WHERE user_id = '$usr->id' AND meta_key = 'origem_cidade'");
  //$origem_estado = $wpdb->get_var("SELECT meta_value FROM $table_usermeta WHERE user_id = '$usr->id' AND meta_key = 'origem_estado'");

  $output .= "alterando <strong>$capability (id: $usr->id)</strong> origem: '$origem_cidade' - '$origem_estado' (";
  // PROCURA O MUNICIPIO DE ORIGEM A PARTIR DOS DADOS CADASTRADOS
  $origem = TNB_getCidade($origem_estado, $origem_cidade);


  // SE O MUNICIPIO FOI ENCONTRADO...
  if($origem){
    $origensEncontradas++;

    $output .= "encontrado : $origem->id";
    // ... PADRONIZA O NOME DA CIDADE
    $queryUpdateOrigemCidade = "
      UPDATE
        $table_usermeta
      SET
        meta_value = '$origem->nome'
      WHERE
        meta_key = 'origem_cidade' AND
        user_id = '$usr->id'";

    // E DEFINE O PAÍS COMO 'BR'
    $queryUpdateResidenciaCidade = "
        UPDATE
          $table_usermeta
        SET
          meta_value = 'BR'
        WHERE
          meta_key = 'origem_pais' AND
          user_id = '$usr->id'";

    $wpdb->query($queryUpdateOrigemCidade);

  }else{
    $usr->ESTADO = $origem_estado;
    $usr->CIDADE = $origem_cidade;
    
    $nao_encontrados[$capability][$usr->id] = $usr;
    $output .= "<span style='color:red'>não encontrado</span>";
    $origensNaoEncontradas++;
  }
  $output .= ') ';

  // SOMENTE PARA ARTISTAS
  if($capability == 'artista'){

    unset($banda_pais);
    unset($banda_estado);
    unset($banda_cidade);
    
      // PEGA A CIDADE E ESTADO DE RESIDENCIA CADASTRADOS
      $ometadados = $wpdb->get_results("SELECT meta_key, meta_value FROM $table_usermeta WHERE user_id = '$usr->id' AND (meta_key = 'banda_cidade' OR meta_key = 'banda_estado' OR meta_key = 'banda_pais')");
      
      foreach ($ometadados as $ometadado){
        $key = $ometadado->meta_key;
        $$key = $ometadado->meta_value;
      }
      
      
      if(!isset($banda_pais)){
        $num_banda_paises = $num_banda_paises ? $num_banda_paises+1 : 1;
        $wpdb->query("INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) VALUES ('$usr->id','banda_pais','BR')");
      }
      
      if(!isset($banda_estado)){
        $num_banda_estado = $num_banda_estado ? $num_banda_estado+1 : 1;
        $wpdb->query("INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) VALUES ('$usr->id','banda_estado','')");
      }
      
      if(!isset($banda_cidade)){
        $num_banda_cidade = $num_banda_cidade ? $num_banda_cidade+1 : 1;
        $wpdb->query("INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) VALUES ('$usr->id','banda_cidade','')");
      }
      $output .= " - residência: '$banda_cidade' - '$banda_estado' (";

      // PROCURA O MUNICIPIO DE RESIDENCIA A PARTIR DOS DADOS CADASTRADOS
      $residencia = TNB_getCidade($banda_estado, $banda_cidade);

      // SE O MUNICIPIO DE RESIDENCIA FOI ENCONTRADO...
      if($residencia){
        $residenciasEncontradas++;
        $output .= "encontrado : $residencia->id";

        // ...PADRONIZA O NOME DA CIDADE
        $queryUpdateResidenciaCidade = "
          UPDATE
            $table_usermeta
          SET
            meta_value = '$residencia->nome'
          WHERE
            meta_key = 'banda_cidade' AND
            user_id = '$usr->id'";

        // E DEFINE O PAÍS COMO 'BR'
        $queryUpdateResidenciaCidade = "
          UPDATE
            $table_usermeta
          SET
            meta_value = 'BR'
          WHERE
            meta_key = 'banda_pais' AND
            user_id = '$usr->id'";
        
        
        $wpdb->query($queryUpdateResidenciaCidade);
      }else{
        $usr->ESTADO = $residencia_estado;
        $usr->CIDADE = $residencia_cidade;
        
        $nao_encontrados[$capability][$usr->id] = $usr;
        $output .= "<span style='color:red'>não encontrado</span>";
        $residenciasNaoEncontradas++;
      }
      $output .=')';
  }

  echo $output;
}


echo "
<pre>
  <em>
  metadado origen_pais criados: $num_origem_paises 
  metadado origen_estado criados: $num_origem_estado 
  metadado origen_cidade criados: $num_origem_cidade
  
  metadado banda_pais criados: $num_banda_paises 
  metadado banda_estado criados: $num_banda_estado 
  metadado banda_cidade criados: $num_banda_cidade
  </em> 

  origens encontradas: $origensEncontradas
  residências encontradas: $residenciasEncontradas

<strong>
  origens não encontradas: $origensNaoEncontradas
  residências não encontradas: $residenciasNaoEncontradas
</strong>


</pre>";

foreach($nao_encontrados as $capability => $users){
  echo "
  <div style='font-weight:bold;'>$capability</div>
  	<table cellpadding=4 border=1>
    	<tr>
    		<td>id</td>
    		<td>login</td>
    		<td>nome</td>
    		<td>e-mail</td>
    		
    		<td><strong>UF</strong></td>
    		<td><strong>cidade</strong></td>
    	</tr>
  ";
  
  foreach($users as $user)
    echo "
  		<tr>
  			<td>$user->id</td>
  			<td>$user->user_login</td>
  			<td>$user->display_name</td>
  			<td>$user->user_email</td>
  			<td style='background-color:#eee;'>$user->ESTADO</td>
  			<td style='background-color:#eee;'>$user->CIDADE</td>
 		</tr>";
  
  echo '</table>';
}

?>


