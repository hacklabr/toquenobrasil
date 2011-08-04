<?php 
global $wpdb;

$euconfig = eu_getConfig();

?>
<style>
.eu-field { float:left; margin:10px;}
</style>
<form method="post">
    <input type="hidden" name='<?php echo EU_PREFIX?>action' value='export-users' />
    <div>
        <select name='filter'>
            <option value=''><?php _e('não filtrar', 'exportador-usuarios')?></option>
            <?php foreach ($euconfig->userdata as $udata => $dataname): ?>
                <option value='<?php echo $udata?>'><?php echo $dataname?></option>
            <?php endforeach;?>
            
            <?php foreach ($euconfig->metadata as $udata => $dataname): ?>
                <option value='_<?php echo $udata?>'><?php echo $dataname?></option>
            <?php endforeach;?>
        </select>
        <select name='operator'>
            <option value='eq'><?php _e('igual','exportador-usuarios')?></option>
            <option value='dif'><?php _e('diferente','exportador-usuarios')?></option>
            <option value='like'><?php _e('contém','exportador-usuarios')?></option>
            <option value='not-like'><?php _e('não contém','exportador-usuarios')?></option>
        </select>
        <input name='filter_value' value=''> 
    </div>
    <div class='eu-field'> 
        <h4><?php _e('Capabilities','exportador-usuarios')?>:</strong></h4>
        <?php foreach ($euconfig->roles as $cap => $capname): ?>
            <label><input type='checkbox' name='roles[]' value='<?php echo $cap?>' <?php if(!isset($_POST['userdata']) || isset($_POST['roles'][$cap])) echo 'checked="checked"'; ?>/><?php echo $capname?></label><br />
        <?php endforeach;?>
    </div>
    
    <div class='eu-field'> 
        <h4><?php _e('Campos','exportador-usuarios')?>:</strong></h4>
        <?php foreach ($euconfig->userdata as $udata => $dataname): ?>
            <label><input type='checkbox' name='userdata[]' value='<?php echo $udata?>' <?php if(!isset($_POST['userdata']) || isset($_POST['userdata'][$udata])) echo 'checked="checked"'; ?>/><?php echo $dataname?></label><br />
        <?php endforeach;?>
        <hr />
        <?php _e('metadados','exportador-usuarios')?>:<br />
        <?php foreach ($euconfig->metadata as $udata => $dataname): ?>
            <label><input type='checkbox' name='metadata[]' value='<?php echo $udata?>' <?php if(!isset($_POST['userdata']) || isset($_POST['metadata'][$udata])) echo 'checked="checked"'; ?>/><?php echo $dataname?></label><br />
        <?php endforeach;?>
    </div>
    <div class='eu-field'>
        <h4><?php _e('Formato do arquivo','exportador-usuarios')?>:</strong></h4>
        <?php
        $first = true; 
        foreach ($euconfig->exportadores as $exportador): 
            eval("\$extension = {$exportador}::getFileExtension();");
            eval("\$description = {$exportador}::getFileExtensionDescription();");
            
        ?>
            <label><input type="radio" name='exportador' value='<?php echo $exportador?>' <?php if((!isset($_POST['exportador']) && $first) || (isset($_POST['exportador']) && $_POST['exportador'] == $exportador)) echo 'checked="checked"'?>/><?php echo $description;?> <em>(.<?php echo $extension?>)</em></label> <br/>
        <?php 
            $first = false;
        endforeach;
        ?>
        
        
        <h4><?php _e('Ordenar por','exportador-usuarios')?>:</strong></h4>
        <select name='order'>
        <?php
        $first = true;
        $defaultConfig = eu_getDefaultConfig(); 
        foreach ($defaultConfig->userdata as $udata => $dataname): ?>
            <option value='<?php echo $udata?>' <?php if((!isset($_POST['order']) && $first) || (isset($_POST['order']) && $_POST['order'] == $dataname)) echo 'checked="checked"'?>/><?php echo $dataname?></option>
        <?php 
             $first = false;
        endforeach;
        ?>
        
        
        <?php /* foreach ($euconfig->metadata as $udata => $dataname): ?>
            <label><input type='checkbox' name='metadata[]' value='<?php echo $udata?>' <?php if(!isset($_POST['userdata']) || isset($_POST['metadata'][$udata])) echo 'checked="checked"'; ?>/><?php echo $dataname?></label><br />
        <?php endforeach; */?>
        
        </select> 
        <select name='oby'>
            <option value='ASC'><?php _e('Ascendente', 'exportador-usuarios');?></option>
            <option value='DESC'><?php _e('Descendente', 'exportador-usuarios');?></option>
        </select>
        
        <br/><br/>
        <input type="submit" value="<?php _e('exportar', 'exportador-usuarios')?>">
    </div>
</form>
