<?php
    if(!$evento_list_item_id) {
        $evento_list_item_id = get_the_ID();
    }

    $evento_list_item = get_post($evento_list_item_id);
    $paises = $paises?$paises:get_paises();

    $inicio = get_post_meta($evento_list_item_id, "evento_inicio", true);
    $fim = get_post_meta($evento_list_item_id, "evento_fim", true);
    $br_inicio = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1", $inicio);
    $br_fim = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1",$fim);
    $inscricao_inicio = get_post_meta($evento_list_item_id, "evento_inscricao_inicio", true);
    $inscricao_fim = get_post_meta($evento_list_item_id, "evento_inscricao_fim", true);
    $br_insc_inicio = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1", $inscricao_inicio);
    $br_insc_fim = preg_replace("/([0-9]{4})-([0-9]{2})-([0-9]{2})/","$3/$2/$1",$inscricao_fim);
    $local = get_post_meta($evento_list_item_id, "evento_local", true);
    $sigla_pais = get_post_meta($evento_list_item_id, "evento_pais", true);
    $estado = strtoupper(get_post_meta($evento_list_item_id, "evento_estado", true));
    $cidade = get_post_meta($evento_list_item_id, "evento_cidade", true);
    $link = get_post_meta($evento_list_item_id, "evento_site", true);
    $vagas = get_post_meta($evento_list_item_id, "evento_vagas", true);
    $condicoes = !$supress_condicoes?get_the_condicoes($evento_list_item_id):null;
    $restricoes = !$supress_restricoes?get_the_restricoes($evento_list_item_id):null;
    $tos = get_the_tos($evento_list_item_id);
    $superevento = get_post_meta($evento_list_item_id, "superevento", true) == 'yes';

    $patrocinador_1 = get_post_meta($evento_list_item_id, "evento_patrocinador1", true) ;
    $patrocinador_2 = get_post_meta($evento_list_item_id, "evento_patrocinador2", true) ;
    $patrocinador_3 = get_post_meta($evento_list_item_id, "evento_patrocinador3", true) ;
    $subevento = $evento_list_item->post_parent != 0;

?>

<?php global $current_user; ?>
<a href="<?php echo get_permalink($evento_list_item_id); ?>" title="">
    <div id="thumb"<?php echo $superevento?'':' class="span-4"';?>>
        <?php
            $event_link_title = __('Visitar página do evento', 'tnb');
            if ( has_post_thumbnail($evento_list_item_id) ) :
                $thumbsize = $superevento ? "banner-horizontal" : "thumbnail";
                echo get_the_post_thumbnail($evento_list_item_id, $thumbsize, array('title'=> $event_link_title));
            else :
                theme_image($superevento ? "thumb-superevento.png" : "thumb.png", array('title'=> $event_link_title));
            endif;
        ?>
    </div><!-- .thumb -->
</a>
<div class="span-<?php echo $superevento?11:7;?>">
  <div id="dados-do-evento">
        
    <p>
      
      <span class="labels"><?php _e('Tipo de evento:', 'tnb');?></span> <?php echo get_post_meta($evento_list_item_id, "evento_tipo", true); ?><br />
      <span class="labels"><?php _e('Data do evento:', 'tnb');?></span> <?php echo ($br_fim==$br_inicio ? $br_inicio : "$br_inicio - $br_fim") ;?><br />
      <span class="labels"><?php _e('Inscrições até:', 'tnb');?></span> <?php echo $br_insc_fim; ?><br />

      <span class="labels"><?php _e('Local:', 'tnb');?></span> <?php echo ($local?"$local, ":'')."$cidade".($estado?"/$estado":'')." - {$paises[$sigla_pais]}"; ?><br />
            
      <?php if($link):?>
        <span class="labels"><?php _e('Site:', 'tnb');?></span> <a href="<?php echo $link; ?>" target="_blank"><?php echo $link; ?></a><br />
      <?php endif; ?>
            
      <?php if($vagas):?>
        <span class="labels"><?php _e('Vagas:', 'tnb');?></span> <?php echo $vagas; ?><br />
      <?php endif; ?>

      <?php if($subevento):?>
        <span class="labels"><?php _e('Aprovado:', 'tnb');?></span> <?php echo get_post_meta($evento_list_item_id,'aprovado_para_superevento',true) ==  $evento_list_item->post_parent ? _e('Sim') : _e('Não');?><br/>
      <?php endif; ?>

      <span class="labels"><?php _e('Produtor');?>:</span> <a href="<?php echo get_author_posts_url($evento_list_item->post_author);?>"><?php echo get_author_name($evento_list_item->post_author); ?></a><br />
      
      <?php if (current_user_can('edit_post', $evento_list_item_id)): ?>
        <a href="<?php echo get_author_posts_url($evento_list_item->post_author);?>/eventos/<?php echo $evento_list_item->post_name;?>/editar">Editar este evento</a> <br />
      <?php endif; ?>
      
      
    </p>
  </div><!-- .dados-do-evento -->
</div>

<div class="span-<?php echo $subevento?2:3;?> last">

    <?php if(is_produtor() && $superevento && strtotime($inscricao_fim) >= strtotime(date('Y-m-d'))): ?>
        <div class="novo-subevento quero-tocar">
            <a href="<?php bloginfo('siteurl');?>/rede/<?php echo $current_user->user_nicename;?>/eventos/novo/?superevento=no&post_parent=<?php echo $evento_list_item_id;?>">Inscrever meu evento</a>
        </div>
    <?php elseif( is_artista() && in_postmeta(get_post_meta($evento_list_item_id, 'selecionado'), $current_user->ID)): ?>
        <div class="quero-tocar iam-selected">
            <a><?php _e('Já fui<br />selecionado!', 'tnb');?></a>
        </div>
    <?php  elseif(is_artista() &&  in_postmeta(get_post_meta($evento_list_item_id, 'inscrito'), $current_user->ID) && strtotime($inscricao_inicio) <= strtotime(date('Y-m-d')) && strtotime($inscricao_fim) >= strtotime(date('Y-m-d'))): ?>

        <div class="quero-tocar cancel-subscription">
            <form action='<?php the_permalink();?>' method="post" id='form_unjoin_event_<?php echo $evento_list_item_id; ?>'>
                <?php wp_nonce_field('unjoin_event'); ?>
                <input type="hidden" name="banda_id" value='<?php echo $current_user->ID; ?>' />
                <input type="hidden" name="action" value='unjoin' />
                <input type="hidden" name="evento_id" value='<?php echo $evento_list_item_id; ?>' />
            </form>
            <a onclick="jQuery('#form_unjoin_event_<?php echo $evento_list_item_id; ?>').submit();"><?php _e('Cancelar<br />inscrição', 'tnb');?></a>
        </div>
    
        
    <?php  elseif(!$superevento && strtotime($inscricao_inicio) <= strtotime(date('Y-m-d')) && strtotime($inscricao_fim) >= strtotime(date('Y-m-d'))):?>  
        <?php if( is_artista() ):?>
            <form action='<?php the_permalink();?>' method="post" id='form_join_event_<?php echo $evento_list_item_id; ?>'>
                <?php wp_nonce_field('join_event'); ?>
                <input type="hidden" name="banda_id" value='<?php echo $current_user->ID; ?>' />
                <input type="hidden" name="action" value='join' />
                <input type="hidden" name="evento_id" value='<?php echo $evento_list_item_id; ?>' />
            </form>
            
            <?php if($tos):?>    
                <div class='tnb_modal' id='tnb_modal_<?php echo $evento_list_item_id; ?>'>
                    <h2><?php _e('Termo de Responsabilidade','tnb'); ?></h2>
                    <?php echo $tos; ?>
                    <div class="textright">
                        <a onclick="jQuery('#form_join_event_<?php echo $evento_list_item_id; ?>').submit();" class="button"><?php _e('Li e aceito o termo', 'tnb');?></a>
                    </div>
                </div>
                <div class="quero-tocar i-wanna-play">
                    <a onclick="jQuery('#tnb_modal_<?php echo $evento_list_item_id; ?>').dialog('open');" title="<?php printf(__('Participe do evento %s', 'tnb'),  get_the_title());?>"><?php _e('Me<br />Inscrever!', 'tnb');?></a>
                </div>
            <?php else:?>
                <div class="quero-tocar i-wanna-play">
                    <a onclick="jQuery('#form_join_event_<?php echo $evento_list_item_id; ?>').submit();"><?php _e('Me<br />Inscrever!', 'tnb');?></a>
                </div>
            <?php endif;?>   
          
        <?php  elseif(!is_user_logged_in()) :?>
          <div class="quero-tocar i-wanna-play">
            <a href="<?php bloginfo('url');?>/cadastre-se/artista" title='<?php _e('Cadastre-se para poder participar do Toque no Brasil!', 'tnb');?>'><?php _e('Me<br />Inscrever!', 'tnb');?></a>
          </div>
        <?php endif;?>

    <?php /* Quando as inscrições estão encerradas */ ?>
    <?php elseif(strtotime($inscricao_fim) < strtotime(date('Y-m-d'))): ?>    

        <div class="quero-tocar inscricoes-encerradas">
            <a><?php _e('Inscrições <br /> encerradas!', 'tnb');?></a>
        </div>

    <?php /* Quando as inscrições ainda não abriram */ ?>
    <?php elseif(strtotime($inscricao_inicio) > strtotime(date('Y-m-d'))): ?>    

        <div class="quero-tocar em-breve">
            <a><?php _e('Em breve!', 'tnb');?></a>
        </div>

    <?php endif;?>
 </div>
 
 <?php if(is_single()) echo '<div class="clear"></div>' ;?>
 
<div class="span-<?php echo $subevento?13:14;?>">
   <?php 
     if(is_single())
       echo apply_filters('the_content',$evento_list_item->post_content);
     else
       echo apply_filters('the_excerpt',$evento_list_item->post_excerpt);
   ?>
   <div class="clear"></div>
   
   
   
   <?php if (is_single()): ?>
   
        <?php if ($patrocinador_1) echo wp_get_attachment_image($patrocinador_1, 'banner-horizontal'); ?>
        <?php if ($patrocinador_2) echo wp_get_attachment_image($patrocinador_2, 'banner-horizontal'); ?>
        <?php if ($patrocinador_3) echo wp_get_attachment_image($patrocinador_3, 'banner-horizontal'); ?>
   
        <div class="clear"></div>
   
       <?php if($restricoes) : ?>
         <div class="restrictions">
           <h3>
             <?php _e('Restrições para participar','tnb'); ?>
             <span>+</span>
           </h3>
           <p><?php echo $restricoes; ?></p>
           
         </div>
       <?php endif; ?>    
                
      <?php if($condicoes) : ?>
        <div class="conditions">
          <h3>
            <?php _e('Condições para participar','tnb'); ?>
            <span>+</span>
          </h3>
          <p><?php echo $condicoes; ?></p>
        </div>
      <?php endif;  ?>
      
      <div class="post-tags">
        <p><?php the_tags(" "," "," "); ?></p>
      </div><!-- .post-tags -->

  <?php endif; ?>
</div>

<div class="clear"></div>

<?php unset($evento_list_item_id);?>
