<?php global $widget_group; ?>
<div class='public-profile clearfix'>
    <div class='grid_8'><?php $widget_group->containers['left']->__print(); ?></div>
    <div class='grid_8'>

        <?php if ( WPEB_countBanners('Perfil de usuário') ) : ?>
            <div class="box-shadow banner-perfil-publico">
                    <?php WPEB_printBanner('Perfil de usuário');?>
            </div>        
        <?php endif; ?>   
        
        <?php $widget_group->containers['right']->__print(); ?>
    </div>
</div>
<?php 

