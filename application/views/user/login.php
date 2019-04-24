<main>
    <div class="inner rs_form">
        <div class="rs_form__body rs_form__body--large">
            <?php
                $data['message'] = isset($result) ? $result : null;
                $this->load->view('inc/message', $data);
            ?>
            <form action="<?php echo current_url(); ?>" method="post">
                <div class="form_control_group form_control_group--order nolimit">
                    <div class="form_control_wrap">
                        <input type="email" class="form_control" name="email" placeholder="uživatelské jméno / email" required autofocus />
                    </div>
                    <div class="form_control_wrap">
                        <input type="password" class="form_control" name="password" placeholder="heslo" required/>
                    </div>
                </div>
    
                <p class="text-center">
                    <input type="submit" class="form_submit" name="send" value="Přihlásit"/>
                </p>
                <p class="text-center">
                    <a href="zapomenute-heslo">zapomenuté heslo</a>
                </p>
            </form>
        </div>
    </div>
</main>