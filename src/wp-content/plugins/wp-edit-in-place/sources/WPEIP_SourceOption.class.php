<?php

class WPEIP_SourceOption extends WPEIP_Source {

    protected function _termExists($key, $lcode) {
        $option = $this->_getOption($key);
        $result = is_array($option) && array_key_exists($lcode, $option);
        return $result;
    }

    protected function _getTerm($key, $lcode) {
        $option = $this->_getOption($key);
        if (isset($option[$lcode])) {
            return $option[$lcode];
        } else {
            return null;
        }
    }

    protected function _updateTerm($lcode, $key, $new_term) {
        $option = $this->_getOption($key);
        $option[$lcode] = $new_term;
        $this->_setOption($key, $option);
    }

    protected function _insertTerm($lcode, $key, $term, $description, $frontend_form = false) {
        $option = $this->_getOption($key);
        $option[$lcode] = $term;
        $option['description'] = $description;
        $this->_setOption($key, $option);
        if ($frontend_form) {
            global $WPEIP;
            $frontend_forms = $WPEIP->get_option('frontend_forms');
            array_push($frontend_forms, $key);
            $WPEIP->update_option('frontend_forms', $frontend_forms);
        }
        
    }

    protected function _getDescription($key) {
        $option = $this->_getOption($key);
        return $option['description'];
    }

    protected function _getAll() {
        $result = array();
        $_temp = array();

        global $wpdb;
        $key_prefix = $this->_getOptionName('');

        $terms = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '$key_prefix%'");

        foreach ($terms as $term) {

            if (is_serialized($term->option_value)) {
                
                $_term = unserialize($term->option_value);
                
                $key = str_replace($key_prefix, '', $term->option_name);
                if (trim($_term['description'])) {
                    $_temp[$_term['description']][] = array('key' => $key, 'term' => $_term);
                } else {
                    $_temp[] = array('key' => $key, 'term' => $_term);
                }
            }
        }

        ksort($_temp);
        foreach ($_temp as $temp) {
            if(!isset($temp['key'])){
                foreach ($temp as $t) 
                    $result[$t['key']] = $t['term'];
            }else{
                $result[$temp['key']] = $temp['term'];
            }
        }

        return $result;
    }

    protected function _getOption($key) {
        $option = get_option($this->_getOptionName($key));
        if ($option === false)
            $option = null;

        return $option;
    }

    protected function _setOption($key, array $value) {
        if (is_null($this->_getOption($key)))
            add_option($this->_getOptionName($key), $value, '', $this->config['autoload']);
        else
            update_option($this->_getOptionName($key), $value);
    }

    protected function _getOptionName($key) {
        return $this->config['option_prefix'] . '-' . $key;
    }

}
