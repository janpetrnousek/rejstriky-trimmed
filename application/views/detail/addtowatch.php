<?php
    $user = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));
?>

<?php if(isIcDefined($subject['ic']) && is_array($user) && isWatchingAnything($user)) { ?>
<div style="float: right;">
    <a href="#" class="accordion__icon accordion__icon--5" title="přidat ke sledování" data-add-to-watch data-ic="<?php echo $subject['ic']; ?>">
        přidat ke sledování
    </a>
</div>
<?php } ?>