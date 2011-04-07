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
        <div id="ph_highlight-<?php echo $counter; ?>" class="ph_post grid_16 clearfix">

            <!-- Each post MUST have a div with ph_content class. Here is where all the information about the post goes -->
            <!-- Except the pictures -->
            <div class="ph_content grid_12 omega text-right">
                <!-- Put anything you like here, in any way you like -->
                <!-- You can use any of the Template Tags -->
                <h1 class="bottom"><a class="ph_title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                <p class="bottom"><?php echo $headline; ?></p>
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
    <div id="ph-description-background" class="ph-hide-while-loading grid_12 omega"></div>


    <?php if ($counter > 2 ) : ?>
        <div id="ph-numeric-nav"></div>
    <?php endif; ?>

    <!-- This is not part of the post-highlights itself -->
    <div id="ph-menu">
        <a href="<?php echo $this->get_option('iam-artist'); ?>"><?php theme_image("sou-artista.png", array('alt' => 'Sou Artista')); ?></a>
        <a href="<?php echo $this->get_option('iam-producer'); ?>"><?php theme_image("sou-produtor.png", array('alt' => 'Sou Produtor')); ?></a>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#posthighlights_container").addClass("grid_16 box-shadow");
        })
    </script>

<?php endif; ?>
