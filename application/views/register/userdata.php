<main>
    <div class="inner rs_form">
        <div class="rs_form__body rs_form__body--large">
            <form action="<?php echo current_url(); ?>" method="post">
                <?php
                    $lowerTitle = mb_strtolower($title);
                    $isPlainRegistrace = $lowerTitle == 'registrace';
                    if (!$isPlainRegistrace) {
                        echo '<h1>Registrace - '. $lowerTitle .'</h1>';
                    }
                ?>
    
                <?php
                    if (isset($showmessage)) {
                        echo 
                            '<h2>Registrace byla úspěšně dokončena.</h2>
                            <p><strong>Děkujeme Vám za projevenou důvěru.</strong></p>
                            <p><strong>Účet je aktivní, můžete se přihlásit.</strong></p>';
                    } else if (isset($showvalidation)) {
                        $message['message'] = validation_errors('<p class="validation_error">', '</p>');
                        $this->load->view('inc/message', $message);
                    }

                    if (!isset($showmessage)) {
                ?>

                <h2>kontaktní údaje</h2>
    
                <div class="form_control_group form_control_group--order">
                    <div class="form_control_wrap">
                        <input type="text" class="form_control" name="name"
                               placeholder="* Obchodní firma / jméno a příjmení" autofocus required value="<?php echo set_value('name'); ?>"/>
                    </div>
                    <div class="form_control_wrap">
                        <input type="text" class="form_control" name="ico" placeholder="IČ" value="<?php echo set_value('ico'); ?>"/>
                    </div>
                    <div class="form_control_wrap">
                        <input type="text" class="form_control" name="representedby" placeholder="* Zastoupen/jednající"
                                required value="<?php echo set_value('representedby'); ?>"/>
                    </div>
                    <div class="form_control_wrap">
                        <input type="text" class="form_control" name="caknumber" placeholder="Evidenční číslo advokáta" 
                                <?php echo $register_session['discount_lawyer'] ? 'required' : ''; ?> value="<?php echo set_value('caknumber'); ?>"/>
                    </div>
                </div>
    
                <ul class="form_control_group form_control_group--two_cols">
                    <li>
                        <h2>sídlo</h2>
                        <div class="form_control_wrap">
                            <input type="text" class="form_control" name="address" placeholder="* Ulice, č.p." required value="<?php echo set_value('address'); ?>"/>
                        </div>
                        <div class="form_control_wrap">
                            <input type="text" class="form_control" name="city" placeholder="* Město" required value="<?php echo set_value('city'); ?>"/>
                        </div>
                        <div class="form_control_wrap">
                            <input type="text" class="form_control" name="zip" placeholder="PSČ" value="<?php echo set_value('zip'); ?>"/>
                        </div>
                        <div class="form_control_wrap form_control_wrap--padded-b">
                            <input type="checkbox" class="rs_styled_checkbox" name="has_invoice_same" id="faktura_toggle" <?php echo set_checkbox('has_invoice_same', 1); ?>
                                    value="1"/>
                            <label for="faktura_toggle">
                                <span class="btn_area"></span>
                                <span class="btn_desc">Sídlo je shodné s fakturační adresou </span>
                            </label>
                        </div>
                        <div class="form_control_wrap">
                            <input type="tel" class="form_control" required placeholder="* Telefon" name="phone" value="<?php echo set_value('phone'); ?>"/>
                        </div>
                        <div class="form_control_wrap">
                            <input type="email" class="form_control" required placeholder="* Email" name="email" value="<?php echo set_value('email'); ?>"/>
                        </div>
                        <div class="form_control_wrap">
                            <input type="password" class="form_control" required placeholder="* Heslo" name="password"/>
                        </div>
                        <div class="form_control_wrap">
                            <input type="password" class="form_control" required placeholder="* Heslo (kontrola)"
                                   name="password2"/>
                        </div>
                    </li>
                    <li>
                        <div id="faktura_address_area">
                            <h2>fakturační údaje</h2>
                            <div class="form_control_wrap">
                                <input type="text" class="form_control" name="faktura_address" placeholder="* Ulice, č.p." required value="<?php echo set_value('faktura_address'); ?>"/>
                            </div>
                            <div class="form_control_wrap">
                                <input type="text" class="form_control" name="faktura_city" placeholder="* Město" required value="<?php echo set_value('faktura_city'); ?>"/>
                            </div>
                            <div class="form_control_wrap">
                                <input type="text" class="form_control" name="faktura_zip" placeholder="PSČ" value="<?php echo set_value('faktura_zip'); ?>"/>
                            </div>
                        </div>

                        <?php if (!$isPlainRegistrace && $register_session['discount_year'] == false) { ?>
                        <div class="form_control_wrap form_control_wrap--padded-e">
                            <div class="rs_styled_select">
                                <select name="payment_frequency_id" placeholder="Frekvence platby">
                                    <?php
                                        foreach($payment_frequencies as $i) {
                                            $default = isset($prefill_payment_frequency_id) && $prefill_payment_frequency_id == $i['id'];
                                            echo '<option value="'. $i['id'] .'"'. set_select('payment_frequency_id', $i['id'], $default) .'>Fakturační období - '. $i['name'] .'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php } ?>

                    </li>
                </ul>
    
                <hr/>
    
                <div class="form_control_wrap">
                    <textarea class="form_control" name="note" placeholder="Poznámka"><?php echo set_value('note'); ?></textarea>
                </div>
                <p class="hint_padded">Údaje označené <span class="error">*</span> je nutné vyplnit </p>
    
    
                <p class="text-center">
                    <input type="submit" class="form_submit" name="send" value="registrovat"/>
                </p>

                <?php } ?>
    
            </form>
        </div>
    </div>
</main>

<script type="text/javascript">
    window.onload = function(e) { 
        $("input[name=address]").change(copyAddress);
        $("input[name=city]").change(copyCity);
        $("input[name=zip]").change(copyZip);
        $("input[name=address]").keyup(copyAddress);
        $("input[name=city]").keyup(copyCity);
        $("input[name=zip]").keyup(copyZip);

        $("#faktura_toggle").change(toggleFakturaAddress);

        function copyAddress() {
            if ($("#faktura_toggle").is(':checked')) {
                $("input[name=faktura_address]").val($("input[name=address]").val());
            }
        }

        function copyCity() {
            if ($("#faktura_toggle").is(':checked')) {
                $("input[name=faktura_city]").val($("input[name=city]").val());
            }
        }

        function copyZip() {
            if ($("#faktura_toggle").is(':checked')) {
                $("input[name=faktura_zip]").val($("input[name=zip]").val());
            }
        }

        function toggleFakturaAddress() {
            if ($("#faktura_toggle").is(':checked')) {
                $("input[name=faktura_address]").attr('disabled', 'disabled');
                $("input[name=faktura_city]").attr('disabled', 'disabled');
                $("input[name=faktura_zip]").attr('disabled', 'disabled');

                $("input[name=faktura_address]").css('background', 'transparent');
                $("input[name=faktura_city]").css('background', 'transparent');
                $("input[name=faktura_zip]").css('background', 'transparent');

                $("input[name=faktura_address]").css('border', '0');
                $("input[name=faktura_city]").css('border', '0');
                $("input[name=faktura_zip]").css('border', '0');

                copyAddress();
                copyCity();
                copyZip();
            } else {
                $("input[name=faktura_address]").removeAttr('disabled');
                $("input[name=faktura_city]").removeAttr('disabled');
                $("input[name=faktura_zip]").removeAttr('disabled');

                $("input[name=faktura_address]").removeAttr('style');
                $("input[name=faktura_city]").removeAttr('style');
                $("input[name=faktura_zip]").removeAttr('style');
            }
        }

        toggleFakturaAddress();
    };
</script>