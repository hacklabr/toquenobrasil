<section id="information" class="content">
    <form method="post" enctype="multipart/form-data">
        <input type='hidden' name='tnb_user_action' value='edit-produtor' />
        
        <p><?php _e("Para criar oportunidades você precisa definir a sua residência e inserir um CPF ou CNPJ válido.", "tnb"); ?></p>
        
        <h2 class="section-title">
            <span class="bg-blue"><?php _e("Informações do Produtor", "tnb"); ?></span>
        </h2>

        <div class="clearfix">
            <label><?php _e("Nome do Produtor", "tnb"); ?></label>
            <input type="text" id="nome_produtor" name="nome_produtor" value="<?php echo htmlspecialchars($profileuser->display_name); ?>" />
        </div>
        <!-- .clearfix -->
        
        <div class="clearfix">
            <label><?php _e("CPF", "tnb"); ?></label>
            <input type="text" id="cpf" name="cpf" value="<?php echo $profileuser->cpf; ?>"  />
        </div>
        <!-- .clearfix -->
        
        <div class="clearfix">
            <label><?php _e("CNPJ", "tnb"); ?></label>
            <input type="text" id="cnpj" name="cnpj" value="<?php echo $profileuser->cnpj; ?>" />
        </div>
        <!-- .clearfix -->

        <hr/>
        
        <h2 class="section-title">
            <span class="bg-blue"><?php _e("Informações de Contato", "tnb"); ?></span>
        </h2>
        
        <div class="clearfix">
            <label><?php _e("Nome do Responsável", "tnb"); ?></label>
            <input type="text" id="responsavel" name="responsavel" value="<?php echo htmlspecialchars($profileuser->responsavel); ?>" />
        </div>
        <!-- .clearfix -->

        <div class="clearfix">
            <label><?php _e("E-mail", "tnb"); ?></label>
            <input type="text" id="email_publico" name="email_publico" value="<?php echo htmlspecialchars($profileuser->email_publico); ?>" />
            <span class="info"><?php _e("Este será o seu e-mail público, o qual as pessoas utilizarão para se comunicar com você. Se for deixado em branco, a comunicação será realizada pelo seu e-mail de cadastro.", "tnb"); ?></span>
        </div>
        <!-- .clearfix -->

        <div class="clearfix">
            <label><?php _e("Telefone", "tnb"); ?></label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($profileuser->telefone); ?>" />
        </div>
        <!-- .clearfix -->
        
        <h2 class="section-title">
            <span class="bg-blue"><?php _e("Residência", "tnb"); ?></span>
        </h2>

        <div class="clearfix">
            <label><?php _e("País", 'tnb')?></label>
            <select id='origem_pais' name="origem_pais">
                <?php
                    foreach($paises as $sigla=>$name) {
                        echo "<option " . ($profileuser->origem_pais == $sigla ? 'selected':'') . " value='$sigla'>$name</option>";
                    }
                ?>
            </select>
        </div>
        <!-- .clearfix -->

        <div class="clearfix">
            <label><?php _e("Estado", 'tnb')?></label>
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
        <!-- .clearfix -->

        <div class="clearfix">
            <label><?php _e("Cidade", 'tnb')?></label>
            <select class="span-6 text <?php echo $profileuser->origem_pais == 'BR' ? '' : 'hide' ?>" id="origem_cidade_select" name="origem_cidade_select" ></select>
            <input class="span-6 text <?php echo $profileuser->origem_pais == 'BR' ? 'hide' : '' ?>" type="text" id="origem_cidade_input" name="origem_cidade_input" value="<?php echo $profileuser->origem_pais == 'BR' ? '' : $profileuser->origem_cidade; ?>" />
            <input type="hidden" name="origem_cidade" id="origem_cidade" value="<?php echo $profileuser->origem_cidade;?>"/>
            <?php /*if(!$usuarioOK && !tnb_getMunicipio($profileuser->origem_estado, $profileuser->origem_cidade)): ?>
                <br/>
                <span class="info">A cidade "<?php echo $profileuser->origem_cidade; ?>" não é válida, por favor selecione uma das cidades listadas abaixo.</span>
            <?php endif; */?>
        </div>
        <!-- .clearfix -->


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
        <div class="clearfix text-right">
            <input type="submit" value="Salvar" class="submit" />
        </div>
        <!-- .clearfix -->
    </form>
</section>
<!-- #band -->
