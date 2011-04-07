
<!-- You will allway want to check if there are posts -->
<?php if ($highlightedPosts->have_posts()) : ?>

    <!-- Initialize the Loop -->
    <?php while ($highlightedPosts->have_posts()) : $highlightedPosts->the_post(); ?>
    
        <!-- From now on, its a standard WordPress Loop -->
        <?php
        // Get the postmeta information. 
		$headline = get_post_meta(get_the_ID(), 'ph_headline', true);
		$imageurl = $this->get_post_image();
		?>
		
		<!-- This is the main div. You MUST respect this ID standard -->
		<div class="ph_post" id="ph_highlight-<?php echo $counter; ?>">
			
			<!-- Each post MUST have a div with ph_content class. Here is where all the information about the post goes -->
			<!-- Except the pictures -->
			<div class="ph_content">
			
                <!-- Put anything you like here, in any way you like -->
                <!-- You can use any of the Template Tags -->
				<h2><a class="ph_title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p><?php echo $headline; ?></p>
			</div>
			
			<!-- This is the div where the pictures go -->
			<!-- All posts must have one of this, with ph_picture class, and follow this ID standard -->
			<div id="ph_picture-<?php echo $counter; ?>" class="ph_picture">
			  <a href="<?php echo the_permalink(); ?>">
			      <img src="<?php echo $imageurl; ?>" />
			  </a>
			</div>
		</div>
		
		<!-- Now you must increment the counter, just leave this line -->
		<?php $counter ++; ?>
		
	<!-- End of the Loop -->
	<?php endwhile; ?>
	
	<!-- Outside the Loop you can have anything else you want -->
	
	<!-- In this theme, we have a 50% opacity background for the text -->
	<!-- We add the ph-hide-while-loading class, so it will only show up when everything is loaded -->
	<!-- You can name elements here in anyway you like -->
	<div id="ph-description-background" class="ph-hide-while-loading"></div>
	
	
	<?php if ($counter > 2 ) : ?>
		<!-- If we have more than 1 post, we want to add the arrows -->
		<!-- Use these IDs and these elements will automatically have the behaviours added -->
		<!-- All you have to worry about is the look of your theme -->
		<a id="ph-next-nav" class="ph-hide-while-loading"></a>
		<a id="ph-prev-nav" class="ph-hide-while-loading"></a>
	<?php endif; ?>
	
	
	<!-- This theme has some settings -->
	<!-- We can easily get them with $this->get_option(option_name)  (dont forget the "$this->")-->
	
	<style>
		#posthighlights_container #ph-next-nav,
		#posthighlights_container #ph-prev-nav {
		    background-color: <?php echo $this->get_option('arrow_colors'); ?>;
		}
		#posthighlights_container, #posthighlights_container .ph-canvas {
		    width: <?php echo $this->get_option('width'); ?>px;
		    height: <?php echo $this->get_option('height'); ?>px;
		}
	</style>

<?php endif; ?>
