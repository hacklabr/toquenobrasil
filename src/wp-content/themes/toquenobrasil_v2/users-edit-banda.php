
    <section id="band" class="content">
        <form method="post" enctype="multipart/form-data">
        
            <input type='hidden' name='tnb_user_action' value='edit-banda' />
            
            <p><?php _e("O KIT são as informações que serão avaliadas pelos produtores de oportunidades. Para completar o seu KIT, <strong>complete</strong> inicialmente os campos <strong>Origem da Banda</strong> e <strong>Residência da Banda</strong>. Eles servem para sua <strong>inscrição em oportunidades</strong>. <strong>Sem eles</strong> nenhuma outra informação poderá ser carregada.", "tnb"); ?></p>
            
            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Origem da Banda", "tnb"); ?></span>
            </h2>

            <div class="clearfix">
                <label>País</label>

                <select id='origem_pais' name="origem_pais">
                    <?php
                     
                        foreach($paises as $sigla=>$name){
                            echo "<option " . ($profileuser->origem_pais == $sigla ? 'selected':'') . " value='$sigla'>$name</option>";
                        }
                    ?>
                </select>

            </div>
            <div class="clearfix">
                <label>Estado</label>
                <select id="origem_estado_select" name="origem_estado_select" class='<?php echo $profileuser->origem_pais == 'BR' ? '' : 'hide' ?>' >
                     <?php
                        foreach($estados as $uf=>$name){
                            echo "<option " . ($profileuser->origem_estado == $uf ? 'selected':'') . " value='$uf'>$name</option>";
                        }
                    ?>
                </select>
                <input class="<?php echo $profileuser->origem_pais == 'BR' ? 'hide' : '' ?>" type="text" id="origem_estado_input" name="origem_estado_input" value="<?php echo $profileuser->origem_pais == 'BR' ? '' : $profileuser->origem_estado; ?>" />
                <input type="hidden" id="origem_estado" name="origem_estado" value="<?php echo $profileuser->origem_estado; ?>" />
            </div>
            <div class="clearfix">
                <label>Cidade</label>
                <select class="span-6 text <?php echo $profileuser->origem_pais == 'BR' ? '' : 'hide' ?>" id="origem_cidade_select" name="origem_cidade_select" ></select>
                <input class="span-6 text <?php echo $profileuser->origem_pais == 'BR' ? 'hide' : '' ?>" type="text" id="origem_cidade_input" name="origem_cidade_input" value="<?php echo $profileuser->origem_pais == 'BR' ? '' : $profileuser->origem_cidade; ?>" />
                <input type="hidden" name="origem_cidade" id="origem_cidade" value="<?php echo $profileuser->origem_cidade;?>"/>
                <?php /*if(!$usuarioOK && !tnb_getMunicipio($profileuser->origem_estado, $profileuser->origem_cidade)): ?>
                    <br/>
                    <span class="info">A cidade "<?php echo $profileuser->origem_cidade; ?>" não é válida, por favor selecione uma das cidades listadas abaixo.</span>
                <?php endif; */?>
            </div>

            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Residência da Banda", "tnb"); ?></span>
            </h4>

            <div class="clearfix">
                <label>País</label>
                <select id='banda_pais' name="banda_pais">
                     <?php
                        foreach($paises as $sigla=>$name){
                            echo "<option " . ($profileuser->banda_pais == $sigla ? 'selected':'') . " value='$sigla'>$name</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="clearfix">
                <label>Estado</label>
                <select id="banda_estado_select" name="banda_estado_select" class='<?php echo $profileuser->banda_pais == 'BR' ? '' : 'hide' ?>' >
                     <?php
                        foreach($estados as $uf=>$name){
                            echo "<option " . ($profileuser->banda_estado == $uf ? 'selected':'') . " value='$uf'>$name</option>";
                        }
                    ?>
                </select>
                <input class="<?php echo $profileuser->banda_pais == 'BR' ? 'hide' : '' ?>" type="text" id="banda_estado_input" name="banda_estado_input" value="<?php echo $profileuser->banda_pais == 'BR' ? '' : $profileuser->banda_estado; ?>" />
                <input type="hidden" id="banda_estado" name="banda_estado" value="<?php echo $profileuser->banda_estado; ?>" />
            </div>
            <div class="clearfix">
                <label>Cidade</label>
                <select class="<?php echo $profileuser->banda_pais == 'BR' ? '' : 'hide' ?>" id="banda_cidade_select" name="banda_cidade_select" ></select>
                <input class="<?php echo $profileuser->banda_pais == 'BR' ? 'hide' : '' ?>" type="text" id="banda_cidade_input" name="banda_cidade_input" value="<?php echo $profileuser->banda_pais == 'BR' ? '' : $profileuser->banda_cidade; ?>" />
                <input type="hidden" name="banda_cidade" id="banda_cidade" value="<?php echo $profileuser->banda_cidade;?>"/>
                <?php /*if(!$usuarioOK && !tnb_getMunicipio($profileuser->banda_estado, $profileuser->banda_cidade)): ?>
                    <br/>
                    <span class="info">A cidade "<?php echo $profileuser->banda_cidade; ?>" não é válida, por favor selecione uma das cidades listadas abaixo.</span>
                <?php endif; */?>
            </div>
            
            
            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Informações da Banda", "tnb"); ?></span>
            </h2>

            <div class="clearfix">
                <label><?php _e("Nome da Banda", "tnb"); ?></label>
                <input type="text" id="banda" name="banda" value="<?php echo htmlspecialchars($profileuser->display_name); ?>" />
            </div>
            <div class="clearfix">
                <label><?php _e("Integrantes", "tnb"); ?></label>
                <textarea  id="integrantes" name="integrantes"><?php echo htmlspecialchars($profileuser->integrantes); ?></textarea>
            </div>
            <div class="clearfix">
                <label><?php _e("Release", "tnb"); ?></label>
                <textarea  id="description" name="description"  ><?php echo $profileuser->description; ?></textarea>
            </div>
            <div class="clearfix">
                <label><?php _e("Rider", "tnb"); ?></label>
                <?php
                    $media = get_posts("post_parent=".$galeriasId['rider']->ID."&post_type=attachment&meta_key=_media_index&meta_value=rider_1&author={$user_ID}");
                    
                    if(isset($media[0])){
                        $media  = $media[0];
                    ?>
                        <div class="uploaded_file">
                            <a href="<?php echo wp_get_attachment_url($media->ID); ?>"><?php echo $media->post_title ?></a>
                            <label class='reset-label'><input type="checkbox"  name="delete_media[]" value="rider_<?php echo $media->ID; ?>" class="delete_profile_media" /> <?php _e("Deletar arquivo",'tnb'); ?></label>
                        </div>
                    <?php
                    }
                ?>
                <input type="file" id="rider" name="rider_1" value=""  />
                <br/>
                <span class="info"><?php _e("O arquivo deve ser em um dos seguintes formatos: PDF, ODT, DOC(x), JPG, PNG ou GIF.",'tnb');?></span>
            </div>
            <div class="clearfix">
                <label><?php _e("Mapa de Palco", "tnb"); ?></label>
                <?php
                    $media = get_posts("post_parent=".$galeriasId['mapa_palco']->ID."&post_type=attachment&meta_key=_media_index&meta_value=mapa_palco_1&author={$user_ID}");

                    if(isset($media[0])){
                        $media  = $media[0];
                    ?>
                        <div class="uploaded_file">
                            <a href="<?php echo wp_get_attachment_url($media->ID); ?>"><?php echo $media->post_title; ?></a>
                            <label class='reset-label'><input type="checkbox"  name="delete_media[]" value="mapa_palco_<?php echo $media->ID; ?>" class="delete_profile_media" /> <?php _e("Deletar arquivo");?></label>
                        </div>
                    <?php
                    }
                ?>
                <input type="file" id="mapa_palco" name="mapa_palco_1" value=""  />
                <br/>
                <span class="info"><?php _e("O arquivo deve ser em um dos seguintes formatos: PDF, ODT, DOC(x), JPG, PNG ou GIF.",'tnb');?></span>
            </div>
            
            
            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Contato", "tnb"); ?></span>
            </h2>
            
            <?php if (is_artista($profileuser->ID)): ?>
                <p><?php _e("As informações de contato estarão disponíveis <strong>apenas</strong> para os produtores do site. Eles as usarão para entrar em <strong>contato com você</strong>, portanto é <strong>vital que elas estejam corretas</strong>.", "tnb"); ?></p>
            <?php endif; ?>
            
            <div class="clearfix">
                <label><?php _e("Nome do Responsável", "tnb"); ?></label>
                <input type="text" id="responsavel" name="responsavel" value="<?php echo htmlspecialchars($profileuser->responsavel); ?>" />
            </div>
            <!-- .clearfix -->

            <div class="clearfix">
                <label><?php _e("E-mail", "tnb"); ?></label>
                <input type="text" id="email_publico" name="email_publico" value="<?php echo htmlspecialchars($profileuser->email_publico); ?>" />
                <span class="info"><?php _e("Este é o seu e-mail público, o qual os usuários do site utilizarão para se comunicar com você. Se for deixado em branco, a comunicação será realizada pelo seu e-mail de cadastro.", "tnb"); ?></span>
            </div>
            <!-- .clearfix -->

            <div class="clearfix">
                <label><?php _e("Telefone", "tnb"); ?></label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($profileuser->telefone); ?>" />
            </div>
            <!-- .clearfix -->
            
            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Estilos Musicais", "tnb"); ?></span>
            </h2>
            
            <div class="clearfix">
                <label>Seu estilo</label>
                <input type="text" name="estilo_musical_livre" value="<?php echo htmlspecialchars($profileuser->estilo_musical_livre); ?>" />
                <br/>
                <span class="info"><?php _e('Insira aqui o estilo musical que melhor caracteriza sua banda. Este item é opcional e não tem nenhuma restrição de conteúdo. Sinta-se livre para colocar o que quiser', 'tnb'); ?></span>
            </div>
            <div class="clearfix">
                <label><?php _e("Estilos padrão", "tnb"); ?></label>
                <?php $estilos = get_estilos_musicais(); ?>
                <?php $estilosSelecionados = get_user_meta($profileuser->ID, 'estilo_musical'); ?>
                <?php if (!is_array($estilosSelecionados)) $estilosSelecionados = array(); ?>
                <div class="style" id='estilos_div'>
                    <?php foreach ($estilos as $estilo): ?>                
                        <label class='reset-label'><input type="checkbox" value="<?php echo $estilo; ?>" name="estilo_musical[]" <?php if (in_array($estilo, $estilosSelecionados)) echo 'checked'; ?> > <?php echo $estilo; ?> </label><br />
                    <?php endforeach; ?>
                </div>
                <script type="text/javascript">
                    jQuery("#estilos_div input").change(function(){
                        if(jQuery("#estilos_div input:checked").length > 3){
                            jQuery(this).attr('checked',false);
                            alert("<?php _e("Escolha no máximo três estilos.","tnb");?>");
                        }
                    });
                </script>
                
                <span class="info"><?php _e('Selecione <strong>no máximo 3 estilos</strong> musicais nos quais sua banda se enquadra. Esses estilos não serão exibidos no seu perfil, mas servirão para <strong>filtros de inscrição em oportunidades</strong> e para <strong>buscas no site</strong>. *Se seu som não corresponde a nenhum desses estilos, entre em contato conosco via <a href="mailto:artista@toquenobrasil.com.br">artista@toquenobrasil.com.br</a>.', 'tnb'); ?></span>
            </div>
            
            
            <h2 class="section-title">
                <span class="bg-blue"><?php _e("Redes Sociais", "tnb"); ?></span>
            </h2>
            
            <div class="clearfix">
                <label>Facebook</label>
                <input type="text" name="facebook" value="<?php echo htmlspecialchars($profileuser->facebook); ?>" />
                <br/>
                <span class="info">O link deve possuir http://</span>
            </div>
            <div class="clearfix">
                <label>Twitter</label>
                <input type="text" name="twitter" value="<?php echo htmlspecialchars($profileuser->twitter); ?>" />
                <br/>
                <span class="info">O link deve possuir http://</span>
            </div>
            <div class="clearfix">
                <label>Orkut</label>
                <input type="text" name="orkut" value="<?php echo htmlspecialchars($profileuser->orkut); ?>" />
                <br/>
                <span class="info">O link deve possuir http://</span>
            </div>
            <div class="clearfix">
                <label>Vimeo</label>
                <input type="text" name="vimeo" value="<?php echo htmlspecialchars($profileuser->vimeo); ?>" />
                <br/>
                <span class="info">O link deve possuir http://</span>
            </div>
            <div class="clearfix">
                <label>YouTube</label>
                <input type="text" name="youtube" value="<?php echo htmlspecialchars($profileuser->youtube); ?>" />
                <br/>
                <span class="info">O link deve possuir http://</span>
            </div>

            <p class="clearfix text-right">
                <input type="submit" value="Salvar" class="submit" />
            </p>
        </form>
    </section>
    <!-- #band -->
