<?php
abstract class A_UserExporter{
    protected $users;
    protected $filename;
    protected $tmpfile;
    
    protected $cols = array();
    protected $descriptions = array();
    
    
    public abstract static function getFileExtension();
    
    public abstract static function getFileExtensionDescription();
    
    protected abstract function printHeader();
    
    protected abstract function printFooter();
    
    protected abstract function printUser($user);
    
    public function __construct($filename = ''){
        $_cols = array();
        $wpue_config = wpue_getConfig();
        if(isset($_POST['userdata']))
            foreach ($_POST['userdata'] as $col){
                $_cols[] = $col;
                $this->descriptions[$col] = $wpue_config->userdata[$col];
            }
            
        if(isset($_POST['metadata']))
            foreach ($_POST['metadata'] as $col){
                $_cols[] = $col;
                $this->descriptions[$col] = $wpue_config->metadata[$col];
            }
            
        if(wpue_isBP()){
        	$bp_fields = wpue_bp_getProfileFields();
        	foreach ($wpue_config->bp_fields as $field_id)
        		if(isset($_POST['bp_fields']) && in_array($field_id, $_POST['bp_fields'])){
        			$col = 'bp_'.$field_id;
        			$_cols[] = $col;
        			$this->descriptions[$col] = $bp_fields[$field_id]->name;
        		}
        			
        }
        
        $this->tmpfile = wpue_getUsers_to_tmpfile();
        
        $this->filename = $filename ? $filename : 'users-'.date('Y-m-d');
        
        foreach($_POST['display_order'] as $field)
            if(in_array($field, $_cols))
                $this->cols[] = $field;
    }
    
    public final function export(){
        $this->printHeader();
        
        $file_handle = fopen($this->tmpfile, "r");
        
        while (!feof($file_handle)) {
            
            $_user = fgets($file_handle);
            $_user = str_replace('||BR||', "\r\n", $_user);
            //if (is_serialized($_user)) {
                $user = unserialize($_user);
                $this->printUser($user);
            //}
            
        }
        
        fclose($file_handle);
        
        $this->printFooter();
        
        $this->deleteTempFile();
        
        die;
    }
    
    public function deleteTempFile() {
    
        unlink($this->tmpfile);
    
    }
    
    public final static function activate(){
        $class = get_called_class();
        eval('$default = '.$class.'::getDefaultOptions();');
        $option = $class.'-options';
        if($default){
            if(get_option($option))
                delete_option($option);
                
            add_option($option, $default);
        }
    }
    
    public static function deactivate(){
        $option = get_called_class().'-options';
        if(get_option($option))
            delete_option($option);
    }
    
    public static function saveOptions(){
       $class = get_called_class();
       eval("\$options = {$class}::getPostedOptions();");
       $option = $class.'-options';
       if($options)
           update_option($option, $options);
    }
    
    public static function getOptions(){
        $option = get_called_class().'-options';
        return get_option($option);
    }
    
    public static function printOptionsForm(){}
    public static function getPostedOptions(){return null;}
    public static function getDefaultOptions(){return null;}
}
