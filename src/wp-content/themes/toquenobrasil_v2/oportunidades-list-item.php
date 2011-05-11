<?php
	global $evento_list_item_id;
    if(!$evento_list_item_id) {
        $evento_list_item_id = get_the_ID();
    }

    $data = get_oportunidades_data($evento_list_item_id);  
    
    $local = '';
    
    $local = $data['cidade'];
    
    if (strlen($local) > 0 && $data['estado']) $local .= ' - ';
    if ($data['estado']) $local .= $data['estado'];
    
    
    
    if ($data['sigla_pais']) {
        $paises = get_paises();
        if (strlen($local) > 0) {
            $local .= ', ' . $paises[$data['sigla_pais']];
        } else {
            $local .= $paises[$data['sigla_pais']];
        }
    
    }

?>

<div id="<?php echo basename(get_permalink($evento_list_item_id)); ?>" class="opportunity clearfix">
    <div class="avatar alignleft">
        <?php if ( has_post_thumbnail($evento_list_item_id) ) : ?>
            <?php echo get_the_post_thumbnail($evento_list_item_id, array(80,80));?>
        <?php else : ?>
            <?php theme_image("tnb-thumb-80.png", array("alt" => get_the_title($evento_list_item_id), "title" => get_the_title($evento_list_item_id))); ?>
        <?php endif; ?>
    </div>
    <div class="content alignleft">
        <h3 class="title bottom"><a href="<?php echo get_permalink($evento_list_item_id); ?>" title="<?php echo htmlentities(get_the_title($evento_list_item_id));?>"><?php echo get_the_title($evento_list_item_id);?></a></h3>
        
        <ul>
            <li><span class="label">Data do evento:</span> <?php echo ($data['br_fim']==$data['br_inicio'] ? $data['br_inicio'] : "$data[br_inicio] - $data[br_fim]") ;?></li>
         <?php if(strtotime($data['inscricao_fim']) < strtotime(date('Y-m-d'))): ?>
            <li><span class="label">Inscrições encerradas</span></li>
         <?php elseif (strtotime($inscricao_data['inicio']) > strtotime(date('Y-m-d'))):?>
            <li><span class="label">Inscrições a partir de:</span> <?php echo $data['br_insc_inicio']; ?></li>
         <?php else:?>
            <li><span class="label">Inscrições até:</span> <?php echo $data['br_insc_fim']; ?></li>
         <?php endif;?>
         
         <?php if(!strtotime($data['inscricao_fim']) < strtotime(date('Y-m-d')) && $data['inscricao_cobrada']): ?>
         	<li><span class="label"><?php _e('valor da inscrição','tnb')?>:</span> <?php echo get_valor_monetario($data['inscricao_valor']); ?></li>
         <?php endif;?>
            <li><span class="label">Local:</span> <?php echo $local; ?></li>
        </ul>
    </div>
</div>
