<?php
    
    /**
      * gets the diacritics out of the characters and replaces it with non-diacritics equivalents
      */
    function diacritics_out($vstup) {
        $has_dia = array('á','ä','ă','â','ą','č','ć','ď','é','ě','ę','í','î','ľ','ĺ','ł','ň','ń','ó','ô','ö','ő','ř','ŕ','š','ș','ś','ť','ț','ů','ú','ü','ű','ý','ž','ź','ż','ß'); 
        $bez_dia = array('a','a','a','a','a','c','c','d','e','e','e','i','i','l','l','l','n','n','o','o','o','o','r','r','s','s','s','t','t','u','u','u','u','y','z','z','z','s'); 
    
        $has_dia_up = ARRAY('Á','Ä','Ă','Â','Ą','Č','Ć','Ď','É','Ě','Ę','Í','Î','Ľ','Ĺ','Ł','Ň','Ń','Ó','Ô','Ö','Ő','Ř','Ŕ','Š','Ș','Ś','Ť','Ț','Ů','Ú','Ü','Ű','Ý','Ž','Ź','Ż','ẞ'); 
        $bez_dia_up = ARRAY('A','A','A','A','A','C','C','D','E','E','E','I','I','L','L','L','N','N','O','O','O','O','R','R','S','S','S','T','T','U','U','U','U','Y','Z','Z','Z','S'); 

        $string = str_replace($has_dia, $bez_dia, $vstup); 
        $string = str_replace($has_dia_up, $bez_dia_up, $string); 
        
        return $string;
    }
    
    /**
      * applies the url format on the name and gets rid out of the diacritics
      */
    function url_title_ex($str) {
        $str = str_replace('.', '-', $str);

        $str = url_title(diacritics_out($str));
        if ($str == '') {
            $str = 'emp';
        }

        $str = strtolower($str);
        return $str;
    }

    function character_limiter_ex($word, $size) {
        $result = $word;
        if (strlen($word) > $size) {
            $result = mb_substr($result, 0, $size) .'...';
        }
        
        return $result;
    }
    
    function prepareForFulltext($searchstring) {
        $result = '';
        $result_array = explode(' ', $searchstring);
        foreach ($result_array as $item) {
            $item = trim_ex($item);
            
            if ($item != '') {
                $result = $result .' +'. $item;
            }
        }
        
        return $result;
    }
    
    function formatIc($ic) {
        return str_pad($ic, 8, "0", STR_PAD_LEFT);
    }

    function mb_str_shuffle($str) {
        $tmp = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
        shuffle($tmp);
        return join("", $tmp);
    }    

    function normalize_for_search($str, $replaceW = false) {
        $result = trim_ex(strtoupper(diacritics_out($str)));

        if ($replaceW) {
            $result = str_replace('w', 'v', $result);
        }

        return $result;
    }

    function prepare_for_fulltext($str, $match_all = false) {
        $result = array();
        $pieces = explode(' ', $str);

        $ci =& get_instance();

        foreach ($pieces as $piece) {
            if (strlen($piece) > 2) {
                array_push($result, $ci->db->escape(($match_all ? '+' : '') . $piece));
            }
        }

        return implode(' ', $result);
    }

    function partial_phonetical($str, $method) {
        $result = array();
        $pieces = explode(' ', $str);

        foreach ($pieces as $piece) {
            if (strlen($piece) > 2) {
                if ($method === 'soundex') {
                    array_push($result, soundex($piece));
                } else if ($method === 'metaphone') {
                    array_push($result, metaphone($piece));
                }
            }
        }

        return implode(' ', $result);
    }

    function removeDoubleSpaces($str) {
        return preg_replace('/\s+/', ' ', $str);
    }

    function trim_ex($str) {
        return trim(removeDoubleSpaces($str));
    }

    /**
    * Detects whether a string starts with defined 
    */
    function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    /**
    * Detects whether a string ends with defined
    */
    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
        
    function isIcDefined($ic) {
        return $ic != null && $ic != '' && $ic != 0 && $ic != '0';
    }

    function makeIsirDetailLink($subject) {
        return makeGeneralDetailLink('isir', $subject);
    }

    function makeIsirDocLink($id, $name) {
        $subject = array('id' => $id, 'name' => $name);
        return makeGeneralDetailLink('isir/doc', $subject);
    }

    function makeIsirLink($subject) {
        $ci =& get_instance();
        return makeGeneralDetailLink($ci->config->item('DETAIL_ISIR'), $subject);
    }

    function makeObchodniLink($subject) {
        $ci =& get_instance();
        return makeGeneralDetailLink($ci->config->item('DETAIL_OBCHODNI'), $subject);
    }

    function makeGeneralDetailLink($source, $subject) {
        return 'vypis/'. $source .'/'. $subject['id'] .'/'. url_title_ex($subject['name']);
    }

    function makeScreeningLink($source, $subject) {
        return 'lustrace/'. $source .'/'. $subject['id'] .'/'. url_title_ex($subject['name']);
    }

    function makeRelationsLink($subject) {
        return 'vazby/'. $subject['id'] .'/'. url_title_ex($subject['name']);
    }

    function makeMenuLink($link, $alternative_links = array()) {
        $uri_string = uri_string();
        $isCurrent = $uri_string == $link;

        if (!$isCurrent && sizeof($alternative_links) > 0) {
            foreach ($alternative_links as $al) {
                if (startsWith($uri_string, $al)) {
                    $isCurrent = true;
                    break;
                }
            }
        }

        return '<li'. ($isCurrent ? ' class="current-menu-item"' : '') . '>';
    }

    function replaceBreaks($str) {
        $breaks = array("<br />", "<br>", "<br/>", "</li>", "</tr>");  
        $str = str_ireplace($breaks, "\r\n", $str);  

        $delimiters = array("</td>");  
        $str = str_ireplace($delimiters, " ; ", $str);  
        
        return $str;
    }

?>