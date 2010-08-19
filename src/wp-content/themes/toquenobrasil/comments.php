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

<div id="comentarios" class="span-14"> 
    <!--show the comments-->
    <div class="item yellow">
      <div class="title pull-1">
        <div class="shadow"></div>
        <h3>Comentários</h3>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>    
    <?php if(have_comments()) : ?>
    <h4 class="col-12 prepend-2"><?php comments_number('Nenhum comentário', '1 comentário', '% comentários' );?> | <a href="#respond" title="Comente">Comente &raquo;</a></h4>
    <ul class="commentlist" id="singlecomments">
        <?php wp_list_comments('callback=tnb_comment'); ?>
        <li class="clear"></li>
    </ul><!-- .commentlist #singlecomments -->
    <?php endif; ?>    
    <!--show the form-->
    <?php if('open' == $post-> comment_status) : ?>
    <div id="respond">    	
    	<div class="title">
            <div class="shadow"></div>
            <span><h4 class="no-margin">Comente</h4> <?php cancel_comment_reply_link('Cancelar') ?></span>
            <div class="clear"></div>
      	</div><!-- .title -->        
        <?php if(get_option('comment_registration') && !$user_ID) : ?>
        <p>Você precisa estar <a href="<?php print get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logado</a> para fazer um comentário.</p>
        <?php else : ?>
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="form-comentario" class="clearfix">
            <?php comment_id_fields(); ?>
            <div class="span-7">
                <textarea name="comment" id="comment" tabindex="1" onfocus="if (this.value == 'Insira seu comentário aqui.') this.value = '';" onblur="if (this.value == '') {this.value = 'Insira seu comentário aqui.';}">Insira seu comentário aqui.</textarea>
            </div>
            <div class="outros-campos span-4 last">
                <?php if($user_ID) : ?>
                    <p>Conectado como <a href="<?php print get_option('siteurl'); ?>/wp-admin/profile.php"><?php print $user_identity; ?></a>. <a href="<?php print get_option('siteurl'); ?>/wp-login.php?action=logout" title="Logout">Logout &raquo;</a></p>
                <?php else : ?>
                    <p>
                        <input type="text" name="author" id="author" onfocus="if (this.value == 'nome') this.value = '';" onblur="if (this.value == '') {this.value = 'Nome';}"  value="nome" size="22" tabindex="2" /><br />
                        <input type="text" name="email" id="email" onfocus="if (this.value == 'email') this.value = '';" onblur="if (this.value == '') {this.value = 'E-mail';}" value="email" size="22" tabindex="3" /><br />
                        <input type="text" name="url" id="url" value="http://" size="22" tabindex="4" />
                    </p>
                <?php endif; ?>
                               
                <div class="span-3 prepend-1 last">
                
              <input type="image" src="<?php echo get_theme_image("submit-comment.png"); ?>" value="Enviar" class="submit" />
            </div>
            </div>
            <?php if(get_option("comment_moderation") == "1") : ?>
            Todos os comentários estão sujeitos a aprovação
            <?php endif; ?>
            <?php do_action('comment_form', $post->ID); ?>
        </form>
        <?php endif; ?>
    </div><!-- #respond -->
    <?php endif; ?>
</div><!-- #comentarios -->
