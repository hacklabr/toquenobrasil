<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>
<div class="span-14 prepend-1 right-colborder">
	<p>Aqui você encontra festivais que estão buscando bandas novas! Inscreva-se clicando em "Quero Tocar!", seus dados serão automaticamente enviados ao produtor do evento.</p>
    <?php if ( have_posts() ) : the_post(); ?>
    
    <div class="post">
        <div class="post-time span-14">
            <div class="shadow"></div>
            <div class="quero-tocar">
                <a href="#">quero tocar!</a>                
            </div>
        </div>
        <!-- .post-time -->
        <h2 class="span-10">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
         </h2>
        <div id="thumb" class="span-4">
            <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail('eventos'); ?>
            <?php else : ?>
            <?php theme_image("thumb.png") ?>
            <?php endif; ?>
        </div><!-- .thumb -->
        <div class="span-10 last">
        	<div id="dados-do-evento">                        	
                <p><span class="labels">Data do evento:</span> <?php echo get_post_meta(get_the_ID(), "evento_data", true); ?><br />
                <span class="labels">Inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_inicio", true); ?><br />
                <span class="labels">Fim das inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_fim", true); ?><br />
                <span class="labels">Local:</span><?php echo get_post_meta(get_the_ID(), "eventos_local", true); ?><br />
                <span class="labels">Site:</span><?php echo get_post_meta(get_the_ID(), "eventos_site", true); ?><br />
                <span class="labels">Vagas:</span><?php echo get_post_meta(get_the_ID(), "eventos_vagas", true); ?><br />
                </p>
            </div><!-- .dados-do-evento --> 
            <?php the_excerpt(); ?>
            <div class="clear"></div>
            <div class="post-tags">
                <p>
                    <?php the_tags(" "," "," "); ?>
                </p>
            </div>
            <!-- .post-tags -->
            
        </div>        
        <div class="clear"></div>
        <div class="navigation">
            <div class="alignright">
                <?php next_post_link('%link', 'Próximo evento &raquo;', true); ?>
            </div>
            <div class="alignleft">
                <?php previous_post_link('%link', '&laquo; Evento anterior', true); ?>
            </div>
        </div>
        <!-- .navigation --> 
    </div>
    <!-- .post -->
    <?php endif; ?>
</div>
<?php get_sidebar("blog"); ?>
<?php get_footer(); ?>
