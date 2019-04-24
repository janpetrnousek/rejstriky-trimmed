<div class="inner inner--bigger">
    <a href="<?php echo base_url(); ?>" class="main_header__logo">
        <img src="images/logo.png" alt="rejstříky.info" width="131" height="51"/>
    </a>

    <?php
        $user = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));
    ?>

    <?php if (is_array($user)) { ?>
    <nav class="main_header__top_nav main_header__top_nav--logged">
        <div class="main_header__top_nav__account">
            <div class="main_header__top_nav__account-info">
                <a href="#" data-account-handle="true">info</a>
                <div class="main_header__top_nav__account-popup">
                    <p>
                        Poslední přihlášení: <?php echo date("d.m.Y", strtotime($user['lastlogin'])); ?><br />
                        Celkem sledujete: <?php echo $user['numwatches']; ?> (max. <?php echo $user['max_subjects']; ?>)
                    </p>
                </div>
            </div>
            <span class="main_header__top_nav__email"><?php echo $user['email']; ?></span> | 
            <a href="monitoring-rejstriku/sledovane">zpět do účtu</a> |
            <a href="odhlasit">odhlásit</a>
        </div>
    </nav>

    <?php } else { ?>

    <nav class="main_header__top_nav">
        <ul class="main_header__top_nav__menu">
            <li><a href="registrace">Registrovat</a></li>
            <li><a href="prihlasit">Přihlásit</a></li>
        </ul>
    </nav>

    <?php } ?>
</div>
