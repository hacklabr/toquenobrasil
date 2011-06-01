<?php
abstract class EU_Exportador{
    protected $users;
    protected $filename;
    
    protected $cols = array();
    protected $descriptions = array();
    
    
    public abstract static function getFileExtension();
    
    public abstract static function getFileExtensionDescription();
    
    protected abstract function printHeader();
    
    protected abstract function printFooter();
    
    protected abstract function printUser($user);
    
    public function __construct(array $users, $filename = ''){
        
        $euconfig = eu_getConfig();
        if(isset($_POST['userdata']))
            foreach ($_POST['userdata'] as $col){
                $this->cols[] = $col;
                $this->descriptions[$col] = $euconfig->userdata[$col];
            }
            
        if(isset($_POST['metadata']))
            foreach ($_POST['metadata'] as $col){
                $this->cols[] = $col;
                $this->descriptions[$col] = $euconfig->metadata[$col];
            }
            
        $this->users = $users;
        $this->filename = $filename ? $filename : 'users-'.date('Y-m-d');
    }
    
    public final function export(){
        $this->printHeader();
        $keys = array_keys($this->users);
         
        
        foreach ($keys as $user_id){
            $user = $this->users[$user_id];
            $this->printUser($user);
            unset($this->users[$user_id]);
        }
        $this->printFooter();
        
        die;
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