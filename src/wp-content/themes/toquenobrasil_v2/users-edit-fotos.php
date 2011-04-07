<?php 
    $fotos = tnb_get_artista_fotos($profileuser->ID);
?>

<section id="photo" class="content clearfix">
    <form method='post' enctype="multipart/form-data" action="<?php echo remove_query_arg(array('tnb_user_action', 'mid', 'mtype')); ?>" >
        <input type='hidden' name='tnb_user_action' value='<?php echo $edit ? 'edit-image-save' : 'insert-image'; ?>' />

        <h2 class="section-title">
            <span class="bg-blue"><?php echo $edit ? __("Editar Foto", "tnb") : __("Nova Foto","tnb"); ?></span>
        </h2>

        <?php if (!$edit): ?>
            <div class="clearfix">
                <label><?php _e("Arquivo", "tnb"); ?></label>
                <input type="file" name="image" />
                <span class="info"><?php _e("O arquivo deve ser em um dos seguintes formatos: JPG, PNG ou GIF.");?></span>
            </div>
        <?php else: ?>
            <input type="hidden" name="mid" value="<?php echo $image_edit_id; ?>" />
            <?php echo wp_get_attachment_image($image_edit_id, 'thumbnail'); ?>
        <?php endif; ?>
        
        <div class="clearfix">
            <label><?php _e("Título da imagem", "tnb"); ?></label>
            <input type="text" name="image_title" value="<?php echo htmlspecialchars($image_edit_title); ?>" />
        </div>
        
        <div class="clearfix text-right">
            <?php if($edit): ?>
                <a href="<?php echo remove_query_arg(array('tnb_user_action', 'mid', 'mtype')); ?>"><?php _e("Cancelar",'tnb')?></a>
            <?php endif; ?>
            <input type="submit" value="<?php _e("Salvar",'tnb')?>" class="submit" />
        </div>
    </form>

    <?php if (!$edit): ?>
        <hr/>
        <div class="lista-midias">
            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Minhas Fotos", "tnb"); ?></span>
            </h2>
    
            <?php if(!$fotos) : ?>
                <hr/>
                <p class="text-center"><?php _e("Você ainda não subiu nenhum foto", "tnb"); ?></p>
                <hr/>
            <?php else : ?>
                <a class="comecar-ordenacao btn-yellow"><?php _e('Ordenar Fotos', 'tnb'); ?></a>
                <hr/>
                <?php foreach ($fotos as $foto): ?>
                    <div class="photo">
                        <?php echo wp_get_attachment_image($foto->ID, 'thumbnail'); ?>
                        <a href="?tnb_user_action=edit-image&mid=<?php echo $foto->ID; ?>">Editar</a> | <a class="apagar-media" href="?tnb_user_action=delete-media&mtype=images&mid=<?php echo $foto->ID; ?>">Apagar</a>
                    </div>
                    <!-- .photo -->
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="ordenar-midias">
        <h2 class="section-title">
            <span class="bg-blue"><?php _e("Ordenar Fotos", "tnb"); ?></span>
        </h2>

        <p class="text-center"><?php _e('Arraste e solte para ordenar', 'tnb'); ?></p>
    
        <ul class="ordenar">
            <?php foreach($fotos as $foto): ?>
                <li id="media_<?php echo $foto->ID; ?>" class="ordenar_foto_item"><?php echo wp_get_attachment_image($foto->ID, array(80,80)); ?><?php echo $foto->post_title; ?></li>
            <?php endforeach; ?>
        </ul>
        <!-- .ordenar -->

        <form class="ordenacao" name="ordenacao" action="<?php echo remove_query_arg(array('tnb_user_action', 'mid', 'mtype')); ?>" method="post">
            <input type='hidden' name='tnb_user_action' value='save-order' />
            <input type="hidden" id="order_input" name="ordem" value="" />        
            <p class="text-right">
                <input type="button" class="cancelar-ordenacao grey" value="<?php _e('Cancelar', 'tnb'); ?>" />
                <input type="submit" value="Salvar" class="salvar-ordenacao submit" />
            </p>
        </form>
    </div>
    <!-- .ordenar-midias -->
</section>
<!-- #photo -->