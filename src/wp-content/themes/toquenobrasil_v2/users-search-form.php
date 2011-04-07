<?php 
global $wp_query; 
$reg_type = ($wp_query->query_vars['reg_type'] == 'artistas' || $wp_query->query_vars['reg_type'] == 'produtores') ? $reg_type : 'universo'; 
?>

<form id="search-users" action="<?php echo bloginfo('url')?>/universo">
    <input type='hidden' name='tnb_action' value='tnb_busca_usuarios' />
    <div class="clearfix bottom">
        <label for="usuario_nome">Nome</label>
        <input id='usuario_nome' name='user_name' type="text" class="text" value="<?php echo htmlentities(utf8_decode(stripslashes($_GET['user_name']))); ?>"/>

    </div>

    <div class="clearfix bottom">
        <label for="usuario_local">Local</label>
        <input id='usuario_local' name='user_local' type="text" class="text"  value="<?php echo htmlentities(utf8_decode(stripcslashes($_GET['user_local']))); ?>"/>
    </div>

    <div class="clearfix bottom">
        <label>Tipo</label>
        <label class="radio"><input type="radio" name="user_type" value="" <?php if($reg_type == 'universo') echo 'checked';?>> Todos</label>
        <label class="radio"><input type="radio"	name="user_type" value="artistas" <?php if($reg_type == 'artistas') echo 'checked';?>> Artistas</label>
        <label class="radio"><input type="radio" name="user_type" value="produtores" <?php if($reg_type == 'produtores') echo 'checked';?>> Produtores</label>
    </div>

    <div class="clearfix">
        <label for="usuario_estilo">Estilo</label>
        <div class="style">
            <?php $estilos = get_estilos_musicais(); ?>
            <?php $estilosSelecionados = $_GET['user_estilo']; ?>
            <?php $i = 0; // apenas para colocar ids e label for ?>
            <?php if (!is_array($estilosSelecionados)) $estilosSelecionados = array(); ?>
            <?php foreach ($estilos as $estilo): ?>
                <p class="bottom"><input type="checkbox" id="check_estilo_<?php echo $i; ?>" value="<?php echo $estilo; ?>" name="user_estilo[]" <?php if (in_array($estilo, $estilosSelecionados)) echo 'checked'; ?> > <label for="check_estilo_<?php echo $i; ?>" class="checkbox"><?php echo $estilo; ?></label></p>
                <?php $i ++; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <p class="text-right">
        <input type="image"	src="<?php echo get_theme_image("btn-search-opportunities.png"); ?>" class="btn-search" />
    </p>
</form>
<!-- #search-users -->
