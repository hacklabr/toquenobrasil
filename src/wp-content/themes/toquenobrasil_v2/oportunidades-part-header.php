<?php
global $oportunidade_item;
$header_data = get_oportunidades_data($oportunidade_item->ID); 

$local = '';
    
$local = $header_data['cidade'];

if (strlen($local) > 0 && $header_data['estado']) $local .= ' - ';
if ($header_data['estado']) $local .= $header_data['estado'];



if ($header_data['sigla_pais']) {
    $paises = get_paises();
    if (strlen($local) > 0) {
        $local .= ', ' . $paises[$header_data['sigla_pais']];
    } else {
        $local .= $paises[$header_data['sigla_pais']];
    }

}

?>

<p>
 <?php echo get_the_post_thumbnail($oportunidade_item->ID, array(528,528));?>
</p>

<ul>
    <?php if(current_user_can('edit_post')):?><li><a href='<?php echo get_author_posts_url($oportunidade_item->post_author).'/editar/oportunidades/'.$oportunidade_item->post_name ?>' class='alignright'><?php _e('editar'); ?></a></li><?php endif;?>
    <li><span class="label">Data da oportunidade:</span> <?php echo ($header_data['br_fim']==$header_data['br_inicio'] ? $header_data['br_inicio'] : "$header_data[br_inicio] - $header_data[br_fim]") ;?></li>
    <?php if(strtotime($header_data['inscricao_fim']) < strtotime(date('Y-m-d'))): ?>
       <li><span class="label">Inscrições encerradas</span></li>
    <?php elseif (strtotime($inscricao_inicio) > strtotime(date('Y-m-d'))):?>
       <li><span class="label">Inscrições a partir de:</span> <?php echo $header_data['br_insc_inicio']; ?></li>
    <?php else:?>
       <li><span class="label">Inscrições até:</span> <?php echo $header_data['br_insc_fim']; ?></li>
    <?php endif;?>
    <li><span class="label">Local:</span> <?php echo $local; ?></li>
    <li><span class="label">Estabelecimento:</span> <?php echo $header_data['local']; ?></li>
    <li><span class="label">Site:</span> <a href="<?php echo $header_data['link']; ?>" title="<?php echo $header_data['link']; ?>"><?php echo $header_data['link']; ?></a></li>
    <li><span class="label">Vagas:</span> <?php echo $header_data['vagas']; ?></li>
    <li><span class="label">Produtor:</span> <a href="<?php echo get_author_posts_url($oportunidade_item->post_author);?>"><?php echo get_author_name($oportunidade_item->post_author); ?></a></li>
    
</ul>

<p>
     <?php 
        if(is_single())
            echo apply_filters('the_content',$oportunidade_item->post_content);
        else
            echo apply_filters('the_excerpt',$oportunidade_item->post_excerpt);
    ?>
</p>

<div class="clearfix">
    <section class="conditions">
        <h4 class="title">Condições</h4>
        <div class="clear"></div>
        <div class="content">
            <?php if (is_array($header_data['condicoes'])): ?>
                <table>
                    <tr>
                        <td>Hospedagem</td>
                        <td class="text-center"><?php if($header_data['condicoes']['hospedagem']) theme_image("yes.png", array("alt" => "Sim", "title" => "Sim")); else theme_image("no.png", array("alt" => "Não", "title" => "Não")); ?></td>
                    </tr>
                    <tr>
                        <td>Alimentação</td>
                        <td class="text-center"><?php if($header_data['condicoes']['alimentacao']) theme_image("yes.png", array("alt" => "Sim", "title" => "Sim")); else theme_image("no.png", array("alt" => "Não", "title" => "Não")); ?></td>
                    </tr>
                    <tr>
                        <td>Transporte local</td>
                        <td class="text-center"><?php if($header_data['condicoes']['transporte_local']) theme_image("yes.png", array("alt" => "Sim", "title" => "Sim")); else theme_image("no.png", array("alt" => "Não", "title" => "Não")); ?></td>
                    </tr>
                    <tr>
                        <td>Transporte enre cidades</td>
                        <td class="text-center"><?php if($header_data['condicoes']['transporte_cidades']) theme_image("yes.png", array("alt" => "Sim", "title" => "Sim")); else theme_image("no.png", array("alt" => "Não", "title" => "Não")); ?></td>
                    </tr>
                    <tr>
                        <td>Cache</td>
                        <td class="text-center"><?php if($header_data['condicoes']['cache']) theme_image("yes.png", array("alt" => "Sim", "title" => "Sim")); else theme_image("no.png", array("alt" => "Não", "title" => "Não")); ?></td>
                    </tr>
                </table>
            <?php else: ?>
            
                <p><?php echo $header_data['condicoes']; ?></p>
                
            <?php endif; ?>
        </div>
    </section>
    <!-- .conditions -->
    <section class="restrictions">
        <h4 class="title">Restrições</h4>
        <div class="clear"></div>
        <div class="content">
            <p><?php echo $header_data['restricoes']; ?></p>
        </div>
        <div class='filtro'>
            <?php if($header_data['filtro_estilo']): ?>
                <strong><?php _e('Estilos Musicais', 'tnb')?>:</strong> 
                
                <?php foreach ($header_data['filtro_estilo'] as $estilo):?><?php echo $sep0.__($estilo,'tnb');?><?php $sep0 = ', '; endforeach;?>
                
            <?php endif;?>
            
            
            <?php if($header_data['filtro_origem_pais']): $pais = get_paises();  $estados = get_estados();?>
                <br /><strong><?php _e('País de origem','tnb'); ?>:</strong> <?php _e($pais[$header_data['filtro_origem_pais']], 'tnb')?>
                <?php if($header_data['filtro_origem_uf']):?>
                    <br />
                    <strong><?php _e('Estado de origem', 'tnb')?>: </strong>
                    <?php foreach ($header_data['filtro_origem_uf'] as $uf):?><?php echo $sep1 . __($estados[$uf],'tnb');?><?php $sep1 = ', '; endforeach;?>
                <?php endif; ?>
            <?php endif;?>
            
            
           <?php if($header_data['filtro_residencia_pais']): $pais = get_paises(); $estados = get_estados();?>
                <br /><strong><?php _e('País de residência','tnb'); ?>:</strong> <?php _e($pais[$header_data['filtro_residencia_pais']], 'tnb')?>
                <?php if($header_data['filtro_residencia_uf']):?>
                    <br />
                    <strong><?php _e('Estado de residência', 'tnb')?>:</strong> 
                    <?php foreach ($header_data['filtro_residencia_uf'] as $uf):?><?php echo $sep2 . __($estados[$uf],'tnb');?><?php $sep2 = ', '; endforeach;?>
                <?php endif; ?>
            <?php endif;?>
        </div>
    </section>
    <!-- .restrictions -->
    
    <div class="clear"></div>
    
    <section class="sponsors clearfix">
        <?php echo wp_get_attachment_image($header_data['patrocinador_1'], "large"); ?>
        <?php echo wp_get_attachment_image($header_data['patrocinador_2'], "large"); ?>
        <?php echo wp_get_attachment_image($header_data['patrocinador_3'], "large"); ?>
    </section>
</div>
