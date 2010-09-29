<?php
/*
Function Name: tnb_comment

*/

function tnb_comment($comment, $args, $depth)
{
  $GLOBALS['comment'] = $comment;  
?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
    	<div class="span-2"><?php echo get_avatar( $comment, 70 ); ?></div>
        <div class="span-12 last">    
            <p class="comment-meta alignright"><?php comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'])) ?> <?php edit_comment_link('Editar', '| ', ''); ?></p>    
            <p class="comment-meta">Por <cite><a target="_blank" href="<?php comment_author_url(); ?>"><?php comment_author(); ?></a></cite> em <?php comment_date(); ?> às <?php comment_time(); ?></p>
            <?php if($comment->comment_approved == '0') : ?><p>Seu comentário está aguardando moderação.</p><?php endif; ?>
            <?php comment_text(); ?>
        </div>
        <div class="clear"></div>     
    </li>
    <?php
}
?>
