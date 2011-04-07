<?php if(!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Please do not load this page directly. Thanks!'); ?>
<?php if(post_password_required()) return; ?>
<?php
// add a microid to all the comments
function comment_add_microid($classes)
{
  $c_email = get_comment_author_email();
  $c_url = get_comment_author_url();
  if (!empty($c_email) && !empty($c_url)) {
    $microid = 'microid-mailto+http:sha1:' . sha1(sha1('mailto:'.$c_email).sha1($c_url));
    $classes[] = $microid;
  }
  return $classes;  
}

add_filter('comment_class','comment_add_microid');
?>

<section class="comments">
    <h3><?php _e("Comentários", "tnb"); ?></h3>
    <?php if ( have_comments() ) : ?>
        <h4><?php comments_number('Nenhum comentário', '1 comentário', '% comentários'); ?> | <a href="#respond" title="Coment">Comente &raquo;</a></h4>
        <ul class="comments-list">
            <?php wp_list_comments('callback=tnb_comment'); ?>
        </ul>
    <?php endif; ?>
    
    <?php if ( 'open' == $post->comment_status ) : ?>
        <div id="respond">
            <hr/>
            <h4><?php _e("Comente", "tnb"); ?> <small><?php cancel_comment_reply_link(' Cancelar') ?></small></h4>
        
            <?php if ( get_option( 'comment_registration' ) && !$user_ID ) : ?>
                <p>Você precisa estar <a href="<?php print get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logado</a> para fazer um comentário.</p>

            <?php else : ?>
                <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comment-form" class="clearfix">
                    <?php comment_id_fields(); ?>
                    <?php if($user_ID) : ?>
                        <p>Conectado como <a href="<?php print get_option('siteurl'); ?>/wp-admin/profile.php"><?php print $user_identity; ?></a>. <a href="<?php print get_option('siteurl'); ?>/wp-login.php?action=logout" title="Logout">Logout &raquo;</a></p>
                    <?php else : ?>
                        <div class="grid_3 clearfix">
                            <input class="text" type="text" name="author" id="author" onfocus="if (this.value == 'nome') this.value = '';" onblur="if (this.value == '') {this.value = 'Nome';}"  value="nome" size="22" tabindex="2" /><br />
                            <input class="text" type="text" name="email" id="email" onfocus="if (this.value == 'email') this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}" value="email" size="22" tabindex="3" /><br />
                            <input class="text" type="text" name="url" id="url" value="http://" size="22" tabindex="4" />
                        </div>
                    <?php endif; ?>
                    <div class="grid_6">
                        <textarea name="comment" id="comment" tabindex="1" onfocus="if (this.value == 'Insira seu comentário aqui.') this.value = '';" onblur="if (this.value == '') {this.value = 'Insira seu comentário aqui.';}">Insira seu comentário aqui.</textarea>
                    </div>
                    <p class="clear text-right">
                        <input type="submit" value="Enviar" class="submit" />
                    </p>

                    <?php if(get_option("comment_moderation") == "1") : ?>
                        Todos os comentários estão sujeitos a aprovação
                    <?php endif; ?>
                    <?php do_action('comment_form', $post->ID); ?>
                </form>
            </div>
        <?php endif; ?>
    <?php endif;  ?>
</section>