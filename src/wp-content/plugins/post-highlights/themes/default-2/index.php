
<?php if ($highlightedPosts->have_posts()) : ?>

    <?php while ($highlightedPosts->have_posts()) : $highlightedPosts->the_post(); ?>
		<?php
		$headline = get_post_meta(get_the_ID(), 'ph_headline', true);
		$imageurl = $this->get_post_image();
		?>
		
		<div class="ph_post" id="ph_highlight-<?php echo $counter; ?>">
			
			<div class="ph_content">
	
				<h2><a class="ph_title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p><?php echo $headline; ?></p>
	
			</div>
			
			<div id="ph_picture-<?php echo $counter; ?>" class="ph_picture">
			  <a href="<?php echo the_permalink(); ?>">
			      <img src="<?php echo $imageurl; ?>" />
			  </a>
			</div>
		</div>
		
		<?php $counter ++; ?>
	<?php endwhile; ?>
	
	<div id="ph-description-background" class="ph-hide-while-loading"></div>
	
	<?php if ($counter > 2 ) : ?>
		<a id="ph-next-nav" class="ph-hide-while-loading"><?php _e('Next'); ?></a>
		<a id="ph-prev-nav" class="ph-hide-while-loading"><?php _e('Previous'); ?></a>
		<div id="ph-numeric-nav"></div>
    	<div id="ph-nav-background"></div>
    	
	<?php endif; ?>	
	
	<style>
		#posthighlights_container, #posthighlights_container .ph-canvas {
		    width: <?php echo $this->get_option('width'); ?>px;
		    height: <?php echo $this->get_option('height'); ?>px;
		}
		#posthighlights_container #ph-nav-background,
		#ph-description-background {
		    background:<?php echo $this->get_option('background_color'); ?>;
		}
	</style>

<?php endif; ?>
