<!DOCTYPE html>
<html>
<head>
    <base href="<?php echo base_url(); ?>">

    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800&amp;subset=latin-ext"
          rel="stylesheet">
    <?php echo style_url('css/styles.min.css', 'screen'); ?>
    <?php echo style_url('css/print.min.css', 'print'); ?>

    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- NUTNO DOPLNIT INFORMACE -->
    <meta name="descript" content=""/>
    <link rel="canonical" href=""/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content=""/>
    <meta property="og:description" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:site_name" content=""/>
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:description" content=""/>
    <meta name="twitter:title" content=""/>
    <!--<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32">-->
    <!-- NUTNO DOPLNIT INFORMACE -->


    <!--[if lt IE 9]>
    <?php echo style_url('css/ie.min.css'); ?>
    <![endif]-->
    <!--[if lt IE 9]>
    <?php echo script_url('js/ie.min.js'); ?>
    <![endif]-->
    <title><?php echo isset($title) ? ($title .' | '. $this->config->item('APPLICATION_NAME')) : $this->config->item('APPLICATION_NAME'); ?></title>

</head>
<body>