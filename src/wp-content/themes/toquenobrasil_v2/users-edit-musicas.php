<?php
    global $wpdb;  
    $musicas = tnb_get_artista_musicas($profileuser->ID);
    $musica_principal = tnb_get_artista_musica_principal($profileuser->ID);
?>

<section id="music" class="content">
    <form method='post' enctype="multipart/form-data" action="<?php echo remove_query_arg(array('tnb_user_action', 'mid', 'mtype')); ?>" >
        <input type='hidden' name='tnb_user_action' value='<?php echo $edit ? 'edit-musica-save' : 'insert-musica'; ?>' />

        <h2 class="section-title">
            <span class="bg-blue"><?php echo $edit ? __("Editar Música", "tnb") . " $musica_edit_title" : __("Adicionar Música","tnb"); ?></span>
        </h2>

        <?php if (!$edit): ?>
            <div class="clearfix">
                <label><?php _e("Arquivo", "tnb"); ?></label>
                <input type="file" name='music' />
                <span class="info"><?php _e("O arquivo deve ser no formato <strong>mp3</strong>.");?></span>
            </div>
            <!-- .clearfix -->
        <?php else: ?>
            <?php print_audio_player($musica_edit_id); ?>
            <input type="hidden" name="mid" value="<?php echo $musica_edit_id; ?>" />
        <?php endif; ?>
        <!-- .clearfix -->

        <div class="clearfix">
            <label><?php _e("Título da música", "tnb"); ?></label>
            <input type="text" name="music_title" value="<?php echo htmlspecialchars($musica_edit_title); ?>" />
        </div>
        <!-- .clearfix -->

        <div class="clearfix">
            <label><?php _e("Título do albúm", "tnb"); ?></label>
            <input type="text" name="music_album" value="<?php echo htmlspecialchars($musica_edit_album); ?>" />
        </div>
        <!-- .clearfix -->
        <?php if(!$profileuser->{$wpdb->prefix."capabilities"}['produtor']):?>
        <div class="checkbox clearfix">
            <input type="checkbox" id="music_download" name="music_download" value="1" <?php if ($musica_edit_download) echo 'checked'; ?> />
            <label for="music_download"><?php _e("Permitir download?", "tnb"); ?></label>
        </div>
        <!-- .checkbox -->
        <?php endif;?>
        <div class="checkbox clearfix">
            <input type="checkbox" id="music_principal" name="music_principal" <?php if ($edit && $musica_principal->ID == $musica_edit_id) echo 'checked'; ?> />
            <label for="music_principal"><?php _e("Música principal (aparecerá sempre que sua banda for listada)", "tnb"); ?></label>
        </div>
        <!-- .checkbox -->
        
        <div class="clearfix text-right">
            <?php if ($edit): ?>
                <a href="<?php echo remove_query_arg(array('tnb_user_action', 'mid', 'mtype')); ?>"><?php _e("Cancelar",'tnb')?></a>
            <?php endif; ?>
            <input type="submit" value="<?php _e("Salvar",'tnb')?>" class="submit" />
        </div>
        <!-- .clearfix -->

        <hr/>
    </form>

    
    <?php if (!$edit): ?>
        <div class="lista-midias">
            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Minhas Músicas", "tnb"); ?></span>
            </h2>

            <?php if(!$musicas) : ?>
                <hr/>
                <p class="text-center"><?php _e("Você ainda não subiu nenhuma música", "tnb"); ?></p>
                <hr/>

            <?php else : ?>
                <a class="btn-yellow comecar-ordenacao"><?php _e('Ordenar Músicas', 'tnb'); ?></a>
                <hr/>

                <?php foreach($musicas as $musica): $musica_data = tnb_get_artista_musica_data($musica->ID);?>
                    <div class="music clearfix <?php if($musica_principal->ID == $musica->ID): ?>musica-principal<?php endif; ?>">
                        <div class="grid_3 bottom">
                            <?php print_audio_player($musica->ID); ?>
                            <br/>
                            <a href="?tnb_user_action=edit-musica&mid=<?php echo $musica->ID; ?>">Editar</a> | <a class="apagar-media" href="?tnb_user_action=delete-media&mtype=music&mid=<?php echo $musica->ID; ?>">Apagar</a>
                        </div>
                        <div class="grid_6 bottom">
                            <h4 class="bottom"><?php echo $musica->post_title; ?></h4>
                            <p class="bottom">
                                <?php echo $musica_data['album']; ?>
                                <br/>
                                <?php if($musica_data['download']): ?>
                                    <a href='<?php echo $musica->post_content; ?>'>download</a>
                                <?php endif;?>
                                <?php if($musica_principal->ID == $musica->ID): ?><br/><em><?php _e("Esta é sua música principal e aparecerá sempre que você se inscrever em uma oportunidade.", "tnb"); ?></em><?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <!-- .music -->
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <!-- .lista-midias -->
        
        <div class="ordenar-midias">
            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Ordenar Músicas", "tnb"); ?></span>
            </h2>
        
            <p class="text-center"><?php _e('Arraste e solte para ordenar', 'tnb'); ?></p>
        
            <ul class="ordenar">
                <?php foreach($musicas as $musica): ?>
                    <li id="media_<?php echo $musica->ID; ?>" class="<?php if($musica_principal->ID == $musica->ID): ?>musica-principal<?php endif; ?>"><?php echo $musica->post_title; ?></li>
                <?php endforeach; ?>
            </ul>
            <!-- .ordenar -->

            <form class="ordenacao" name="ordenacao" action="<?php echo remove_query_arg(array('tnb_user_action', 'mid', 'mtype')); ?>" method="post">
                <input type='hidden' name='tnb_user_action' value='save-order' />
                <input type="hidden" id="order_input" name="ordem" value="" />
                <p class="text-right">
                    <input type="button" class="cancelar-ordenacao grey" value="<?php _e('Cancelar', 'tnb'); ?>" />
                    <input type="submit" value="Salvar" class="salvar-ordencao submit" />
                </p>
            </form>
        </div>
        <!-- .ordenar-midias -->
    <?php endif; ?>
</section>
<!-- #music -->