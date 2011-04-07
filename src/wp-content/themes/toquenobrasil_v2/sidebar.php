<aside class="grid_5">

	<?php if ( dynamic_sidebar('Sidebar Banner Institucional') ); ?>

    <?php if ( WPEB_countBanners('interna superior') ) : ?>
		<div class="widget-banner box-shadow">
			<?php WPEB_printBanner('interna superior')?>
		</div>
	<?php endif; ?>

    <?php if ( WPEB_countBanners('interna meio') ) : ?>
		<div class="widget-banner box-shadow">
			<?php WPEB_printBanner('interna meio')?>
		</div>
	<?php endif; ?>

    <?php if ( WPEB_countBanners('interna inferior') ) : ?>
		<div class="widget-banner box-shadow">
			<?php WPEB_printBanner('interna inferior')?>
		</div>
	<?php endif; ?>
	
    <?php if ( dynamic_sidebar('Sidebar Principal') ); ?>
</aside>
<!-- aside -->