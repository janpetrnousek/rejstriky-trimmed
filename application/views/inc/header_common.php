<header class="main_header">
    <?php $this->load->view('inc/header_topbar'); ?>

    <h1 class="big_title"><?php echo isset($titleOverride) ? $titleOverride : (isset($title) ? $title : ''); ?></h1>
</header>