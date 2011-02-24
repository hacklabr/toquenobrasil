<?php
require_once('../../../../wp-load.php');

//if (!current_user_can('manage_options'))
//  die ('sem permissao');
//else
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

// OR
//      '$cidade' LIKE CONCAT('%',municipio.nome,'%')

  $result = $wpdb->get_row($query);
  $tnb_cidades[$uf][$cidade] = $result;
  if(!$result){ echo "<pre>
    $query
    </pre>";
  
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


// SELECIONA OS USUÁRIOS QUE TENHAM AS CAPABILITIES artista E produtor E QUE TENHAM origem_pais = 'BR'
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

  // PEGA A CIDADE E ESTADO DE ORIGEM CADASTRADOS
  $origem_cidade = $wpdb->get_var("SELECT meta_value FROM $table_usermeta WHERE user_id = '$usr->id' AND meta_key = 'origem_cidade'");
  $origem_estado = $wpdb->get_var("SELECT meta_value FROM $table_usermeta WHERE user_id = '$usr->id' AND meta_key = 'origem_estado'");

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

      // PEGA A CIDADE E ESTADO DE RESIDENCIA CADASTRADOS
      $residencia_cidade = $wpdb->get_var("SELECT meta_value FROM $table_usermeta WHERE user_id = '$usr->id' AND meta_key = 'banda_cidade'");
      $residencia_estado = $wpdb->get_var("SELECT meta_value FROM $table_usermeta WHERE user_id = '$usr->id' AND meta_key = 'banda_estado'");

      $output .= " - residência: '$residencia_cidade' - '$residencia_estado' (";

// PROCURA O MUNICIPIO DE RESIDENCIA A PARTIR DOS DADOS CADASTRADOS
      $residencia = TNB_getCidade($residencia_estado, $residencia_cidade);

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

  //echo $output;
}


echo "
<pre>
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


