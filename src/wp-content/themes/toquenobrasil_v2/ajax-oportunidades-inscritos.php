<?php
if(isset($_REQUEST['pid'])){
	require_once '../../../wp-load.php';
	$pid = $_REQUEST['pid'];
}else{
	$pid = $post->ID;
}

$users_num_rows = 2;
$users_num_cols = 4;
$users_limit = $users_num_rows * $users_num_cols;
	
$cpage = isset($_REQUEST['cpage']) ? $_REQUEST['cpage'] : 1;

$reqdata['buid'] = $_REQUEST['buid'] ? $_REQUEST['buid'] : uniqid('b');
$reqdata['usuarios'] = get_post_meta( $pid, 'inscrito') ;
$reqdata['oportunidade_item_id'] = $pid;


$oportunidade_item_id = $reqdata['oportunidade_item_id'];

if($reqdata['usuarios']){
    $list = new ControlledList($reqdata['usuarios'], $cpage, $users_limit);
	$ids = $list->getPageSlice();
	$id_list = implode(',', $ids);
	$artistas = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE ID IN ($id_list)");
	
}else{
	$artistas = array();
}
?>

<?php if(!isset($_REQUEST['cpage'])): ?>
<div id='<?php echo $reqdata['buid']; ?>' class="artists clearfix">
<?php endif;?>
<?php if($users_limit < count($reqdata['usuarios'])): //se o limite por página é menor que o total de usuários encontrados ?>
<div class='clear'></div>
 <div class="navigation clearfix">
	<?php if($cpage > 1): // se a página atual não é a primeira ?>
		<div class="left-navigation alignleft"><a href='javascript:void(0)' onclick="inscritos_previous(<?php echo $cpage;?>);"> anteriores </a></div>
	<?php endif;?> 
	<?php if($cpage * $users_limit <  count($reqdata['usuarios'])): // se a página atual vezes o limite é menor que o número de usuários encontrados ?>
		<div class="right-navigation alignright"><a href='javascript:void(0)' onclick="inscritos_next(<?php echo $cpage;?>);"> proximos </a></div>
	<?php endif; ?>
</div> 
<div class='carregando clearfix' style='display:none'>
	<?php _e('carregando...'); ?>
</div>
<?php endif;?>
<?php
$coln = 0; 
foreach($artistas as $user):
    $coln++;
     if($coln > $users_num_cols){
         echo "\n<hr />";
         $coln = 0;
     }
     $musica = tnb_get_artista_musica_principal($user->ID);
     
    ?>
    <div class="artist">
        <?php include 'users-list-item-artista.php'; ?>
        <?php if($musica): ?>
            <?php print_audio_player($musica->ID);?>
        <?php endif;?>
          
    
    </div>
    <!-- .artist -->
    
<?php endforeach;?>

<?php if(!isset($_REQUEST['cpage'])): ?>
</div>
<?php endif;?>

<?php if(!isset($_REQUEST['buffer'])):?>
	
	<script type="text/javascript">
	var inscritos_content = {};
	function inscritos_next(cpage){
		inscritos_content[cpage] = jQuery("#<?php echo $reqdata['buid']?>").html();
		cpage = cpage + 1;
		if(inscritos_content[cpage] && inscritos_content[cpage] != ''){
			 jQuery("#<?php echo $reqdata['buid']?>").html(inscritos_content[cpage]);
			 jQuery("#<?php echo $reqdata['buid']?> .carregando_i").hide();
		}else{
			jQuery("#<?php echo $reqdata['buid']?> .carregando").show();
			jQuery("#<?php echo $reqdata['buid']?> .navigation").hide();
			
			jQuery("#<?php echo $reqdata['buid']?>").load('<?php echo TNB_URL; ?>/ajax-oportunidades-inscritos.php?buffer=1&cpage='+cpage+'&pid=<?php echo $pid; ?>&buid=<?php echo $reqdata['buid']?>',function(){jQuery("#<?php echo $reqdata['buid']?> .navigation").show(); jQuery("#<?php echo $reqdata['buid']?> .carregando").hide();});
		}
	}

	function inscritos_previous(cpage){
		inscritos_content[cpage] = jQuery("#<?php echo $reqdata['buid']?>").html();
		cpage = cpage - 1;
		if(inscritos_content[cpage] && inscritos_content[cpage] != ''){
			 jQuery("#<?php echo $reqdata['buid']?>").html(inscritos_content[cpage]);
			 jQuery("#<?php echo $reqdata['buid']?> .carregando_i").hide();
		}else{
			jQuery("#<?php echo $reqdata['buid']?> .carregando").show();
			jQuery("#<?php echo $reqdata['buid']?>").load('<?php echo TNB_URL; ?>/ajax-oportunidades-inscritos.php?buffer=1&cpage='+cpage+'&pid=<?php echo $pid; ?>&buid=<?php echo $reqdata['buid']?>',function(){jQuery("#<?php echo $reqdata['buid']?> .navigation").show(); jQuery("#<?php echo $reqdata['buid']?> .carregando").hide();});
		}
	}
	</script>
<?php endif;?>
