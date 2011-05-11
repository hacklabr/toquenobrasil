<?php
global $wpdb;
$q = "
SELECT 
	$wpdb->posts.*, 
	$wpdb->users.display_name AS produtor 
FROM 
	$wpdb->posts, 
	$wpdb->users 
WHERE 
	$wpdb->users.ID = $wpdb->posts.post_author AND 
	$wpdb->posts.post_status = 'pay_pending_ok' 
	
ORDER BY $wpdb->posts.post_date DESC";
	
$eventos = $wpdb->get_results($q);

?>

<h3>Eventos com contrato pendente (produtor ainda não aceitou)</h3>
    <table class='widefat'>
        <thead>
            <tr>
                <th>evento</th>
                <th>produtor</th>
                <th>inscrição</th>
                <th>data</th>
                <th>vagas</th>
                <th style='width:50px; text-align:center'>valor</th>
                <th style='width:30px;'>&nbsp</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($eventos as $evento): $edata = get_oportunidades_data($evento->ID);?>
            <tr>
                <td><a href='<?php echo get_permalink($evento->ID);?>'><?php echo $evento->post_title?></a></td>
                <td><a href='<?php echo get_author_posts_url($evento->post_author)?>'><?php echo $evento->produtor?></a></td>
                <td><?php echo $edata['br_insc_inicio']; ?> à <?php echo $edata['br_insc_fim']; ?></td>
                <td><?php echo $edata['br_inicio']; ?> à <?php echo $edata['br_fim']; ?></td>
                <td><?php echo $edata['vagas']; ?></td>
                <td>R$ <?php echo $edata['inscricao_valor']; ?></td>
                <td style='text-align:right;'><a href="javascript:abreContratoEvento('<?php echo htmlentities($evento->post_title)?>',<?php echo $evento->ID?>)">visualizar</a></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    
<div id='contrato-preview' style='display:none; background-color:white; top:0px; left:0px; position:absolute; width:90%; height:90%; z-index:1000; margin:5%; padding:5px; border:1px solid gray; overflow:auto; '>
	<button id='contrato-preview-fechar' class='alignright'>fechar</button>
	<h4>contrato do evento <span id='contrato-evento-nome'></span></h4>
	<hr>
	<div id='contrato-preview-content' style="padding:15px;"></div>
</div>

    
<script type="text/javascript">
<!--
function abreContratoEvento(evento_nome, evento_id){
	jQuery('#contrato-evento-nome').html(evento_nome);
	
	jQuery.get('<?php echo bloginfo('stylesheet_directory')?>/includes/admin/pagamento-contrato-view.php?evento_id='+evento_id,
				function (data,status){
					jQuery('#contrato-preview').show();
					jQuery('#contrato-preview-content').html(data);
				});
}

jQuery('#contrato-preview-fechar').click(function() {
	jQuery('#contrato-preview').hide();
});
//-->
</script>
