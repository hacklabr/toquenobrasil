<?php
global $wpdb;

$sql_de = '';
$sql_ate = '';


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

$query = "
SELECT
	* 
FROM
	{$wpdb->prefix}tnb_users_stats
WHERE 1
	$sql_de $sql_ate $sql_capability";
	

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

foreach ($users as $user){
	
	$dia = substr($user->data, 0, 10);
	$mes = substr($user->data, 0, 7);
	
	if($user->reg_type == 'insert'){
		$registros_por_dia[$dia] = isset($registros_por_dia[$dia]) ? $registros_por_dia[$dia] + 1 : 1;
		$registros_por_mes[$mes] = isset($registros_por_mes[$mes]) ? $registros_por_mes[$mes] + 1 : 1;
		
	}elseif($user->reg_type == 'delete'){
		$registros_por_dia[$dia] = isset($registros_por_dia[$dia]) ? $registros_por_dia[$dia] - 1 : -1;
		$registros_por_mes[$mes] = isset($registros_por_mes[$mes]) ? $registros_por_mes[$mes] - 1 : -1;
		
	}else{
		die('error');
	}
	if($user->capability == 'artista'){
		if($user->reg_type == 'insert'){
			$artistas_por_dia[$dia] = isset($artistas_por_dia[$dia]) ? $artistas_por_dia[$dia] + 1 : 1;
			$artistas_por_mes[$mes] = isset($artistas_por_mes[$mes]) ? $artistas_por_mes[$mes] + 1 : 1;
		}elseif($user->reg_type == 'delete'){
			$artistas_por_dia[$dia] = isset($artistas_por_dia[$dia]) ? $artistas_por_dia[$dia] - 1 : -1;
			$artistas_por_mes[$mes] = isset($artistas_por_mes[$mes]) ? $artistas_por_mes[$mes] - 1 : -1;
		}
		$num_artistas++;
	}
	if($user->capability == 'produtor'){
		if($user->reg_type == 'insert'){
			$produtores_por_dia[$dia] = isset($produtores_por_dia[$dia]) ? $produtores_por_dia[$dia] + 1 : 1;
			$produtores_por_mes[$mes] = isset($produtores_por_mes[$mes]) ? $produtores_por_mes[$mes] + 1 : 1;
		}elseif($user->reg_type == 'delete'){
			$produtores_por_dia[$dia] = isset($produtores_por_dia[$dia]) ? $produtores_por_dia[$dia] - 1 : -1;
			$produtores_por_mes[$mes] = isset($produtores_por_mes[$mes]) ? $produtores_por_mes[$mes] - 1 : -1;
		}
		$num_produtores++;
	}
}
?>
<p>
<form method="get" >
	<input type='hidden' name='page' value='<?php echo $_GET['page']?>'>
	<select name='user_type' onchange="this.form.submit();">
		<option value='' <?php if(!isset($_GET['user_type']) || $_GET['user_type'] == '') echo 'selected="selected"';?>>todos</option>
		<option value='artista' <?php if(isset($_GET['user_type']) && $_GET['user_type'] == 'artista') echo 'selected="selected"';?>>artistas</option>
		<option value='produtor' <?php if(isset($_GET['user_type']) && $_GET['user_type'] == 'produtor') echo 'selected="selected"';?>>produtores</option>
	</select>
	de: <input id="registros_de" name='registros_de' type="text"  value="<?php echo $_GET['registros_de']; ?>" class="date bottom"/> à 
    <input id="registros_ate" name='registros_ate' type="text" value="<?php echo $_GET['registros_ate']; ?>" class="date bottom"/>
    <input type="submit" value="<?php _e('pesquisar','tnb')?>">
</form>
</p>

<h3><?php echo $num_usuarios?> usuários encontrados</h3>
<div id='registros-total-pie' class='graph' style='width:350px; height:150px;'></div>
<h4>número de registros por dia</h4>
<div id='registros-por-dia' class='graph' style='width:95%; height:250px;'></div>

<h4>acumulado no período</h4>
<div id='acumulado-por-dia' class='graph' style='width:95%; height:250px;'></div>



<div id='lista-de-usuarios'>
	<table class='widefat'>
		<tr>
			<td></td>
		</tr>
	</table>
</div>



<script type="text/javascript">

jQuery('#registros_de').datepicker();
jQuery('#registros_ate').datepicker();


var data_pie = [{ label: "Artistas: <?php echo $num_artistas?>",  data: <?php echo $num_artistas?>},
    		{ label: "Produtores: <?php echo $num_produtores?>",  data: <?php echo $num_produtores?>}];

jQuery.plot(jQuery("#registros-total-pie"), data_pie,
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
ksort($registros_por_dia);


$v = '';
foreach ($registros_por_dia as $dia => $num){	
	$dia = strtotime($dia);	
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

// gráfico de total acumulado no dia

$acumulado_artistas = 0;
$acumulado_produtores = 0;
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

?>

var total_data = [<?php echo $data_total_por_dia?>];
var artistas_data = [<?php echo $data_artistas_por_dia?>];
var produtores_data = [<?php echo $data_produtores_por_dia?>];

var acumulado_artistas = [<?php echo $data_acumulado_artistas?>];
var acumulado_produtores = [<?php echo $data_acumulado_produtores?>];


var data_dia = [	{data: total_data, label: 'total', color:2},
					{data: artistas_data, label: 'artistas', color:0},
                	{data: produtores_data, label:'produtores', color:1}
                ];
                
jQuery.plot(jQuery("#registros-por-dia"), data_dia, { 
	    xaxes: [ { mode: 'time' , timeformat: "%d/%m/%y" } ],
	    yaxes: [ { min: 0 }],
	    legend: { position: 'nw' }
    }
);


var data_acumulado = [
            	{data: acumulado_artistas, label: 'artistas'},
            	{data: acumulado_produtores, label:'produtores'}
            ];
            
jQuery.plot(jQuery("#acumulado-por-dia"), data_acumulado, { 
    xaxes: [ { mode: 'time' , timeformat: "%d/%m/%y" } ],
    yaxes: [ { min: 0 }],
    legend: { position: 'nw' }

}
);

</script>