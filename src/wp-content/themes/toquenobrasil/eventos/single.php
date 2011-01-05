<?php 
    global $current_user;
    $join_success = false;
    
    if(isset($_POST['_wpnonce']) &&  wp_verify_nonce($_POST['_wpnonce'], 'join_event' )){
        if(!in_postmeta(get_post_meta($_POST['evento_id'], 'inscrito'), $_POST['banda_id'])){
            add_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);
            
            do_action('tnb_artista_inscreveu_em_um_evento', $_POST['evento_id'], $_POST['banda_id']);
        }
    }

    if(have_posts()):
        the_post(); 

        // saida csv
        if ($_GET['exportar']) {
            include('export-bandas.php');
            die();
        }

        $selecionados = get_post_meta( get_the_ID(), 'selecionado') ;
        $inscritos = get_post_meta( get_the_ID(), 'inscrito') ;
        
        $num_selecionados = count($selecionados);
        $num_inscritos = count($inscritos);

        $superevento = get_post_meta( get_the_ID(), 'superevento', true) == 'yes';
        $subevento = $post->post_parent ? true : false;

        if ($subevento){
            $evento_pai = get_post($post->post_parent);

            if(!get_post_meta(get_the_ID(), 'aprovado_para_superevento')) {
                if($current_user->ID != get_the_author_ID() && $current_user->ID != $evento_pai->post_author) {
                    wp_redirect(get_bloginfo('siteurl').'/eventos/');
                }
            }
        }
?>

<?php get_header(); ?>

    <div class="prepend-top"></div>

    <div id="event-<?php echo the_ID(); ?>" class="event span-14 prepend-1 right-colborder">
        <div id="event-<?php echo the_ID(); ?>-title" class="item green clearfix">
            <div class="title pull-1 clearfix">
                <div class="shadow"></div>
                <?php if ($subevento): ?>
                    <h1>
                        <a href="<?php echo get_permalink($evento_pai->ID); ?>">
                        <?php echo $evento_pai->post_title; ?>
                        </a>
                    </h1>
                <?php else: ?>
                    <h1><?php the_title(); ?></h1>
                <?php endif; ?>
            </div>
        </div>

        <?php if(current_user_can('edit_users')):?>
        <a href="<?php echo get_stylesheet_directory_uri();?>/exporta_eventos.php?evento=<?php echo get_the_ID();?>">Exportar usuários</a>";
        <?php endif;?>
        
        <!-- partes do evento comum -->
        
        <?php if ($superevento): ?>        
            <?php include('single-superevento.php'); ?>
        <?php else: ?>

        <div id="event-<?php echo the_ID(); ?>-content" class="clearfix">

            <?php if ($subevento): global $post;?>
                
            
                <h2><?php the_title(); ?>
                    <?php if($post->post_status=='draft'):?>
                        <span class="inactive">(<?php _e('Inativo');?>)</span>
                    <?php endif;?>
                </h2>
            
            <?php endif; ?>
            
           <?php include('evento-list-item.php'); ?>
        </div>    

        <div id="selected-artists-title" class="item yellow clearfix">
            <div class="title pull-1 clearfix">
                <div class="shadow"></div>
                <h3>
                    <?php _e('Artistas/Bandas Selecionados','tnb'); ?>
                    <?php
                        if(current_user_can('select_artists') || current_user_can('select_other_artists')) : 
                        echo "($num_selecionados)";
                    endif; ?>
                </h3>

                <?php if (current_user_can('edit_post', get_the_ID())): ?>
                <div style="margin-top: 10px;">&nbsp;
                    <a class="button" href="<?php echo add_query_arg('exportar','selecionado');?>">Exportar planilha</a>&nbsp;
                    <a class="button" onclick="jQuery('#selected-artists-mailbox').dialog('open');">Enviar email</a>&nbsp;

                    <div class="tnb_modal" id="selected-artists-mailbox">
                        <h4><?php _e("Email para artistas selecionados");?></h4>

                        <form method="post">
                            <input type="hidden" name="action" value="mail_selected_artists"/>
                            <input type="hidden" name="post_id" value="<?php the_ID();?>"/>
                            <p>
                                <label for="subject-for-selected" class="clearfix"><?php echo _e("Assunto");?></label>
                                <input type="text" id="subject-for-selected" name="subject"/>
                            </p>

                            <label for="message-for-selected" class="clearfix"><?php echo _e("Mensagem");?></label>
                            <textarea id="message-for-selected" name="message"></textarea>
                            <input type="submit" class="button" value="<?php _e("Enviar");?>"</input>
                        </form>
                    </div>
                </div>

                <?php if($_POST['action']=='mail_selected_artists' && isset($GLOBALS['tnb_errors'])):?>
                <div class="error">
                    <ul>
                    <?php foreach($GLOBALS['tnb_errors'] as $error): ?>
                        <li><?php echo $error;?></li>
                    <?php endforeach; unset($GLOBALS['tnb_errors']);?>
                    </ul>
                </div>
                <?php elseif($_GET['message'] === 'sentforselected'): ?>
                    <div class="message-sent"><?php _e('Mensagem enviada.');?></div>
                <?php endif;?>

                <?php endif; ?>
            </div>
        </div>

        <div id="selected-artists-list" class="clearfix">
            <?php
                foreach($selecionados as $banda_id){
                    if($banda = get_userdata($banda_id))
                        include('banda-list-item.php'); 
                }
            ?>
        </div>

        <div id="signed-artists-title" class="item yellow clearfix">
            <div class="title pull-1 clearfix">
                <div class="shadow"></div>
                <h3>
                    <?php _e('Artistas/Bandas Inscritos','tnb'); ?>
                    <?php
                    if(current_user_can('select_artists') || current_user_can('select_other_artists')) : 
                        echo "($num_inscritos)";
                    endif; ?>
                </h3>
                <?php if (current_user_can('edit_post', get_the_ID())): ?>
                <div style="margin-top: 10px;">&nbsp;
                    <a class="button" href="<?php echo add_query_arg('exportar','inscrito');?>">Exportar planilha</a>&nbsp;
                    <a class="button" onclick="jQuery('#signed-artists-mailbox').dialog('open');">Enviar email</a>&nbsp;

                    <div class="tnb_modal" id="signed-artists-mailbox">
                        <h4><?php _e("Email para artistas inscritos");?></h4>

                        <form method="post">
                            <input type="hidden" name="action" value="mail_signed_artists"/>
                            <input type="hidden" name="post_id" value="<?php the_ID();?>"/>
                            <p>
                                <label for="subject-for-signed" class="clearfix"><?php echo _e("Assunto");?></label>
                                <input type="text" id="subject-for-signed" name="subject"/>
                            </p>

                            <label for="message-for-signed" class="clearfix"><?php echo _e("Mensagem");?></label>
                            <textarea id="message-for-signed" name="message"></textarea>
                            <input type="submit" class="button" value="<?php _e("Enviar");?>"</input>
                        </form>
                    </div>
                </div>

                <?php if($_POST['action']=='mail_signed_artists' && isset($GLOBALS['tnb_errors'])):?>
                <div class="error">
                    <ul>
                    <?php foreach($GLOBALS['tnb_errors'] as $error): ?>
                        <li><?php echo $error;?></li>
                    <?php endforeach; unset($GLOBALS['tnb_errors']);?>
                    </ul>
                </div>
                <?php elseif($_GET['message'] === 'sentforsigned'): ?>
                    <div class="message-sent"><?php _e('Mensagem enviada.');?></div>
                <?php endif;?>

                <?php endif; ?>
            </div>
        </div>

        <div id="signed-artists-list" class="clearfix">
            <?php if($join_success):?>
                <div class='success' id='join_success'><?php _e('Suas informações foram enviadas ao produtor do evento para curadoria. <br/> Apos encerramento das inscrições você receberá um email com a resposta positiva ou negativa.', 'tnb');?></div>
                <script type="text/javascript">
                    jQuery.scrollTo('#join_success', 800);
                </script>
            <?php endif;?>

            <?php
                foreach($inscritos as $banda_id){
                    if($banda = get_userdata($banda_id))
                        include('banda-list-item.php'); 
                }
            ?>
        </div>

        <?php endif; ?>    

        <div id="posts-navigation">
            <?php previous_post_link('<div id="anterior">%link</div>','Evento anterior', true); ?>
            <?php next_post_link('<div id="proximo">%link</div>', 'Próximo evento', true); ?>            
        </div><!-- #posts-navigation -->
    </div>
<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
