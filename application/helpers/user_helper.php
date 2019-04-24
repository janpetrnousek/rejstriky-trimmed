<?php

    function isUserLoggedIn() {
        $ci = & get_instance();

        $user = $ci->session->userdata($ci->config->item('USER_LOGGED_SESSION'));
        return is_array($user);
    }

?>