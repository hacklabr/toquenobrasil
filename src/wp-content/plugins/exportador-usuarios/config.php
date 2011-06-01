<?php
// prefixo que será usado nos slugs
global $wpdb, $wp_roles;
$euoptions = eu_getDefaultConfig();

$euconfig = eu_getConfig();
?>
<style>

.eu-field label{ width:auto; display: inline; vertical-align: auto;}
</style>

<h4><?php _e('Selecione as opções que devem aparecer na página de exportação de usuários.')?></h4>
<form method="post">
    <input type="hidden" name='<?php echo EU_PREFIX?>action' value='save-config' />
    <table>
        <tr>
            <td valign="top">
                <div class='eu-field'>
                    <h4><?php _e('Metadados do usuário','exportador-usuarios')?>:</strong></h4>
                    <?php foreach ($euoptions->metadata as $k => $n): if($k[0] != '_'):?>
                    <label><input type="checkbox" name='metadata[<?php echo $k?>]' value='<?php echo $k?>' <?php if(isset($euconfig->metadata[$k])) echo 'checked="checked"';?>/> <?php echo $k?> </label> 
                    <input type="text" name="metadata_desc[<?php echo $k?>]" value='<?php echo isset($euconfig->metadata[$k]) ? htmlentities(utf8_decode($euconfig->metadata[$k])) : htmlentities(utf8_decode($n))?>'> <br/>
                    <?php endif; endforeach;?>
                </div>
            </td>
            
            
            <td valign="top">
                <div class='eu-field'> 
                    <h4><?php _e('Capabilities','exportador-usuarios')?>:</strong></h4>
                    <?php foreach ($euoptions->roles as $k => $n): ?>
                    <label><input type="checkbox" name='roles[<?php echo $k?>]' value='<?php echo $k?>' <?php if(isset($euconfig->roles[$k])) echo 'checked="checked"';?>/> <?php echo $k?> </label> 
                    <input type="text" name="roles_desc[<?php echo $k?>]" value='<?php echo isset($euconfig->userdata[$k]) ? htmlentities(utf8_decode($euconfig->userdata[$k])) : htmlentities(utf8_decode($n))?>'> <br/>
                    <?php endforeach;?>
                </div>
                
                <div class='eu-field'>
                    <h4><?php _e('Campos do usuário','exportador-usuarios')?>:</strong></h4>
                    <?php foreach ($euoptions->userdata as $k => $n):?>
                    <label><input type="checkbox" name='userdata[<?php echo $k?>]' value='<?php echo $k?>' <?php if(isset($euconfig->userdata[$k])) echo 'checked="checked"';?>/> <?php echo $k?> </label> 
                    <input type="text" name="userdata_desc[<?php echo $k?>]" value='<?php echo isset($euconfig->userdata[$k]) ? htmlentities(utf8_decode($euconfig->userdata[$k])) : htmlentities(utf8_decode($n))?>'> <br/>
                    <?php endforeach;?>
                </div>
                
                <div class='eu-field'>
                    <h4><?php _e('Exportadores','exportador-usuarios')?>:</strong></h4>
                    <?php 
                        foreach ($euoptions->exportadores as $k):
                            eval("\$file_extension = {$k}::getFileExtension();");
                            eval("\$file_extensionDescription = {$k}::getFileExtensionDescription();");
                            $desc = ".$file_extension ($file_extensionDescription)";
                    ?>
                    <label><input type="checkbox" name='exportadores[<?php echo $k?>]' value='<?php echo $k?>' <?php if(in_array($k, $euconfig->exportadores)) echo 'checked="checked"';?>/> <?php echo $desc?> </label> <br />
                    <div style='margin:0px 0px 20px 55px;'>
                    <?php eval("\$file_extensionDescription = {$k}::printOptionsForm();"); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <br /> <br />
                <input type="submit" value="<?php _e('salvar configurações','exportador-usuarios')?>" />
            </td>
            
        </tr>
    </table>
    
</form>