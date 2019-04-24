<?php
    
    function script_url($url, $parameters = null) {
        if ($parameters === null) {
            $parameters = array();
        }

        $parameters['base-url'] = base_url();

        $parameters_attributes = '';
        if (is_array($parameters)) {
            foreach ($parameters as $name => $value) {
                $parameters_attributes .= ' data-'. $name .'="'. $value .'"';
            }
        }

        return '<script src="'. url_with_version($url) .'"'. $parameters_attributes .'></script>';
    }

    function style_url($url, $media = '') {
        $media = $media == '' ? '' : ' media="'. $media .'"';
        return '<link rel="stylesheet" href="'. url_with_version($url) .'"'. $media .'>';
    }

    function url_with_version($url) {
        $ci =& get_instance();
        return $url .'?v='. $ci->config->item('APPLICATION_VERSION');
    }

?>