<?php global $user; ?>
<a href="<?php echo get_author_posts_url($user->ID)?>">
	<?php echo get_avatar($user->ID, 160); ?>
	<h3 class="title"><?php echo $user->display_name; ?></h3>
</a>

