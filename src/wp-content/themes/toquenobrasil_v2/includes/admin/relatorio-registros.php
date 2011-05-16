<?php
global $wpdb;

$sql_de = '';
$sql_ate = '';

$perpage = 50;

if(isset($_GET['registros_de']) && trim($_GET['registros_de'])){
	list($dia1, $mes1, $ano1) = explode('/', $_GET['registros_de']);
	$mes1 = $mes1 > 9 ? $mes1 : "0".intval($mes1);
	$dia1 = $dia1 > 9 ? $dia1 : "0".intval($dia1);
	$registros_de = "$ano1-$mes1-$dia1";

	$sql_de = "AND data >= '$registros_de' ";
}

if(isset($_GET['registros_ate']) && trim($_GET['registros_ate'])){
	list($dia1, $mes1, $ano1) = explode('/', $_GET['registros_ate']);
	$mes1 = $mes1 > 9 ? $mes1 : "0".intval($mes1);
	$dia1 = $dia1 > 9 ? $dia1 : "0".intval($dia1);
	$registros_ate = "$ano1-$mes1-$dia1";
	
	$sql_ate = "AND data <= '$registros_ate' ";
}


$sql_capability = isset($_GET['user_type']) && $_GET['user_type'] ?	
				  "AND capability = '".$_GET['user_type']."'" : ""; 

$sql_pais = '';
$sql_estado = '';
$sql_cidade = '';

if($_GET['pais'])
	$sql_pais = " AND pais = '$_GET[pais]'";
	 
if($_GET['estado'])
	$sql_estado = " AND estado = '$_GET[estado]'";
	
if($_GET['cidade'])
	$sql_estado = " AND cidade = '$_GET[cidade]'";
	
	
$query = "
SELECT
	* 
FROM
	{$wpdb->prefix}tnb_users_stats
WHERE 1
	$sql_de $sql_ate $sql_capability $sql_pais $sql_estado $sql_cidade";
	

$users = $wpdb->get_results($query);

$num_usuarios = count($users);
$num_artistas = 0;
$num_produtores = 0;

$registros_por_dia = array();
$registros_por_mes = array();

$artistas_por_dia = array();
$artistas_por_mes = array();

$produtores_por_dia = array();
$produtores_por_mes = array();

$num_dias_media = 7;
$ultimos_dias = array();
$media_ultimos_dias = array();

$num_novos_artistas = 0;
$num_artistas_deletados = 0;

$num_novos_produtores = 0;
$num_produtores_deletados = 0;


$users_ids = array();
foreach ($users as $user){
	
	
	$dia = substr($user->data, 0, 10);
	$mes = substr($user->data, 0, 7);
	
	for($i = 1; $i <= $num_dias_media; $i++){
		// próximo dia
		if(!isset($registros_por_dia[date('Y-m-d', strtotime($dia) + 86400 * $i)]) && strtotime($dia) + 86400 * $i < strtotime(date('Y-m-d')))
			$registros_por_dia[date('Y-m-d', strtotime($dia) + 86400 * $i)] = 0;
			
		// dia anterior
		if(!isset($registros_por_dia[date('Y-m-d', strtotime($dia) - 86400 * $i)]))
			$registros_por_dia[date('Y-m-d', strtotime($dia) - 86400 * $i)] = 0;
	}
			
	if($user->reg_type == 'insert'){
		$users_ids[] = $user->user_id;
		$registros_por_dia[$dia] = isset($registros_por_dia[$dia]) ? $registros_por_dia[$dia] + 1 : 1;
		
		$registros_por_mes[$mes] = isset($registros_por_mes[$mes]) ? $registros_por_mes[$mes] + 1 : 1;
		
	}elseif($user->reg_type == 'delete'){
		
		$registros_por_dia[$dia] = isset($registros_por_dia[$dia]) ? $registros_por_dia[$dia] - 1 : -1;
		$registros_por_mes[$mes] = isset($registros_por_mes[$mes]) ? $registros_por_mes[$mes] - 1 : -1;
		
	}else{
		die('error');
	}
	
	if($user->capability == 'artista'){
		for($i = 1; $i <= $num_dias_media; $i++){
			// próximo dia
			if(!isset($artistas_por_dia[date('Y-m-d', strtotime($dia) + 86400 * $i)]) && strtotime($dia) + 86400 * $i < strtotime(date('Y-m-d')))
				$artistas_por_dia[date('Y-m-d', strtotime($dia) + 86400 * $i)] = 0;
				
			// dia anterior
			if(!isset($artistas_por_dia[date('Y-m-d', strtotime($dia) - 86400 * $i)]))
				$artistas_por_dia[date('Y-m-d', strtotime($dia) - 86400 * $i)] = 0;
		}
		if($user->reg_type == 'insert'){
			$num_novos_artistas++;
			$artistas_por_dia[$dia] = isset($artistas_por_dia[$dia]) ? $artistas_por_dia[$dia] + 1 : 1;
			$artistas_por_mes[$mes] = isset($artistas_por_mes[$mes]) ? $artistas_por_mes[$mes] + 1 : 1;
		}elseif($user->reg_type == 'delete'){
			$num_artistas_deletados++;
			$artistas_por_dia[$dia] = isset($artistas_por_dia[$dia]) ? $artistas_por_dia[$dia] - 1 : -1;
			$artistas_por_mes[$mes] = isset($artistas_por_mes[$mes]) ? $artistas_por_mes[$mes] - 1 : -1;
		}
		
	}
	if($user->capability == 'produtor'){
		for($i = 1; $i <= $num_dias_media; $i++){
			// próximo dia
			if(!isset($produtores_por_dia[date('Y-m-d', strtotime($dia) + 86400 * $i)]) && strtotime($dia) + 86400 * $i < strtotime(date('Y-m-d')))
				$produtores_por_dia[date('Y-m-d', strtotime($dia) + 86400 * $i)] = 0;
				
			// dia anterior
			if(!isset($produtores_por_dia[date('Y-m-d', strtotime($dia) - 86400 * $i)]))
				$produtores_por_dia[date('Y-m-d', strtotime($dia) - 86400 * $i)] = 0;
		}			
		if($user->reg_type == 'insert'){
			$num_novos_produtores++;
			$produtores_por_dia[$dia] = isset($produtores_por_dia[$dia]) ? $produtores_por_dia[$dia] + 1 : 1;
			$produtores_por_mes[$mes] = isset($produtores_por_mes[$mes]) ? $produtores_por_mes[$mes] + 1 : 1;
			
		}elseif($user->reg_type == 'delete'){
			$num_produtores_deletados++;
			$produtores_por_dia[$dia] = isset($produtores_por_dia[$dia]) ? $produtores_por_dia[$dia] - 1 : -1;
			$produtores_por_mes[$mes] = isset($produtores_por_mes[$mes]) ? $produtores_por_mes[$mes] - 1 : -1;
		}
		
	}
}
ksort($registros_por_dia);

// obtendo a média dos ultimos dias
$i = 0;
$tot = 0;
foreach ($registros_por_dia as $num)
	if($i < $num_dias_media){
		$tot += $num;
		$i++;
	}else{
		break;
	}

$med = $tot /  $num_dias_media;

for($i=1; $i <= $num_dias_media; $i++)
	$ultimos_dias[$i] = $med;


//_pr($registros_por_dia);
foreach ($registros_por_dia as $dia => $num){

			
	$media_ultimos_dias[$dia] = 0;
	// obtendo média dos ultimos dias
	for($i=1; $i <= $num_dias_media; $i++){
		
		if($i == $num_dias_media)
			$ultimos_dias[$i] = $num;
		else
			$ultimos_dias[$i] = $ultimos_dias[$i+1];
			
		$media_ultimos_dias[$dia] += $ultimos_dias[$i]; 		
	}
	$media_ultimos_dias[$dia] = $media_ultimos_dias[$dia] / $num_dias_media;
	
	
}


$paises = get_paises();
$estados = get_estados();
?>

<form method="get" >
	<input type='hidden' name='page' value='<?php echo $_GET['page']?>'>
	
	<table>
		<tr>
			<td>
				País<br />
				<select name='pais'>
					<option value="" <?php if(!isset($_GET['pais']) || $_GET['pais'] == '') echo 'selected="selected"';?>>TODOS</option>
					<?php foreach($paises as $sigla=>$pais):?>
						<option value='<?php echo $sigla?>' <?php if(isset($_GET['pais']) && $_GET['pais'] == $sigla) echo 'selected="selected"';?>><?php echo $pais?></option>
					<?php endforeach;?>
				</select>
			</td>
			
			<td>
				Estado<br />
				<select name='estado'>
					<option value="" <?php if(!isset($_GET['estado']) || $_GET['estado'] == '') echo 'selected="selected"';?>>TODOS</option>
					<?php foreach($estados as $uf=>$estado): if($uf): ?>
						<option value='<?php echo $uf?>' <?php if(isset($_GET['estado']) && $_GET['estado'] == $uf) echo 'selected="selected"';?>><?php echo $estado?></option>
					<?php endif; endforeach;?>
				</select>
			</td>
			
			<td>
				Cidade <br />
				<input name='cidade' value='<?php echo $_GET['cidade'];?>' />
			</td>
			
			<td>
				Tipo de usuário<br />
				<select name='user_type' onchange="this.form.submit();">
					<option value='' <?php if(!isset($_GET['user_type']) || $_GET['user_type'] == '') echo 'selected="selected"';?>>TODOS</option>
					<option value='artista' <?php if(isset($_GET['user_type']) && $_GET['user_type'] == 'artista') echo 'selected="selected"';?>>artistas</option>
					<option value='produtor' <?php if(isset($_GET['user_type']) && $_GET['user_type'] == 'produtor') echo 'selected="selected"';?>>produtores</option>
				</select>
							
			</td>
		</tr>
	</table>
	<br/>
	
	
	de: <input id="registros_de" name='registros_de' type="text"  value="<?php echo $_GET['registros_de']; ?>" class="date bottom"/> à 
    <input id="registros_ate" name='registros_ate' type="text" value="<?php echo $_GET['registros_ate']; ?>" class="date bottom"/>
    <input type="submit" value="<?php _e('pesquisar','tnb')?>">
</form>



<table width="100%">
	<tr>
		<td>
			<h3><?php echo $num_novos_artistas+$num_novos_produtores?> novos usuários</h3>
			<div id='usuarios-novos-pie' class='graph' style='width:350px; height:150px;'></div>
		</td>
		<td>
			<h3><?php echo $num_artistas_deletados+$num_produtores_deletados?> usuários deletados</h3>
			<div id='usuarios-deletados-pie' class='graph' style='width:350px; height:150px;'>
		</div>
	</tr>
</table>

<h4>número de registros por dia</h4>
<div id='registros-por-dia' class='graph' style='width:95%; height:250px;'></div>

<h4>acumulado no período</h4>
<div id='acumulado-por-dia' class='graph' style='width:95%; height:250px;'></div>

<hr />
<div>
<form method="post">
página <select name='_page' onchange="this.form.submit();"">
	<?php for($i=0; $i < count($users_ids) / $perpage; $i++):?>
		<option value='<?php echo $i?>' <?php if($_POST['_page'] == $i) echo 'selected="selected"'?>><?php echo $i+1?></option>
	<?php endfor;?>
</select>
</form>
</div>
<?php 

$_users = array();

$_page = $_POST['_page'] ? $_POST['_page'] : 0;

$uids = implode(',',array_slice($users_ids, $_page * $perpage, $perpage));

$q1 = "
	SELECT 
		ID,
		user_login,
		user_email,
		display_name
	FROM
		$wpdb->users
	WHERE
		ID IN ($uids)";
		
$q2 = "
	SELECT
		user_id,
		meta_key,
		meta_value
	FROM
		$wpdb->usermeta
	WHERE
		user_id IN ($uids) AND
		(
			meta_key = 'origem_cidade' OR	
			meta_key = 'origem_estado' OR
			meta_key = 'origem_pais' OR
			meta_key = 'banda_cidade' OR 
			meta_key = 'banda_estado' OR
			meta_key = 'banda_pais' OR
			meta_key = 'estilo_musical_livre' OR
			meta_key = 'estilo_musical' OR
			meta_key = 'telefone' OR
			meta_key = 'telefone_ddd' OR
			meta_key = 'responsavel' 
		)
	ORDER BY user_id";

$users_data = $wpdb->get_results($q1);
$users_metadata = $wpdb->get_results($q2);

foreach($users_data as $udata)
	$_users[$udata->ID] = $udata;  

foreach($users_metadata as $umeta){
	$key = $umeta->meta_key;
	
	unset($array);
	
	// se o meta dado já existe para o mesmo id de usuário, cria um array 
	if(isset($_users[$umeta->user_id]->$key)){
		if(is_array($_users[$umeta->user_id]->$key))
			$array = $_users[$umeta->user_id]->$key;
		else
			$array = array($_users[$umeta->user_id]->$key);
			
		$array[] = $umeta->meta_value;
		
		$_users[$umeta->user_id]->$key = $array;
		
	}else{
		$_users[$umeta->user_id]->$key = $umeta->meta_value;
	}
}

?>


<div id='lista-de-usuarios'>
<table class='widefat'>
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>login</th>
			<th>e-mail</th>
			<th>nome</th>
			<th>responsavel</th>
			<th>telefone</th>
			<th>país de origem</th>
			<th>estado</th>
			<th>cidade</th>
			<th>país de residência</th>
			<th>estado</th>
			<th>cidade</th>
			<th>estilos</th>
			<th>estilo livre</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		
		foreach($_users as $udata): 
			//$udata = get_userdata($uid);
			
			if(is_artista($udata->ID)){
				$capability = 'artista';
				$pais = $udata->banda_pais ? $paises[$udata->banda_pais] : '';
				if($udata->banda_estado)
					$estado = $udata->banda_pais == 'BR' ? $estados[$udata->banda_estado] :  $udata->banda_estado;
				else
					$estado = '';
				
				$cidade = $udata->banda_cidade;
			}else{
				$capability = 'produtor';
				$cidade = '';
				$estado = '';
				$pais = '';
			}
			
			$origem_pais = $udata->origem_pais ? $paises[$udata->origem_pais] : '';
			if($udata->origem_estado)
				$origem_estado = $udata->origem_pais == 'BR' ? $estados[$udata->origem_estado] :  $origem_udata->origem_estado;
			else
				$origem_estado = '';
			
			$origem_cidade = $udata->origem_cidade;
		
		?>
		<tr>
			<td><?php echo $capability;?></td>
			<td><a href='<?php echo get_author_posts_url($uid);?>' ><?php echo $udata->user_login; ?></a></td>
			<td><?php echo $udata->user_email; ?></td>
			<td><?php echo $udata->display_name; ?></td>
			
			<td><?php echo $udata->responsavel; ?></td>
			<td><?php echo $udata->telefone_ddd ? "($udata->telefone_ddd) $udata->telefone" : $udata->telefone; ?></td>
			
			<td><?php echo $origem_pais?></td>
			<td><?php echo $origem_estado?></td>
			<td><?php echo $origem_cidade?></td>
			
			<td><?php echo $pais?></td>
			<td><?php echo $estado?></td>
			<td><?php echo $cidade?></td>
			<td><?php echo is_array($udata->estilo_musical) ? implode(', ', $udata->estilo_musical) : $udata->estilo_musical?></td>
			<td><?php echo $udata->estilo_musical_livre?></td>
		</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>



<script type="text/javascript">

jQuery('#registros_de').datepicker();
jQuery('#registros_ate').datepicker();


var data_novos_pie = [	{ label: "Artistas: <?php echo $num_novos_artistas?>",  data: <?php echo $num_novos_artistas?>},
    					{ label: "Produtores: <?php echo $num_novos_produtores?>",  data: <?php echo $num_novos_produtores?>}
    				 ];
var data_deletados_pie = [	{ label: "Artistas: <?php echo $num_artistas_deletados?>",  data: <?php echo $num_artistas_deletados?>},
    						{ label: "Produtores: <?php echo $num_produtores_deletados?>",  data: <?php echo $num_produtores_deletados?>}
    					 ];

<?php if($num_novos_artistas || $num_novos_produtores):?>
jQuery.plot(jQuery("#usuarios-novos-pie"), data_novos_pie,
		{
		        series: {
		            pie: {
		                show: true,
		                radius: 1,
		                label: {
		                    show: true,
		                    radius: 3/4,
		                    formatter: function(label, series){
			                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+Math.round(series.percent)+'%</div>';
		                    },
		                    background: { opacity: 0.5 }
		                }
		            }
		        }
		});
<?php endif; ?>

<?php if($num_artistas_deletados || $num_produtores_deletados):?>

jQuery.plot(jQuery("#usuarios-deletados-pie"), data_deletados_pie,
		{
		        series: {
		            pie: {
		                show: true,
		                radius: 1,
		                label: {
		                    show: true,
		                    radius: 3/4,
		                    formatter: function(label, series){
			                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+Math.round(series.percent)+'%</div>';
		                    },
		                    background: { opacity: 0.5 }
		                }
		            }
		        }
		});
<?php endif; ?>
<?php 
// gráficos de inscrições por dia
foreach ($artistas_por_dia as $dia => $num)
	if(!isset($produtores_por_dia[$dia]))
		$produtores_por_dia[$dia] = 0;

foreach ($produtores_por_dia as $dia => $num)
	if(!isset($artistas_por_dia[$dia]))
		$artistas_por_dia[$dia] = 0;

ksort($artistas_por_dia);
ksort($produtores_por_dia);
ksort($media_ultimos_dias);

$menor_data = 0; 

$v = '';
foreach ($registros_por_dia as $dia => $num){
	
	$dia = strtotime($dia);
	
	if($menor_data == 0){
		$menor_data = $dia;
	}	
	$data_total_por_dia .= $v."[{$dia}000, $num]";
	$v = ',';
}

$v = '';
foreach ($artistas_por_dia as $dia => $num){
	$dia = strtotime($dia);	
	$data_artistas_por_dia .= $v."[{$dia}000, $num]";
	$v = ',';
}

$v = '';
foreach ($produtores_por_dia as $dia => $num){
	
	$dia = strtotime($dia);
	$data_produtores_por_dia .= $v."[{$dia}000, $num]";
	$v = ',';
} 

$v = '';
foreach ($media_ultimos_dias as $dia => $num){
	$dia = strtotime($dia);// - intval((86400 * $num_dias_media / 2));
	if($dia >= $menor_data){
		$data_media_por_dia .= $v."[{$dia}000, $num]";
		$v = ',';
	}
}


// gráfico de total acumulado no dia

$acumulado_artistas = 0;
$acumulado_produtores = 0;
$acumulado_total = 0;

$v = '';
foreach ($artistas_por_dia as $dia => $num){	
	$dia = strtotime($dia);	
	$acumulado_artistas += $num;
	$data_acumulado_artistas .= $v."[{$dia}000, $acumulado_artistas]";
	$v = ',';
}

$v = '';
foreach ($produtores_por_dia as $dia => $num){
	$dia = strtotime($dia);
	$acumulado_produtores += $num;
	$data_acumulado_produtores .= $v."[{$dia}000, $acumulado_produtores]";
	$v = ',';
} 

$v = '';
foreach ($registros_por_dia as $dia => $num){
	$dia = strtotime($dia);
	$acumulado_total += $num;
	$data_acumulado_total .= $v."[{$dia}000, $acumulado_total]";
	$v = ',';
} 

?>

var total_data = [<?php echo $data_total_por_dia?>];
var artistas_data = [<?php echo $data_artistas_por_dia?>];
var produtores_data = [<?php echo $data_produtores_por_dia?>];
var media_data = [<?php echo $data_media_por_dia?>];

var acumulado_artistas = [<?php echo $data_acumulado_artistas?>];
var acumulado_produtores = [<?php echo $data_acumulado_produtores?>];
var acumulado_total = [<?php echo $data_acumulado_total?>];


var data_dia = [	{data: media_data, label:'media dos <?php echo $num_dias_media?> dias anteriores', color:3},
					{data: total_data, label: 'total', color:2},
					{data: artistas_data, label: 'artistas', color:0},
                	{data: produtores_data, label:'produtores', color:1}
                	
                ];
                
jQuery.plot(jQuery("#registros-por-dia"), data_dia, { 
	    xaxes: [ { mode: 'time' , timeformat: "%d/%m/%y" } ],
	    
	    legend: { position: 'nw' }
    }
);


var data_acumulado = [
            	{data: acumulado_total, label: 'total', color:2},
            	{data: acumulado_artistas, label: 'artistas', color:0},
            	{data: acumulado_produtores, label:'produtores', color:1}
            ];
            
jQuery.plot(jQuery("#acumulado-por-dia"), data_acumulado, { 
    xaxes: [ { mode: 'time' , timeformat: "%d/%m/%y" } ],
    
    legend: { position: 'nw' }

}
);

</script>