<?php $creatingSubEvent = is_numeric($_GET['post_parent']); ?>

<section id="opportunity" class="content clearfix">
    <?php if (!is_artista($profileuser->ID)): ?>

        <?php if (!$event->ID): ?>
            <div class="alignright">
                <a id="nova_oportunidade" class="btn-green">criar oportunidade</a>
            </div>
        <?php endif; ?>

        <form id="opportunity-form" enctype="multipart/form-data" method="post" <?php if (!$event->ID && !$creatingSubEvent) echo 'style="display:none;"'; ?> >
            <?php if ($event->ID): ?>
                <h2 class="section-title">
                    <span class="bg-blue"><?php _e("Editar","tnb"); ?> <?php echo $event->post_title; ?></span>
                </h2>
            <?php else: ?>
                <h2 class="section-title">
                    <span class="bg-blue"><?php _e("Nova Oportunidade","tnb"); ?></span>
                </h2>
            <?php endif; ?>
            
            <p><?php _e("Você tem que preencher pelo menos o nome e a descrição da sua oportunidade antes de criá-la.", "tnb"); ?></p>
            
            <?php if ($creatingSubEvent): ?>
                <p><?php _e('Para cadastrar uma oportunidade dentro desta oportunidade, você precisa apenas inserir um nome e uma descrição e salvar. Ela precisa ser aprovada pelo produtor da oportunidade a qual pertence. As demais informações podem ser completadas depois que sua oportunidade for aprovada.', 'tnb'); ?></p>
            <?php endif; ?>
            
            <h5 class="title"><?php _e("Informações Gerais", "tnb"); ?></h5>
            <div class="clear"></div>

            <div class="clearfix">
                <label><?php _e("Nome", "tnb"); ?></label>
                <input id="post_title" name="post_title" type="text" value="<?php echo stripslashes(htmlspecialchars($event->post_title));?>" />
            </div>
            <div class="clearfix">
                <label><?php _e("Descrição", "tnb"); ?></label>
                <textarea id="post_content" class="text" name="post_content"><?php echo stripslashes(htmlspecialchars($event->post_content));?></textarea>
            </div>
            
            <div class="clearfix">
                <label><?php _e("Oportunidade para:", "tnb"); ?></label>
                <select id="superevento" name="superevento">
                    <option value="no"><?php _e('Artistas', 'tnb');?></option>
                    <?php if($event_meta['superevento'] === 'yes'):?>
                        <option value="yes" selected="selected"><?php _e('Produtores', 'tnb');?></option>
                    <?php else: ?>
                        <option value="yes"><?php _e('Produtores', 'tnb');?></option>
                    <?php endif;?>
                </select>
                <span class="info">
                Se você escolher “produtores”, artistas não poderão se inscrever, apenas produtores poderão cadastrar oportunidades dentro desta oportunidade. Caso você não esteja seguro de usar essa opção, entre em contato conosco via contato@toquenobrasil.com.br 
                </span>
            </div>
            <div id="evento_pai" class="clearfix">
                <?php if($superevents): ?>
                    <label><?php _e("Cadastrar oportunidade em", "tnb"); ?></label>
                    <select name="post_parent">
                        <option value="0"><?php _e('Nenhum');?></option>
                        <?php foreach($superevents as $se): ?>
                            <option <?php echo $event->post_parent==$se->ID?'selected="selected" ':'';?>value="<?php echo $se->ID;?>"><?php echo $se->post_title;?></option>
                        <?php endforeach;?>
                    </select>
                <?php endif;?>
            </div>
            <div class="clearfix checkbox" id="produtores_selecionam">
                <input id="evento_produtores_selecionam" name="evento_produtores_selecionam" type="checkbox" value="1" <?php if ($event_meta['evento_produtores_selecionam'] == 1) echo 'checked' ?>/>
                <label><?php _e("Curadoria compartilhada", "tnb"); ?></label>
                <br />
                <span class="info"><?php _e("Selecionando essa opção os produtores cadastrados também poderão selecionar artistas para suas oportunidades. Se ela não for marcada, toda curadoria ficará sob sua responsabilidade", "tnb"); ?></span>
            </div>

            <div class="clearfix evento_tipo">
                <label><?php _e("Tipo de oportunidade", "tnb"); ?></label>
                <select id="evento_tipo">
                    <?php foreach($evento_tipos as $tipo):?>
                        <option value="<?php echo $tipo;?>"<?php echo $tipo==$event_meta['evento_tipo']?' selected="selected"':'';?>><?php echo $tipo;?></option>
                    <?php endforeach;?>
                    <option value="Outro"<?php echo !in_array($event_meta['evento_tipo'],$evento_tipos)?' selected="selected"':'';?>><?php _e('Outro');?></option>
                </select>
            </div>
            <div class="clearfix" id="outro_evento_tipo" style="display:none">
                <label><?php _e("Qual o tipo da oportunidade?", "tnb"); ?></label>
                <input type="hidden" id="evento_tipo_original" value="<?php echo $event_meta['evento_tipo'];?>"/>
                <input type="text" class="text" name="evento_tipo" value="<?php echo stripslashes(htmlspecialchars($event_meta['evento_tipo']));?>"/>
            </div>
            
            <div class="clearfix">
                <label><?php _e("Status", "tnb"); ?></label>
                <input type="radio" name="post_status" value="publish" <?php if ($event->post_status == 'publish') echo "checked"; ?> /> <?php _e("Ativo", "tnb"); ?>
                <input type="radio" name="post_status" value="draft" <?php if ($event->post_status == 'draft') echo "checked"; ?> /> <?php _e("Inativo (Uma oportunidade inativa não é divulgada no site)", "tnb"); ?>
            </div>
            <div class="clearfix">
                <label><?php _e("Site", "tnb"); ?></label>
                <input id="evento_site" name="evento_site" type="text" value="<?php echo $event_meta['evento_site'];?>" />
            </div>
            <div class="clearfix">
                <label><?php _e("Banner", "tnb"); ?></label>
                <?php if($images['evento_avatar']): ?>
                    <?php if ($event_meta['superevento'] == 'yes') : ?>
                        <?php echo wp_get_attachment_image($images['evento_avatar'], 'banner-horizontal');?>
                    <?php else: ?>
                        <?php echo wp_get_attachment_image($images['evento_avatar'], 'thumbnail');?>
                    <?php endif; ?>
                    <div class="checkbox">
                        <input type="checkbox" id="excluir_evento_avatar" name="excluir_evento_avatar" value="0"/>
                        <label for="excluir_evento_avatar">Apagar imagem?</label>
                    </div>
                    <br/>
                <?php endif;?>
                <input id="evento_avatar" class="text" name="evento_avatar" type="file" />
                <br/>
                <span class="info">
                    <?php _e("largura máxima", "tnb"); ?> - 528px
                </span>
            </div>
            

            <hr/>

            <h5 class="title"><?php _e("Data da oportunidade", "tnb"); ?></h5>
            <div class="clear"></div>

            <div class="clearfix">
                <label><?php _e("Início", "tnb"); ?></label>
                <input id="evento_inicio" class="date" name="evento_inicio" type="text" value="<?php echo $event_meta['evento_inicio'];?>" />
            </div>
            <div class="clearfix">
                <label><?php _e("Fim", "tnb"); ?></label>
                <input id="evento_fim" class="date" name="evento_fim" type="text" value="<?php echo $event_meta['evento_fim'];?>" />
            </div>

            <hr/>

            <h5 class="title"><?php _e("Inscrições", "tnb"); ?></h5>
            <div class="clear"></div>

            <div class="clearfix">
                <label><?php _e("Início", "tnb"); ?></label>
                <input id="evento_inscricao_inicio" class="date" name="evento_inscricao_inicio" type="text" value="<?php echo $event_meta['evento_inscricao_inicio'];?>" />
            </div>
            <div class="clearfix">
                <label><?php _e("Fim", "tnb"); ?></label>
                <input id="evento_inscricao_fim" class="date" name="evento_inscricao_fim" type="text" value="<?php echo $event_meta['evento_inscricao_fim'];?>" />
                
            </div>
            <div class="clearfix">
                <label><?php _e("Número de Vagas", "tnb"); ?></label>
                <input id="evento_vagas" name="evento_vagas" type="text" value="<?php echo $event_meta['evento_vagas'];?>" />
            </div>

            <hr/>

            <h5 class="title"><?php _e("Condições e Restrições", "tnb"); ?></h5>
            <div class="clear"></div>

            <div class="clearfix" id="evento_condicoes_div">
                <label><?php _e("Condições", "tnb"); ?></label>
                
                <?php if ($event->ID && ($event->post_parent == 0 || !$parent_event->forcar_condicoes) && is_string($event_meta['evento_condicoes']) ) : ?>
                    <textarea <?php echo $parent_event->forcar_condicoes?'disabled="disabled" ':'';?>id="evento_condicoes" name="evento_condicoes"><?php echo htmlspecialchars($event_meta['evento_condicoes']);?></textarea>
                <?php else: ?>
                    <div class="conditions">
                        <table class="bottom">
                            <tr>
                                <td>Hospedagem</td>
                                <td><input type="radio" value="1" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if ($event_meta['evento_condicoes']['hospedagem'] == '1') echo 'checked'; ?> name="evento_condicoes[hospedagem]"> Sim</td>          
                                <td><input type="radio" value="0" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if ($event_meta['evento_condicoes']['hospedagem'] == '0') echo 'checked'; ?> name="evento_condicoes[hospedagem]"> Não</td>
                            </tr>
                            
                            <tr>
                                <td>Alimentação</td>
                                <td><input type="radio" value="1" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if ($event_meta['evento_condicoes']['alimentacao'] == '1') echo 'checked'; ?> name="evento_condicoes[alimentacao]"> Sim</td>         
                                <td><input type="radio" value="0" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if ($event_meta['evento_condicoes']['alimentacao'] == '0') echo 'checked'; ?> name="evento_condicoes[alimentacao]"> Não</td>
                            </tr>
                            <tr>
                                <td>Transporte local</td>
                                <td><input type="radio" value="1" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if ($event_meta['evento_condicoes']['transporte_local'] == '1') echo 'checked'; ?> name="evento_condicoes[transporte_local]"> Sim</td>
                                <td><input type="radio" value="0" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if ($event_meta['evento_condicoes']['transporte_local'] == '0') echo 'checked'; ?> name="evento_condicoes[transporte_local]"> Não</td>
                            </tr>
                            <tr>
                                <td>Transporte entre cidades</td>
                                <td><input type="radio" value="1" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if   ($event_meta['evento_condicoes']['transporte_cidades'] == '1') echo 'checked'; ?> name="evento_condicoes[transporte_cidades]"> Sim</td>
                                <td><input type="radio" value="0" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if ($event_meta['evento_condicoes']['transporte_cidades'] == '0') echo 'checked'; ?> name="evento_condicoes[transporte_cidades]"> Não</td>
                            </tr>
                            <tr>
                                <td>Cache</td>
                                <td><input type="radio" value="1" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if ($event_meta['evento_condicoes']['cache'] == '1') echo 'checked'; ?> name="evento_condicoes[cache]"> Sim</td>
                                <td><input type="radio" value="0" <?php if ($parent_event->forcar_condicoes) echo 'disabled '; if ($event_meta['evento_condicoes']['cache'] == '0') echo 'checked'; ?> name="evento_condicoes[cache]"> Não</td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>
                <div class="checkbox">
                    <span class="forced" style="display:none">Usando Condições do Evento pai</span>
                    <br />
                    <input <?php echo $event_meta['forcar_condicoes']?'checked="checked" ':'';?>type="checkbox" id="forcar_condicoes" name="forcar_condicoes" class="forcar"/>
                    <label for="forcar_condicoes" class="forcar"><?php _e('Forçar sub-eventos a usarem as mesmas condições', 'tnb');?></label>
                </div>
            </div>
            <div class="clearfix" id="evento_restricoes_div">
                <label><?php _e("Restrições", "tnb"); ?></label>
                <textarea <?php echo $parent_event->forcar_restricoes?'disabled="disabled" ':'';?>id="evento_restricoes" name="evento_restricoes"><?php echo htmlspecialchars($event_meta['evento_restricoes']);?></textarea>
                <div class="checkbox">
                    <span class="forced" style="display:none">Usando Restrições do Evento pai</span>
                    <br />
                    <input <?php echo $event_meta['forcar_restricoes']?'checked="checked" ':'';?>type="checkbox" id="forcar_restricoes" name="forcar_restricoes" class="forcar"/>
                    <label for="forcar_restricoes" class="forcar"><?php _e('Forçar sub-eventos a usarem as mesmas restrições:');?></label>
                    
                </div>
            </div>
            <div class="clearfix" id="evento_tos_div">
                <label><?php _e("Termos", "tnb"); ?></label>
                <textarea <?php echo $parent_event->forcar_tos?'disabled="disabled" ':'';?>id="evento_tos" name="evento_tos"><?php echo $event_meta['evento_tos'];?></textarea>
                <div class="checkbox">
                    <span class="forced" style="display:none">Usando Termos do Evento pai</span>
                    <br/>
                    <input <?php echo $event_meta['forcar_tos']?'checked="checked" ':'';?>type="checkbox" id="forcar_tos" name="forcar_tos" class="forcar"/>
                    <label for="forcar_tos" class="forcar"><?php _e('Forçar sub-eventos a usarem os mesmos termos:');?></label>
                </div>
            </div>

            <hr/>
            <h5 class="title"><?php _e("Filtro", "tnb"); ?></h5><br />
            <?php $estilos = get_estilos_musicais(); ?>
            
            <p><?php _e('Utilize estes campos para restringir as inscrições às bandas que se enquadrem aos itens selecionados abaixo. Para permitir todos, não selecione nada.','tnb');?></p>
            
            <div id="evento_filtro_origem_div" class='clearfix'>
                <label><?php _e('Local de Origem','tnb'); ?></label>
                <select id='evento_filtro_origem_pais' name='evento_filtro_origem_pais'>
                    <option value=''><?php _e('sem restrição', 'tnb'); ?></option>
                <?php foreach(get_paises() as $sigla=>$pais):?>
                    <option value="<?php echo $sigla; ?>" <?php if($event_meta['evento_filtro_origem_pais'] == $sigla) echo 'selected="selected"'?>><?php echo $pais; ?></option>
                <?php endforeach;?>
                </select>
                <div class='oportunidade-filtro'>
                <?php foreach($estados as $uf => $name): if($uf): ?>
                    <label class='reset-label'><input type="checkbox" name="evento_filtro_origem_uf[]" value="<?php echo $uf?>" <?php if(in_array($uf, $event_meta['evento_filtro_origem_uf'])) echo 'checked="checked" ';?>/> <?php echo $name;?></label><br />
                <?php endif; endforeach;?>
                </div>
            </div>
                
            <div id="evento_filtro_residencia_div" class="clearfix">
                <label><?php _e('Local de Residência','tnb'); ?></label>
                <select id='evento_filtro_residencia_pais' name='evento_filtro_residencia_pais'>
                    <option value=''><?php _e('sem restrição', 'tnb'); ?></option>
                <?php foreach(get_paises() as $sigla=>$pais):?>
                    <option value="<?php echo $sigla; ?>" <?php if($event_meta['evento_filtro_residencia_pais'] == $sigla) echo 'selected="selected"'?>><?php echo $pais; ?></option>
                <?php endforeach;?>
                </select>
                <div class='oportunidade-filtro'>
                <?php foreach($estados as $uf => $name): if($uf): ?>
                    <label class='reset-label'><input type="checkbox" name="evento_filtro_residencia_uf[]" value="<?php echo $uf?>" <?php if(in_array($uf, $event_meta['evento_filtro_residencia_uf'])) echo 'checked="checked" ';?>/> <?php echo $name;?></label><br />
                <?php endif; endforeach;?>
                </div>
            </div>

            <div id="evento_filtro_estilo_div" class="clearfix">
                <label><?php _e('Estilo Musical','tnb'); ?></label>
                <div class='oportunidade-filtro'>
                <?php foreach($estilos as $estilo): ?>
                    <label class='reset-label'><input type="checkbox" name="evento_filtro_estilo[]" value="<?php echo htmlentities(utf8_decode($estilo)); ?>" <?php if(in_array($estilo, $event_meta['evento_filtro_estilo'])) echo 'checked="checked" ';?>/> <?php echo $estilo;?></label><br />
                <?php endforeach;?>
                </div>
                </div>

            <hr />
            <h5 class="title"><?php _e("Local", "tnb"); ?></h5>
            <div class="clear"></div>

            <div class="clearfix">
                <label><?php _e("Estabelecimento", "tnb"); ?></label>
                <input id="evento_local" name="evento_local" type="text" value="<?php echo stripslashes(htmlspecialchars($event_meta['evento_local']));?>" />
            </div>            
            <div class="clearfix">
                <label><?php _e("País", "tnb"); ?></label>
                <select name="evento_pais" id='evento_pais' class="span-3">
                    <?php
                        foreach($paises as $sigla=>$name){
                            echo "<option " . ($event_meta['evento_pais'] == $sigla ? 'selected':'') . " value='$sigla'>$name</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="clearfix">
                <label><?php _e("Estado", "tnb"); ?></label>
                <select id="evento_estado_select" name="evento_estado_select" class="<?php echo $event_meta['evento_pais'] == 'BR' ? 'hide' : '' ?>" class="span-4">
                    <?php
                        foreach($estados as $uf=>$name){
                            echo "<option " . ($event_meta['evento_estado'] == $uf ? 'selected':'') . " value='$uf'>$name</option>";
                        }
                    ?>
                </select>
                
                <input class="<?php echo $event_meta['evento_pais'] == 'BR' ? 'hide' : '' ?>" type="text" id="evento_estado_input" name="evento_estado_input" value="<?php echo $event_meta['evento_pais'] == 'BR' ? '' : stripslashes(htmlspecialchars($event_meta['evento_estado'])); ?>" />
			    <input type="hidden" id="evento_estado" name="evento_estado" value="<?php echo stripslashes(htmlspecialchars($event_meta['evento_estado'])); ?>" />
                
            </div>
            <div class="clearfix">
                <label><?php _e("Cidade", "tnb"); ?></label>
                
                <select class="<?php echo $event_meta['evento_pais'] == 'BR' ? '' : 'hide' ?>" id="evento_cidade_select" name="evento_cidade_select" ></select>
                
                <input class="<?php echo $event_meta['evento_pais'] == 'BR' ? '' : 'hide' ?>" id="evento_cidade_input" class="text" name="evento_cidade_input" type="text" value="<?php echo stripslashes(htmlspecialchars($event_meta['evento_cidade']));?>" />
                <input type="hidden" name="evento_cidade" id="evento_cidade" value="<?php echo stripslashes(htmlspecialchars($event_meta['evento_cidade'])); ?>"/>
            </div>

            <hr/>

            <h5 class="title"><?php _e("Patrocinadores", "tnb"); ?></h5>
            <div class="clear"></div>

            <div class="clearfix">
                <label><?php _e("Logo do primeiro patrocinador", "tnb"); ?></label>
                <?php if($images['evento_patrocinador1']):?>
                    <?php echo wp_get_attachment_image($images['evento_patrocinador1'], 'banner-horizontal');?>
                    <label for="excluir_evento_patrocinador1">Apagar imagem?</label>
                    <input type="checkbox" id="excluir_evento_patrocinador1" name="excluir_evento_patrocinador1" value="0"/>
                    <br/>
                <?php endif;?>
                <input id="evento_patrocinador1" class="text alignleft" name="evento_patrocinador1" type="file" /> <span>(<?php _e("largura máxima",'tnb'); ?> - 550px)</span>
            </div>
            <div class="clearfix">
                <label><?php _e("Logo do segundo patrocinador", "tnb"); ?></label>
                <?php if($images['evento_patrocinador2']):?>
                    <?php echo wp_get_attachment_image($images['evento_patrocinador2'], 'banner-horizontal');?>
                    <label for="excluir_evento_patrocinador2">Apagar imagem?</label>
                    <input type="checkbox" id="excluir_evento_patrocinador2" name="excluir_evento_patrocinador2" value="0"/>
                    <br/>
                <?php endif;?>
                <input id="evento_patrocinador2" class="text alignleft" name="evento_patrocinador2" type="file" /> <span>(<?php _e("largura máxima",'tnb'); ?> - 550px)</span>
            </div>
            <div class="clearfix">
                <label><?php _e("Logo do terceiro patrocinador", "tnb"); ?></label>
                <?php if($images['evento_patrocinador3']):?>
                    <?php echo wp_get_attachment_image($images['evento_patrocinador3'], 'banner-horizontal');?>
                    <label for="excluir_evento_patrocinador3">Apagar imagem?</label>
                    <input type="checkbox" id="excluir_evento_patrocinador3" name="excluir_evento_patrocinador3" value="0"/>
                    <br/>
                <?php endif;?>
                <input id="evento_patrocinador3" class="text alignleft" name="evento_patrocinador3" type="file" /> <span>(<?php _e("largura máxima",'tnb'); ?> - 550px)</span>
            </div>

            <?php if( ! property_exists($event, 'ID')):?>
                <div class='tnb_modal' id='tnb_modal_cadastro_evento'>
                    <h2><?php _e('Termo de Responsabilidade','tnb'); ?></h2>
                    <p><?php echo nl2br($options['tnb_termo_para_novo_evento']); ?></p>
                    <p class="text-center bottom">
                        <a href="#" class="btn-grey" id="aceito_termo_para_novo_evento"><?php _e('Li e aceito o termo', 'tnb');?></a>
                    </p>
                </div>
                <input type="hidden" name="termo_para_novo_evento" id="termo_para_novo_evento" value="0"/>
            <?php endif;?>
            
            <hr/>

            <p class="text-right">
                <input type="button" value="Cancelar" class="grey" onClick="document.location.href='<?php echo get_author_posts_url($current_user->ID)?>/editar/oportunidades'" />
                <input type="submit" value="Salvar" />
            </p>
        </form>

        <?php if (!$event->ID) include('users-edit-oportunidades-list.php'); ?>

    <?php else: ?>

        <?php include('users-edit-oportunidades-list-artistas.php'); ?>

    <?php endif; // !is_artista() ?>

    </section>
    <!-- #opportunity -->

</section>
<!-- #profile -->

<script type="text/javascript">
    /*
    var today = new Date(<?php echo date('Y').','.(date('m')-1).','.date('d')?>);
    jQuery('input.date').keydown(function(){return false;});
    
    jQuery('input.date#evento_inicio').datepicker({
        onSelect: function(date, inst){
            var selectedDate = jQuery(this).datepicker("getDate");
            
            if(selectedDate > today){
            	jQuery('input.date#evento_fim').datepicker( "option", "minDate", selectedDate );
            }else{
            	jQuery('input.date#evento_fim').datepicker( "option", "minDate",  today);
            }
            
        }
    });
    
    jQuery('input.date#evento_fim').datepicker({
        minDate: today,
        onSelect: function (date, inst){
        	var selectedDate = jQuery(this).datepicker("getDate");
        	jQuery('input.date#evento_inicio').datepicker( "option", "maxDate", selectedDate );
        	jQuery('input.date#evento_inscricao_inicio').datepicker( "option", "maxDate", selectedDate );
        	jQuery('input.date#evento_inscricao_fim').datepicker( "option", "maxDate", selectedDate );
        }
    });

    jQuery('input.date#evento_inscricao_inicio').datepicker({
        onSelect: function(date, inst){
            var selectedDate = jQuery(this).datepicker("getDate");
            
            if(selectedDate > today){
            	jQuery('input.date#evento_inscricao_fim').datepicker( "option", "minDate", selectedDate );
            }else{
            	jQuery('input.date#evento_inscricao_fim').datepicker( "option", "minDate",  today);
            }
            
        }
    });
    
    jQuery('input.date#evento_inscricao_fim').datepicker({
        minDate: today,
        onSelect: function (date, inst){
            
        }
    });
    */

    var today = new Date(<?php echo date('Y').','.(date('m')-1).','.date('d')?>);
    jQuery('input.date').keydown(function(){return false;});
    
    jQuery('input.date#evento_inicio').datepicker({
        onSelect: function(date, inst){
            var selectedDate = jQuery(this).datepicker("getDate");
            
            jQuery('input.date#evento_fim').datepicker( "option", "minDate", selectedDate );
            
        }
    });
    
    jQuery('input.date#evento_fim').datepicker({
        
        onSelect: function (date, inst){
        	var selectedDate = jQuery(this).datepicker("getDate");
        	jQuery('input.date#evento_inicio').datepicker( "option", "maxDate", selectedDate );
        	jQuery('input.date#evento_inscricao_inicio').datepicker( "option", "maxDate", selectedDate );
        	jQuery('input.date#evento_inscricao_fim').datepicker( "option", "maxDate", selectedDate );
        }
    });

    jQuery('input.date#evento_inscricao_inicio').datepicker({
        onSelect: function(date, inst){
            var selectedDate = jQuery(this).datepicker("getDate");
            jQuery('input.date#evento_inscricao_fim').datepicker( "option", "minDate", selectedDate );
            
        }
    });
    
    jQuery('input.date#evento_inscricao_fim').datepicker({
        
        onSelect: function (date, inst){
            
        }
    });
    
</script>
