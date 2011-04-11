<?php get_header(); ?>

<section id="not-found" class="grid_16 box-shadow">
    <div class="content">
        <h1><?php _e("página não encontrada", "tnb"); ?></h1>
        <p><?php _e("A página solicitada não foi encontrada. Realize uma busca para encontrar o conteúdo desejado.", "tnb"); ?></p>
        
        <form id="not-found-search-form" action="<?php echo site_url(); ?>">
            <input id="404_s" name="s" type="text" />
            <input type="submit" value="Pesquisar" class="button" />
            <input type="hidden" id="404_search_param1" value="" />
            <input type="hidden" id="404_search_param2" value="" />
            <input type="hidden" id="404_search_param3" value="" />
            <ul>
                <li><input type="radio" name="404_search_action" value="usuarios" id="404_search_action_usuarios" checked/> <label for="404_search_action_usuarios">universo TNB</label></li>
                <li><input type="radio" name="404_search_action" value="oportunidades" id="404_search_action_op" /> <label for="404_search_action_op">oportunidades</label></li>
                <li><input type="radio" name="404_search_action" value="blog" id="404_search_action_blog" /> <label for="404_search_action_blog">blog</label></li>
            </ul>
        </form>
        
    </div>
</section>

<?php get_footer(); ?>
