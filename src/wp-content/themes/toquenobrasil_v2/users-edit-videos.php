<?php 
$videos = tnb_get_artista_videos($profileuser->ID);
$video_principal = tnb_get_artista_video_principal($profileuser->ID);

?>
<section id="video" class="content clearfix">
   <form method='post' action="<?php echo remove_query_arg(array('tnb_user_action', 'mid', 'mtype')); ?>" >
        <input type='hidden' name='tnb_user_action' value='<?php echo $edit ? 'edit-video-save' : 'insert-video'; ?>' />

        <h2 class="section-title">
            <span class="bg-blue"><?php echo $edit ? _e("Editar Vídeo","tnb") : _e("Novo Vídeo","tnb");; ?></span>
        </h2>
    
        <div class="clearfix">
            <label><?php _e("Link do vídeo (vimeo ou youtube)", "tnb"); ?></label>
            <input type="text" name="video_url" value="<?php echo htmlspecialchars($video_edit_url); ?>" />
        </div>
        
        <?php if ($edit): ?>
            <?php print_video_player($video_edit_url); ?>
            <input type="hidden" name="mid" value="<?php echo $video_edit_id; ?>" />
        <?php endif; ?>
        
        <div class="clearfix">
            <label><?php _e("Título", "tnb"); ?></label>
            <input type="text" name="video_title" value="<?php echo htmlspecialchars($video_edit_title); ?>" />
        </div>
        <div class="clearfix">
            <label><?php _e("Descrição", "tnb"); ?></label>
            <textarea name="video_description"><?php echo htmlspecialchars($video_edit_description); ?></textarea>
        </div>
        
        <div class="checkbox clearfix">
            <input type="checkbox" id="video_principal" name="video_principal" <?php if ($edit && $video_principal->ID == $video_edit_id) echo 'checked'; ?> />
            <label for="music_principal"><?php _e("Vídeo principal do seu kit de divulgação para o produtor", "tnb"); ?></label>
        </div>
        <!-- .checkbox -->
        
        <div class="clearfix text-right">
            <?php if ($edit): ?>
                <a href="<?php echo remove_query_arg(array('tnb_user_action', 'mid', 'mtype')); ?>">Cancelar</a>
            <?php endif; ?>
            <input type="submit" value="Salvar" class="submit" />
        </div>
    </form>

    <hr/>
    
    <?php if (!$edit): ?>
        <div class="lista-midias">
            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Meus Vídeos", "tnb"); ?></span>
            </h2>

            <?php if(!$videos) : ?>
                <hr/>
                <p class="text-center"><?php _e("Você ainda não subiu nenhum vídeo", "tnb"); ?></p>
                <hr/>
            <?php else: ?>        
                <a class="comecar-ordenacao btn-yellow"><?php _e('Ordenar Vídeos', 'tnb'); ?></a>
                <hr/>
                <?php foreach ($videos as $video): ?>
                    <div class="video clearfix <?php if($video_principal->ID == $video->ID): ?>video-principal<?php endif; ?>">
                        <h3 class="bottom"><?php echo $video->post_title; ?></h3>
                        <p>
                            <a href="?tnb_user_action=edit-video&mid=<?php echo $video->ID; ?>">Editar</a> | <a class="apagar-media" href="?tnb_user_action=delete-video&mid=<?php echo $video->ID; ?>">Apagar</a>
                            <?php if($video_principal->ID == $video->ID): ?><br/><em><?php _e("Esta é seu vídeo principal e aparecerá para o produtor no seu kit de divulgação.", "tnb"); ?></em><?php endif; ?>
                        </p>
                        <?php print_video_player($video->post_excerpt); ?>
                        <p><?php echo $video->post_content; ?></p>
                    </div>
                    <!-- .video -->
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <!-- .lista-midias -->
    <?php endif; ?>
    
    <div class="ordenar-midias">
        <h2 class="section-title">
            <span class="bg-blue"><?php _e("Ordenar Vídeos", "tnb"); ?></span>
        </h2>
    
        <p class="text-center"><?php _e('Arraste e solte para ordenar', 'tnb'); ?></p>

        <ul class="ordenar">
            <?php foreach($videos as $video): ?>
                <li id="media_<?php echo $video->ID; ?>" class="<?php if($video_principal->ID == $video->ID): ?>video-principal<?php endif; ?>"><?php echo $video->post_title; ?></li>
            <?php endforeach; ?>
        </ul>
    
        <form class="ordenacao" name="ordenacao" action="<?php echo remove_query_arg(array('tnb_user_action', 'mid', 'mtype')); ?>" method="post">
            <input type='hidden' name='tnb_user_action' value='save-order' />
            <input type="hidden" id="order_input" name="ordem" value="" />
            <p class="text-right">
                <input type="button" class="cancelar-ordenacao grey" value="<?php _e('Cancelar', 'tnb'); ?>" />
                <input type="submit" value="Salvar" class="salvar-ordenacao submit" />
            </p>
        </form>
    </div>
    <!-- .ordernar-midias -->
</section>
<!-- #video -->
