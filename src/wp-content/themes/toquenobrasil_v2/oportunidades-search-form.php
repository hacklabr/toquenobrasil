<?php 
$acontece = ($_GET['acontece'] != 'proximo_mes' && $_GET['acontece'] != 'periodo' && $_GET['acontece'] != 'nao_importa') ? 'este_mes' : $_GET['acontece'];
$periodos = array(
    'este_mes' => 'Este mês',
    'proximo_mes' => 'Mês que vem',
    'nao_importa' => 'Não importa quando',
    'periodo' => 'de '.$_GET['acontece_de'].' até '.$_GET['acontece_ate']
);
?>

<form id="search-opportunities" class="clearfix">
    <input type='hidden' name='tnb_action' value='tnb_busca_oportunidades' />
    <div class="clearfix bottom">
        <label for="oportunidade_nome">Nome</label>
        <input type="text" id="oportunidade_nome" name='oportunidade_nome' value="<?php echo htmlentities(utf8_decode(stripcslashes($_GET['oportunidade_nome']))); ?>" class="bottom" />
    </div>
    <!-- .clearfix -->

    <div class="clearfix bottom">
        <label for="oportunidade_local">Local</label>
        <input type="text" id="oportunidade_local" name='oportunidade_local' value="<?php echo htmlentities(utf8_decode(stripcslashes($_GET['oportunidade_local']))); ?>" class="bottom" />
    </div>
    <!-- .clearfix -->

    <div class="clearfix bottom">
        <label>Acontece</label>
        <input type='hidden' name='acontece' id='acontece' value='<?php echo $acontece?>' />
        
        <div class="select">
            <div id='periodo_selecionado'><?php echo $periodos[$acontece];?></div>
            <ul class='select'>
                <li id='este_mes' >Este mês</li>
                <li id='proximo_mes' <?php echo $acontece == 'proximo_mes' ? 'class="selected"' : ''?>>Mês que vem</li>
                <li id='nao_importa' <?php echo $acontece == 'nao_importa' ? 'class="selected"' : ''?>>Não importa quando</li>
                <li id='periodo' class="clearfix<?php echo $acontece == 'periodo' ? ' selected' : ''?>">
                    Selecionar período:
                    <div class="clear"></div>

                    <input id="acontece_de" name='acontece_de' type="text"  value="<?php echo $_GET['acontece_de']; ?>" class="date bottom"/>
                    <input id="acontece_ate" name='acontece_ate' type="text" value="<?php echo $_GET['acontece_ate']; ?>" class="date bottom"/>
                    
                    <a id="ok_datas_search" value="ok">ok</a>

                </li>
            </ul>
        </div>
    </div>
    <!-- .clearfix -->

    <div class="clearfix bottom">
        <input type="checkbox" id="oportunidades_abertas" name='oportunidades_abertas' class="bottom" <?php echo isset($_GET['oportunidades_abertas']) ? 'checked="checked"' : ''?>/>
        <label for="oportunidades_abertas" class="checkbox">apenas com inscrições abertas</label>
    </div>
    <!-- .clearfix -->

    <div class="text-right">
        <input type="image" src="<?php echo get_theme_image("btn-search-opportunities.png"); ?>" class="btn-search" />
    </div>
</form>
<!-- #search-opportunities -->
