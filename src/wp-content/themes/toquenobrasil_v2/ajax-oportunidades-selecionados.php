<?php
if(isset($_REQUEST['pid'])){
	require_once '../../../wp-load.php';
	$pid = $_REQUEST['pid'];
}else{
	$pid = $post->ID;
}

$users_num_rows = 1;
$users_num_cols = 3;
$users_limit = $users_num_rows * $users_num_cols;

$cpage = isset($_REQUEST['cpage']) ? $_REQUEST['cpage'] : 1;

$reqdata['buid'] = $_REQUEST['buid'] ? $_REQUEST['buid'] : uniqid('b');
$reqdata['usuarios'] = get_post_meta( $pid, 'selecionado') ;
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
//echo count($reqdata['usuarios']).'-----'.$cpage.'------';
//echo $reqdata['buid'] ;
$coln = 1;
?>

<?php if(!isset($_REQUEST['cpage'])): ?>
<div id='<?php echo $reqdata['buid']; ?>' class="artists clearfix">
<?php endif;?>



<?php if($users_limit < count($reqdata['usuarios'])): //se o limite por página é menor que o total de usuários encontrados ?>
<div class='clear'></div>
 <div class="navigation clearfix">
	<?php if($cpage > 1): // se a página atual não é a primeira ?>
		<div class="left-navigation alignleft"><a href='javascript:void()' onclick="selecionados_previous(<?php echo $cpage;?>)"> anteriores </a></div>
	<?php endif;?> 
	
	<?php if($cpage * $users_limit <  count($reqdata['usuarios'])): // se a página atual vezes o limite é menor que o número de usuários encontrados ?>
		<div class="right-navigation alignright"><a href='javascript:void()' onclick="selecionados_next(<?php echo $cpage;?>)"> proximos </a></div>
	<?php endif; ?>
</div> 
<div class='carregando clearfix' style='display:none'>
	<?php _e('carregando...'); ?>
</div>
<?php endif;?>


<?php 
foreach($artistas as $user):
    $musica = tnb_get_artista_musica_principal($user->ID);
    ?>
    <div class="artist">
        <?php include 'users-list-item-artista.php'; ?>
        <?php if($musica): ?>
            <?php print_audio_player($musica->ID);?>
        <?php endif;?>
        
        <?php
          global $authordata, $current_user;
          if(current_user_can('select_other_artists') || current_user_can('select_artists', $oportunidade_item_id) ):
            if(in_postmeta(get_post_meta($oportunidade_item_id, 'inscrito'), $user->ID)):?>
                    
              <form method="post" id='form_join_event_<?php echo $user->ID; ?>'>
                <?php wp_nonce_field('select_band'); ?>
                <input type="hidden" name="action" value="select_band"/>
                <input type="hidden" name="banda_id" value='<?php echo $user->ID; ?>' />
                <input type="hidden" name="evento_id" value='<?php echo $oportunidade_item_id; ?>' />
              </form>
                        
              <div class="select-artist">
                <a class="button" onclick="jQuery('#form_join_event_<?php echo $user->ID; ?>').submit();"><?php _e('Selecionar!','tnb'); ?></a>
              </div>

            <?php elseif(in_postmeta(get_post_meta($oportunidade_item_id, 'selecionado'), $user->ID)):?>
            
              <form method="post" id='form_join_event_<?php echo $user->ID; ?>'>
                <?php wp_nonce_field('unselect_band'); ?>
                <input type="hidden" name="action" value="unselect_band"/>
                <input type="hidden" name="banda_id" value='<?php echo $user->ID; ?>' />
                <input type="hidden" name="evento_id" value='<?php echo $oportunidade_item_id; ?>' />
              </form>
              
              <div class="deselect-artist">
                <a class="button" onclick="jQuery('#form_join_event_<?php echo $user->ID; ?>').submit();"><?php _e('Deselecionar','tnb'); ?>.</a>
              </div>
            <?php endif;?>
        <?php endif;?>
        
    </div>
    <!-- .artist -->
<?php endforeach;?>

<?php if(!isset($_REQUEST['cpage'])): ?>
</div>
<?php endif;?>

<?php if(!isset($_REQUEST['buffer'])):?>
	
	<script type="text/javascript">
	var selecionados_content = {};
	function selecionados_next(cpage){
		selecionados_content[cpage] = jQuery("#<?php echo $reqdata['buid']?>").html();
		cpage = cpage + 1;
		if(selecionados_content[cpage] && selecionados_content[cpage] != ''){
			 jQuery("#<?php echo $reqdata['buid']?>").html(selecionados_content[cpage]);
			 jQuery("#<?php echo $reqdata['buid']?> .carregando").hide();
		}else{
			jQuery("#<?php echo $reqdata['buid']?> .carregando").show();
			jQuery("#<?php echo $reqdata['buid']?> .navigation").hide();
			
			jQuery("#<?php echo $reqdata['buid']?>").load('<?php echo TNB_URL; ?>/ajax-oportunidades-selecionados.php?buffer=1&cpage='+cpage+'&pid=<?php echo $pid; ?>&buid=<?php echo $reqdata['buid']?>',function(){jQuery("#<?php echo $reqdata['buid']?> .navigation").show(); jQuery("#<?php echo $reqdata['buid']?> .carregando").hide();});
		}
	}

	function selecionados_previous(cpage){
		selecionados_content[cpage] = jQuery("#<?php echo $reqdata['buid']?>").html();
		cpage = cpage - 1;
		if(selecionados_content[cpage] && selecionados_content[cpage] != ''){
			 jQuery("#<?php echo $reqdata['buid']?>").html(selecionados_content[cpage]);
			 jQuery("#<?php echo $reqdata['buid']?> .carregando").hide();
		}else{
			jQuery("#<?php echo $reqdata['buid']?> .carregando").show();
			jQuery("#<?php echo $reqdata['buid']?>").load('<?php echo TNB_URL; ?>/ajax-oportunidades-selecionados.php?buffer=1&cpage='+cpage+'&pid=<?php echo $pid; ?>&buid=<?php echo $reqdata['buid']?>',function(){jQuery("#<?php echo $reqdata['buid']?> .navigation").show(); jQuery("#<?php echo $reqdata['buid']?> .carregando").hide();});
		}
	}
	</script>
<?php endif;?>
