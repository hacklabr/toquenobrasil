<?php
    if ( !is_user_logged_in() ) {
        wp_redirect('/toquenobrasil/');
    }

    global $current_user;

    function the_edit_link($post=null) {
        global $id, $current_user;

        if($post === null) {
            $post = & get_post($id);
        }

        echo bloginfo('siteurl').'/rede/'.$current_user->user_login.'/eventos/'.$post->post_name.'/editar/';
    }

    function get_meta($key) {
        global $id;
        return get_post_meta($id, $key, true);
    }
    
    /*
     * Moderar sub eventos
     * TODO: Estas chamadas poderiam ser feitas via POST
     */    
    if ($moderarSubId = $_GET['aprovar_subevento']) {
        $moderarSub = get_post($moderarSubId);
        if (isset($moderarSub->post_parent) && $moderarSub->post_parent > 0) {
            if (current_user_can('edit_post', $moderarSub->post_parent)) {
                do_action('tnb_subevento_e_aprovado_em_um_superevento',$moderarSubId);
                delete_post_meta($moderarSubId, 'aprovado_para_superevento');
                update_post_meta($moderarSubId, 'aprovado_para_superevento', $moderarSub->post_parent);
            }
        }
    }
    if ($moderarSubId = $_GET['recusar_subevento']) {
        $moderarSub = get_post($moderarSubId);
        if (isset($moderarSub->post_parent) && $moderarSub->post_parent > 0) {
            if (current_user_can('edit_post', $moderarSub->post_parent)) {
                delete_post_meta($moderarSubId, 'aprovado_para_superevento', $moderarSub->post_parent);
            }
        }
    }
    
    
    /* Configura main loop para eventos normais */
    
    if (current_user_can('edit_others_posts')) {
        $query_args = array(
            'post_type' => 'eventos',
            'post_parent' => 0,
            'meta_key' => 'superevento',
            'meta_value' => 'no',
            'post_status' => 'any'
        );
    } else {
        $query_args = array(
            'author' => $current_user->ID,
            'post_type' => 'eventos',
            'post_parent' => 0,
            'meta_key' => 'superevento',
            'meta_value' => 'no',
            'post_status' => 'any'
        );
    }   
    $normal_events = query_posts($query_args);
    global $post;
?>
<?php get_header(); ?>

    <div class="clear"></div>
    <div class="prepend-top"></div>

    <div class="span-14 prepend-1 right-colborder">
        <div class="item green clearfix">
            <div class="title pull-1">
                <div class="shadow"></div>
                <h1><?php _e('Gerenciar Eventos');?></h1>                
            </div>
            <a class="add-new-event" href='<?php echo get_author_posts_url($current_user->ID)?>/eventos/novo'>+ Adicionar Novo Evento</a>
        </div>
        
        <ul id="gerenciar-eventos">
        
            <div>
            
            </div>
        
            <?php while(have_posts()): the_post();?>
            <li <?php if ($post->post_status == 'draft') echo 'class="evento-inativo"';?>>
                <?php if ($post->post_parent > 0): ?>
                    Esse evento é filho do super evento <?php echo get_the_title($post->post_parent); ?>
                <?php endif; ?>
                <h2 class="alignleft">
                    <a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a>
                    <?php if ($post->post_status == 'draft') : ?>
                        <span>Inativo</span>
                    <?php endif; ?>
                </h2>
                
                <?php if (current_user_can('edit_post', get_the_ID())): ?>
                    <div class="clear">
                        <a href="<?php the_edit_link();?>" title="<?php _e('Editar');?>"><?php _e('Editar');?></a>
                    </div>
                <?php endif; ?>
            </li>
            <?php endwhile; ?>

<?php
    /* Configura main loop para supereventos */
    $query_args = array(
        'post_type' => 'eventos',
        'post_parent' => 0,
        'meta_key' => 'superevento',
        'meta_value' => 'yes',
        'post_status' => 'any'
    );
    $superevents = query_posts($query_args);
    
    if (have_posts()):
        while(have_posts()): the_post();
            
            if (current_user_can('edit_post', get_the_ID())) {
                $query_args = array(
                    'post_type' => 'eventos',
                    'post_parent' => get_the_ID(),
                    'meta_key' => 'superevento',
                    'meta_value' => 'no',
                    'post_status' => 'any',
                    'numberposts' => -1
                );
            } else {
                $query_args = array(
                    'post_type' => 'eventos',
                    'post_parent' => get_the_ID(),
                    'author' => $current_user->ID,
                    'meta_key' => 'superevento',
                    'meta_value' => 'no',
                    'post_status' => 'any',
                    'numberposts' => -1
                );
            }

            $add_subevent_url=  sprintf("%s/rede/%s/eventos/novo/?superevento=no&post_parent=%d",
                                        get_bloginfo('siteurl'),
                                        $current_user->user_login,
                                        get_the_ID());
            
            $subevents = get_posts($query_args);

            if(count($subevents) > 0 || current_user_can('edit_post', get_the_ID())):
                ?>
                <li <?php if ($post->post_status == 'draft') echo 'class="evento-inativo"';?>>
                    <h2>
                        <a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a>
                        <?php if ($post->post_status == 'draft') : ?>
                        <span>Inativo</span>
                    <?php endif; ?>
                    </h2>
                    
                    <?php if (current_user_can('edit_post', get_the_ID())): ?>
                        <div class="clear">
                            <a href="<?php the_edit_link();?>" title="<?php _e('Editar');?>"><?php _e('Editar');?></a>
                            <a href="<?php echo $add_subevent_url;?>" title="<?php _e('Adicionar subevento');?>"><?php _e('Adicionar subevento');?></a>
                        </div>
                    <?php endif; ?>
                    <ul>
                    <?php foreach($subevents as $sub): ?>
                    <li <?php if ($sub->post_status == 'draft') echo 'class="evento-inativo"';?>>
                        <h3>
                            <a href="<?php echo get_permalink($sub->ID); ?>" title="<?php echo $sub->post_title;?>"><?php echo $sub->post_title;?></a>
                            <?php if ($sub->post_status == 'draft') : ?><span>Inativo</span><?php endif; ?>
                        </h3>
                        
                        <div class="clear">
                        <?php if (current_user_can('edit_post', $sub->ID)): ?>
                                <a href="<?php the_edit_link($sub);?>" title="<?php _e('Editar');?>"><?php _e('Editar');?></a>
                        <?php endif; ?>
                        
                        <?php if (get_post_meta($sub->ID, 'aprovado_para_superevento', true) == get_the_ID()): ?>                            
                            <?php if (current_user_can('edit_post', get_the_ID())): // quem pode editar o superevento, pode moderar os subeventos  ?>
                                <a class="recusar-subevento" href="<?php echo add_query_arg(array('recusar_subevento' => $sub->ID, 'aprovar_subevento' => null)); ?>" title="<?php _e('Recusar');?>"><?php _e('Recusar');?></a>
                            <?php else: ?>
                                Aprovado
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if (current_user_can('edit_post', get_the_ID())): // quem pode editar o superevento, pode moderar os subeventos  ?>
                                <a class="aprovar-subevento" href="<?php echo add_query_arg(array('aprovar_subevento'=> $sub->ID, 'recusar_subevento' => null)); ?>" title="<?php _e('Aprovar');?>"><?php _e('Aprovar');?></a>
                            <?php else: ?>
                                Aguardando aprovação
                            <?php endif; ?>
                        <?php endif; ?>
                        </div>
                    </li>
                    <?php endforeach;?>
                    </ul>
                </li>
                <?php
            endif;
        endwhile;
    endif;    
    ?>
        </ul>
    </div>

<?php get_sidebar(); ?>

<script>

jQuery(document).ready(function() {
    jQuery('.aprovar-subevento').click(function() {
        if (confirm('Você deseja aprovar este sub evento?')) {
            return true;
        } else {
            return false;
        }
    });
    
    jQuery('.recusar-subevento').click(function() {
        if (confirm('Você deseja recusar este sub evento?')) {
            return true;
        } else {
            return false;
        }
    });
});

</script>

<?php get_footer(); ?>
