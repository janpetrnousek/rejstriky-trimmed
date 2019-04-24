<main>
    <div class="inner rs_form">
        <div class="rs_form__body rs_form__body--large">
            <?php
                if ($result != '') {
                    $data['message'] = $result;
                    $this->load->view('inc/message', $data);
                } else if ($isResetAllowed) {
                    echo '<p>Zadejte nové heslo pro Váš účet.</p>';
                }
            ?>

            <?php if ($isResetAllowed) { ?>
            <form action="<?php echo current_url(); ?>" method="post">
                <div class="form_control_group form_control_group--order nolimit">
                <div class="form_control_wrap">
                        <input type="password" class="form_control" name="password" placeholder="nové heslo" required autofocus />
                    </div>
                    <div class="form_control_wrap">
                        <input type="password" class="form_control" name="password2" placeholder="nové heslo (potvrzení)" required />
                    </div>
                </div>
    
                <p class="text-center">
                    <input type="submit" class="form_submit" name="send" value="Odeslat"/>
                </p>
    
            </form>
            <?php } ?>
        </div>
    </div>
</main>