<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>
<div class="span-14 prepend-1 right-colborder">
	<p>Aqui você encontra festivais que estão buscando bandas novas! Inscreva-se clicando em "Quero Tocar!", seus dados serão automaticamente enviados ao produtor do evento.</p>
    <?php if ( have_posts() ) : while (have_posts()) : the_post(); ?>
    
    <div class="post">
         <h2 class="span-14">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
         </h2>
         
        <!-- .post-time -->
        <div id="thumb" class="span-4">
            <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail('eventos'); ?>
            <?php else : ?>
            <?php theme_image("thumb.png") ?>
            <?php endif; ?>
        </div><!-- .thumb -->
        <div class="span-10 last">
        	<div id="dados-do-evento">                        	
                <p><span class="labels">Tipo de evento:</span> <?php echo get_post_meta(get_the_ID(), "evento_tipo", true); ?><br />
                <span class="labels">Data do evento:</span> <?php echo get_post_meta(get_the_ID(), "evento_data", true); ?><br />
                <span class="labels">Inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_inicio", true); ?><br />
                <span class="labels">Fim das inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_fim", true); ?><br />
                <span class="labels">Local:</span> <?php echo get_post_meta(get_the_ID(), "eventos_local", true); ?><br />
                <span class="labels">Site:</span> <?php echo get_post_meta(get_the_ID(), "eventos_site", true); ?><br />
                <span class="labels">Vagas:</span> <?php echo get_post_meta(get_the_ID(), "eventos_vagas", true); ?><br />
                </p>
            </div><!-- .dados-do-evento -->
        </div>
        <div class="span-14"> 
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
        <div class="quero-tocar">
            <a href="#">quero tocar!</a>
            <div class="shadow"></div>
        </div><!-- .quero-tocar -->
    </div>
    <!-- .post -->
	<?php endwhile; ?>
    	<div id="posts-navigation">
        	<?php previous_posts_link('<span id="anteriores"><span>Próximos eventos</span></span>'); ?>
            <?php next_posts_link('<span id="proximos"><span>Eventos anteriores</span></span>'); ?>            
        </div><!-- .navigation --> 
    <?php endif; ?>
</div>
<?php get_sidebar("blog"); ?>
<?php get_footer(); ?>
