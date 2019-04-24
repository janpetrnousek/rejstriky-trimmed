<main>
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body">
            <h1>Změnit účet</h1>

            <p>
                Požadujete-li změnit hlavní kritéria monitoringu, stačí provést níže novou volbu a tuto nám odeslat.                    
            </p>

            <div class="user_changeaccount_pricing">
                <?php $this->load->view('register/monitoring_block_core'); ?>
            </div>

            <p>
                Změny, které nemají vliv na cenu, nebo ji navyšují, proběhnou automaticky do 24 hod od odeslání, o čemž budete vyrozuměni e-mailem. Ohledně související změny platby a fakturace vás budeme kontaktovat do 10 pracovních dnů. Změny, jejichž důsledkem je snížení ceny, podléhají schválení - budeme vás kontaktovat do 3 pracovních dnů.                    
            </p>

            <p class="text-center">
                <a href="zmenit-ucet-potvrzeni/<?php echo $registerId; ?>" id="changeProgramSubmit" class="form_submit">ODESLAT</a>
            </p>

            <p>&nbsp;</p>

            <h1>Zrušit účet</h1>

            <p>
                Odesláním požadavku zrušíte účet po uskutečnění objednávky, během <?php echo $this->config->item('TRIAL_LENGTH_DAYS'); ?> denní bezplatné zkušební doby nebo uskutečníte výpověď smlouvy dle Podmínek užívání software (výpovědní doba 2 měsíce). O zrušení či výpovědi obdržíte potvrzení na e-mail.
            </p>

            <p class="text-center">
                <a href="zrusit-ucet" class="form_submit">ZRUŠIT ÚČET / VYPOVĚDĚT SMLOUVU</a>
            </p>
        </div>

    </div>
</main>