<?php
global $wpdb;
$status = isset($_GET['status']) ? $_GET['status'] : 'publish';

$sr = get_oportunidades_search_results($status);
$IDS = implode(',', $sr);
$eventos = array();

$sql_somente_inscricao_cobrada = isset($_GET['somente_inscricao_cobrada']) ? "$wpdb->posts.ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'evento_inscricao_cobrada' AND meta_value = '1') AND" : "";

$query = "
SELECT 
	$wpdb->posts.*,
	$wpdb->postmeta.meta_value as inscricao_fim,
	$wpdb->users.display_name as produtor,
	$wpdb->users.user_email as email
FROM
    $wpdb->posts,
    $wpdb->postmeta,
    $wpdb->users
WHERE
	$wpdb->posts.ID IN ($IDS) AND
	$sql_somente_inscricao_cobrada
	$wpdb->postmeta.post_id = $wpdb->posts.ID AND
	$wpdb->postmeta.meta_key = 'evento_inscricao_fim' AND
	$wpdb->users.ID = $wpdb->posts.post_author
	

ORDER BY inscricao_fim DESC
";
$eventos = $wpdb->get_results($query);

?>
<style>
.zero-selecionados{
    background-color:#fbb;
}
.zero-selecionados .insc-inicio{
    font-weight:bold;
}
.zero-selecionados .selecionados{
    font-weight:bold;
}

</style>
<form method="get">
    <input type='hidden' name='page' value='<?php echo $_GET['page']?>'>
    <label>
        Nome:
        <input type="text" id="oportunidade_nome" name='oportunidade_nome' value="<?php echo htmlentities(utf8_decode(stripcslashes($_GET['oportunidade_nome']))); ?>" />  
    </label>
    
    <label>
        Local:
        <input type="text" id="oportunidade_local" name='oportunidade_local' name='oportunidade_local' value="<?php echo htmlentities(utf8_decode(stripcslashes($_GET['oportunidade_local']))); ?>"  />  
    </label>
    
    <label>
    	Status:
    	<select name='status'>
    		<option value='publish' <?php if(!isset($_GET['status']) || $_GET['status'] == 'publish') echo 'selected="selected"'; ?>>publicado</option>
    		<option value='draft' <?php if(isset($_GET['status']) && $_GET['status'] == 'draft') echo 'selected="selected"'; ?>>rascunho</option>
    		<option value='pay_pending_review' <?php if(isset($_GET['status']) && $_GET['status'] == 'pay_pending_review') echo 'selected="selected"'; ?>>contratos não revisados</option>
    		<option value='pay_pending_ok' <?php if(isset($_GET['status']) && $_GET['status'] == 'pay_pending_ok') echo 'selected="selected"'; ?>>contratos pendentes</option>
    	</select>
    </label>
    
    <label><input type='checkbox' name='somente_inscricao_cobrada' value=1 <?php if(isset($_GET['somente_inscricao_cobrada'])) echo 'checked="checked"';?>/> somente com inscrições cobradas</label>
    <br/>
    Acontece de: <input id="acontece_de" name='acontece_de' type="text"  value="<?php echo $_GET['acontece_de']; ?>" class="date bottom"/> à 
    <input id="acontece_ate" name='acontece_ate' type="text" value="<?php echo $_GET['acontece_ate']; ?>" class="date bottom"/>
    
                    
    <input type="submit" value="<?php _e('pesquisar','tnb')?>">
</form>

<script type="text/javascript">
	jQuery('#acontece_de').datepicker();
	jQuery('#acontece_ate').datepicker();
</script>

<br />
<table class='widefat'>
    <thead>
        <tr>
            <th><?php _e('oportunidade', 'tnb')?></th>
            <th><?php _e('responsável', 'tnb')?></th>
            <th><?php _e('insc. inicio', 'tnb')?></th>
            <th><?php _e('insc. fim', 'tnb')?></th>
            <th><?php _e('inicio', 'tnb')?></th>
            <th><?php _e('fim', 'tnb')?></th>
            <th style="text-align: center"><?php _e('vagas', 'tnb')?></th>
            <th><?php _e('inscritos', 'tnb')?></th>
            <th><?php _e('selecionados', 'tnb')?></th>
        </tr>
    </thead>
    
    <tbody>
        <?php 
            $total_inscritos = 0;
            $total_selecionados = 0;
            
            
            foreach($eventos as $evento): 
                $evento_data = get_oportunidades_data($evento->ID);
                $inscritos = $wpdb->get_var("SELECT count(meta_id) FROM $wpdb->postmeta WHERE meta_key = 'inscrito' AND post_id = $evento->ID");
                $selecionados = $wpdb->get_var("SELECT count(meta_id) FROM $wpdb->postmeta WHERE meta_key = 'selecionado' AND post_id = $evento->ID");
                
                $total_selecionados += $selecionados;
                $total_inscritos += $inscritos;
                $classes = '';
                // inscrições encerradas com zero inscritos
                if(strtotime($evento_data['inscricao_fim']) < time() && $selecionados == 0)
                    $classes = 'zero-selecionados';
                    
                
        ?>
        <tr style="<?php echo $style?>" class='<?php echo $classes?>'>
            <td class='evento first'><a href='<?php echo get_permalink($evento->ID);?>'><?php echo $evento->post_title; ?></a></td>
            <td class='produtor'><a href='<?php echo get_author_posts_url($evento->post_author);?>' ><?php echo $evento->produtor; ?></a><br /><?php echo $evento->email; ?></td>
            <td class='insc-inicio'><?php echo $evento_data['br_insc_inicio']; ?></td>
            <td class='insc-fim'><?php echo $evento_data['br_insc_fim']; ?></td>
            <td class='evt-inicio'><?php echo $evento_data['br_inicio']; ?></td>
            <td class='evt-fim'><?php echo $evento_data['br_fim']; ?></td>
            <td class='vagas' align="center"><?php echo $evento_data['vagas']; ?></td>
            <td class='inscritos' align="center"><?php echo $inscritos;?></td>
            <td class='selecionados last' align="center"><?php echo $selecionados; ?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
    <tfoot style="font-weight:bold">
        <tr>
            <td colspan="7"><?php echo count($eventos); ?> oportunidades</td>
            <td align="center"><?php echo $total_inscritos; ?></td>
            <td align="center"><?php echo $total_selecionados; ?></td>
        </tr>
    </tfoot>
</table>