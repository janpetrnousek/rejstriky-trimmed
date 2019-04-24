<main>
    <div class="inner rs_form">
        <div class="rs_form__body rs_form__body--large">
            <?php
                if ($result != '') {
                    $data['message'] = $result;
                    $this->load->view('inc/message', $data);
                } else {
                    echo '<p>Zadejte prosím vaše uživatelské jméno. Instrukce pro obnovení hesla Vám budou zaslány.</p>';
                }
            ?>
            <form action="<?php echo current_url(); ?>" method="post">
                <div class="form_control_group form_control_group--order nolimit">
                    <div class="form_control_wrap">
                        <input type="email" class="form_control" name="email" placeholder="uživatelské jméno / email" required autofocus />
                    </div>
                </div>
    
                <p class="text-center">
                    <input type="submit" class="form_submit" name="send" value="Odeslat"/>
                </p>
    
            </form>
        </div>
    </div>
</main>