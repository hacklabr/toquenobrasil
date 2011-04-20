<?php
global $wpdb;
$eventos = array();

$query = "
SELECT 
	$wpdb->posts.*,
	$wpdb->postmeta.meta_value as inscricao_fim,
	$wpdb->users.display_name as produtor
FROM
    $wpdb->posts,
    $wpdb->postmeta,
    $wpdb->users
WHERE
	$wpdb->posts.post_type = 'eventos' AND
	$wpdb->postmeta.post_id = $wpdb->posts.ID AND
	$wpdb->postmeta.meta_key = 'evento_inscricao_fim' AND
	$wpdb->users.ID = $wpdb->posts.post_author

	
ORDER BY inscricao_fim DESC
";
$eventos = $wpdb->get_results($query);

?>

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
    <input type="submit" value="<?php _e('pesquisar','tnb')?>">
</form>

<br />
<table class='widefat'>
    <thead>
        <tr>
            <th><?php _e('evento', 'tnb')?></th>
            <th><?php _e('produtor', 'tnb')?></th>
            <th><?php _e('inscrição', 'tnb')?></th>
            <th><?php _e('data', 'tnb')?></th>
            <th><?php _e('vagas', 'tnb')?></th>
            <th><?php _e('inscritos', 'tnb')?></th>
            <th><?php _e('selecionados', 'tnb')?></th>
        </tr>
    </thead>
    
    <tbody>
        <?php 
            foreach($eventos as $evento): 
                $evento_data = get_oportunidades_data($evento->ID);
                $inscritos = $wpdb->get_var("SELECT count(meta_id) FROM $wpdb->postmeta WHERE meta_key = 'inscrito' AND post_id = $evento->ID");
                $selecionados = $wpdb->get_var("SELECT count(meta_id) FROM $wpdb->postmeta WHERE meta_key = 'selecionado' AND post_id = $evento->ID")
        ?>
        <tr>
            <td><a href='<?php echo get_permalink($evento->ID);?>'><?php echo $evento->post_title; ?></a></td>
            <td><a href='<?php echo get_author_posts_url($evento->post_author);?>' ><?php echo $evento->produtor; ?></a></td>
            <td><?php echo $evento_data['br_insc_inicio']; ?> até  <?php echo $evento_data['inscricao_fim']; ?></td>
            <td><?php echo $evento_data['br_inicio']; ?> até  <?php echo $evento_data['br_fim']; ?></td>
            <td align="center"><?php echo $evento_data['vagas']; ?></td>
            <td align="center"><?php echo $inscritos;?></td>
            <td align="center"><?php echo $selecionados; ?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>