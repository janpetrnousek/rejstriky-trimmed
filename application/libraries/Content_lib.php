<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Content_lib
{

    // Codeigniter instance
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Shows the validation errors for the given array of fields
     * Validation class has to be loaded
     */
    public function show_validation($fields)
    {
        $ret = '';

        foreach ($fields as $i) {
            if ($i != '') {
                $ret = $ret . $i;
            }
        }

        return $ret;
    }

    /**
     * Checks the RC
     */
    public function verifyRC($rc, $requireSlash)
    {
        if (!$requireSlash || strpos($rc, '/') != false) {
            // "be liberal in what you receive"
            if (!preg_match('#^\s*(\d\d)(\d\d)(\d\d)[ /]*(\d\d\d)(\d?)\s*$#', $rc, $matches)) {
                return false;
            }

            list(, $year, $month, $day, $ext, $c) = $matches;

            // ziskaj rok a mesiac pre vratenie datumu narodenia
            $currentYear = date("y");
            $minAge = 12;
            $niceMonth = $month;
            $niceYear = $year < ($currentYear - $minAge) ? ($year + 2000) : ($year + 1900);

            // k měsíci může být připočteno 20, 50 nebo 70
            if ($niceMonth > 70 && $niceYear > 2003) {
                $niceMonth -= 70;
            } elseif ($niceMonth > 50) {
                $niceMonth -= 50;
            } elseif ($niceMonth > 20 && $niceYear > 2003) {
                $niceMonth -= 20;
            }

            // do roku 1954 přidělovaná devítimístná RČ nelze ověřit
            if ($c === '') {
                return $year < 54 && checkdate($niceMonth, $day, $niceYear)
                ? $niceYear . '-' . $niceMonth . '-' . $day
                : false;
            }

            // kontrolní číslice
            $mod = ($year . $month . $day . $ext) % 11;
            if ($mod === 10) {
                $mod = 0;
            }

            if ($mod !== (int) $c) {
                return false;
            }

            // kontrola data
            if (!checkdate($niceMonth, $day, $niceYear)) {
                return false;
            }

            // cislo je OK
            return $niceYear . '-' . $niceMonth . '-' . $day;
        } else {
            return false;
        }
    }

    /**
     * Gets the spis name from the parts
     */
    public function format_spisname($number_prefix, $number_id, $number_year)
    {
        return $number_prefix . ' ' . $number_id . '/' . $number_year;
    }

    /**
     * Formats the spises from the given list and according to the type
     * Type can be:
     * - passed (1): status is Odskrtnuta
     * - active (2): status is not Odskrtnuta
     */
    public function format_spises($spises, $type)
    {
        $ret = '';

        for ($i = 0; $i < sizeof($spises); $i++) {
            // show when we search for inactive and spis is NOT active or we look for active (and thus spis is active too)
            $show =
                ($type == $this->CI->config->item('SPIS_NOT_ACTIVE') && in_array($spises[$i]['status_id'], $this->CI->config->item('STATES_NOT_INSOLVENCE')))
                || ($type == $this->CI->config->item('SPIS_ACTIVE') && !in_array($spises[$i]['status_id'], $this->CI->config->item('STATES_NOT_INSOLVENCE')));

            if ($show == true) {
                $ret .= '<a href="' . makeIsirDetailLink($spises[$i]) . '">';
                $ret .= $spises[$i]['court'] . ' ' . $spises[$i]['senat_number'] . ' ' . $spises[$i]['number_prefix'] . ' ' . $spises[$i]['number_id'] . '/' . $spises[$i]['number_year'];
                $ret .= '</a>';
                $ret .= ', stav: ' . $spises[$i]['status'];
                $ret .= '<br />';
            }
        }

        if (strlen($ret) === 0) {
            $ret = 'BEZ ZÁZNAMU';
        }

        return $ret;
    }

}

/* End of file daz_image.php */
/* Location: ./system/application/libraries/daz_image.php */
