<?php global $vendo_perfil; ?>
<?php _pr('ESTE É O TNBRANCH PAGAMENTOS');?>
<header id="main-header" class="<?php if($vendo_perfil): ?>perfil-publico<?php else: ?>geral<?php endif; ?> grid_16 clearfix">
    <nav id="institutional">
        <ul>
            <li>
                <h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name') ?>">Toque no Brasil</a></h1>
                <?php wp_nav_menu(array("theme_location" => "institutional")); ?>
            </li>
        </ul>
    </nav>
    <!-- #institutional -->
    <nav id="main-menu">
        <?php wp_nav_menu(array("theme_location" => "main-menu")); ?>
    </nav>
    <!-- #main-menu -->
    <form id="searchform" method="get" action="<?php echo site_url(); ?>">
        <input id="s" name="s" type="text" />
        <input type="image" src="<?php echo get_theme_image("busca.png"); ?>" />
        <input type="hidden" id="search_param1" value="" />
        <input type="hidden" id="search_param2" value="" />
        <input type="hidden" id="search_param3" value="" />
        <ul id="search-options">
            <li><a class="search-option" id="search_usuarios">universo TNB</a></li>
            <li><a class="search-option" id="search_oportunidades">oportunidades</a></li>
            <li><a class="search-option" id="search_blog">blog</a></li>
        </ul>
    </form>
    <!-- #searchform -->

    <nav class="user-nav">
        <?php if( is_user_logged_in()):
            global $current_user;
        ?>
        
            <ul>
                <li class="username">
                    <a href="<?php echo get_author_posts_url($current_user->ID)?>" class="clearfix">
                        <div class="alignleft"><?php echo get_avatar($current_user->ID, 36); ?></div>
                        <div class="display-name alignleft"><?php echo $current_user->display_name; ?></div>
                    </a>
                
                    <ul class="usermenu">
                        <li><a href="<?php echo get_author_posts_url($current_user->ID)?>"><?php _e("Perfil Público", "tnb"); ?></a></li>
                        <?php if(current_user_can('delete_users')):?>
                            <li><a href="<?php echo admin_url(); ?>"><?php _e("Painel Admin", "tnb"); ?></a></li>
                        <?php endif;?>
                        <!-- <li><a href="">Customizar perfil</a></li> -->
                        <li><a href="<?php echo get_author_posts_url($current_user->ID)?>/editar/"><?php _e("Editar perfil", "tnb"); ?></a></li>
                        <li><a href="<?php echo get_author_posts_url($current_user->ID)?>/editar/musicas/"><?php _e("Carregar Músicas", "tnb"); ?></a></li>
                        <li><a href="<?php echo get_author_posts_url($current_user->ID)?>/editar/fotos/"><?php _e("Carregar Fotos", "tnb"); ?></a></li>
                        <li><a href="<?php echo get_author_posts_url($current_user->ID)?>/editar/videos/"><?php _e("Carregar Vídeos", "tnb"); ?></a></li>
                        <li><a href="<?php echo get_author_posts_url($current_user->ID)?>/editar/oportunidades"><?php _e("Gerenciar Oportunidades", "box"); ?></a></li>
                    </ul>
                </li>
                <li class="logout"><a href="<?php echo wp_logout_url(get_bloginfo('url')) ; ?>"><?php _e("Sair", "tnb"); ?></a></li>
            </ul>
        
        <?php else: ?>
            <div id="login">
                <?php
                  if($_GET['login_error']){
                    echo "<div class='error'>" ;
                    _e('Erro de login.','tnb');
                    echo " ";
                    echo "<a href='" . site_url('wp-login.php?action=lostpassword') . "'>";
                    _e('Esqueceu sua senha?','tnb');
                    echo "</a>";
                    _e("<div class='alignright close'>x</div>");
                    echo "</div>" ;
                  }
                  if($_GET['email_confirm']){

                    echo "<div class='error'>" ;
                    if($_GET['email_confirm'] == 'artista')
                        _e('Confirme seu e-mail para acessar sua conta.','tnb');
                    elseif($_GET['email_confirm'] == 'produtor')
                        _e('Acesso para produtores inativo.','tnb');
                    echo "</div>" ;
                  }
                  if($_GET['new_pass']){
                    echo "<div class='notice'>" ;
                    _e('Acesse seu email para o link de ativação.','tnb');
                    echo "</div>" ;
                  }
                ?>
            
                <form id="loginform"method="post" action="<?php bloginfo('url'); ?>/wp-login.php">
                    <input type="hidden" value="<?php echo preg_replace("/(login_error=1)?/", "", $_SERVER['REQUEST_URI']); ?>" name="redirect_to">
                    <input id="login" name="log" type="text" value="login" onfocus="if (this.value == 'login') this.value = '';" onblur="if (this.value == '') {this.value = 'login';}" />
                    <input id="password" name="pwd" type="password" value="senha" onfocus="if (this.value == 'senha') this.value = '';" onblur="if (this.value == '') {this.value = 'senha';}" />
                    <input type="submit" value="Entrar" />
                </form>
                <!-- #loginform -->
            </div>
            <!-- .login -->
        <?php endif; ?>
    </nav>
    <!-- .user-nav -->
</header>
<!-- #main-header -->
