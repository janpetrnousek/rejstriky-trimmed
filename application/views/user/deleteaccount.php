
<main>
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body">
            <?php if($isFree) { ?>

            <h1>Ukončení licence</h1>

            <p>
                Vážený uživateli,
                <br />
                <br />
                potvrzujeme bezplatné zrušení vaší licence a uživatelského
                účtu na stránkách <a href="<?php echo base_url(); ?>">www.rejstriky.info</a>. Tímto jsou veškeré
                vzájemné závazky ve vztahu k vaší objednávce vypořádány.
            </p>

            <?php } else { ?>

            <h1>Přijetí výpovědi</h1>

            <p>
                Vážený uživateli,
                <br />
                <br />
                potvrzujeme přijetí výpovědi smlouvy o užívání software
                rejstriky.info na stránkách <a href="<?php echo base_url(); ?>">www.rejstriky.info</a>.
            </p>

            <?php } ?>

        </div>

    </div>
</main>