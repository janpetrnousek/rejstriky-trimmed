<!DOCTYPE html>
<html>
<head>
    <base href="<?php echo base_url(); ?>">

    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800&amp;subset=latin-ext"
          rel="stylesheet">
    <?php echo style_url('css/cms.min.css'); ?>

    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <title><?php echo isset($title) ? ($title .' | '. $this->config->item('APPLICATION_NAME')) : $this->config->item('APPLICATION_NAME'); ?></title>

</head>
<body>
    <header>
        <?php
            if ($this->session->userdata($this->config->item('CMS_LOGGED_SESSION'))) {
                $this->load->view('cms/inc/menu');
            }
        ?>
    </header>