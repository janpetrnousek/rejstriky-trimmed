<main>
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body">
            <form action="<?php echo current_url(); ?>" method="post">
                <h1>Editovat sledovaný subjekt</h1>

                <div class="rs_form__body__help">
    
                    <div class="tooltip tooltip--top tooltip--help">
                        <span class="tooltip__handle">?</span>
                        <div class="tooltip__content" style="display: none;">
                            <p>
                                Předpokladem funkčního monitoringu je bezchybné zadání IČ nebo RČ.
                                <br /><br />
                                Monitoring probíhá zejména podle IČ/RČ a podpůrně podle názvu/jména (vložíte-li jej, což doporučujeme). 
                                Monitoring fyzických osob bez RČ probíhá pouze při současném zadání datumu narození a jména.
                            </p>
                        </div>
                    </div>
    
                </div>

                <?php 
                    if (isset($result)) {
                        $msg['message'] = $result;
                        $this->load->view('inc/message', $msg);
                    }
                ?>

                <div class="form_control_group form_control_group--order">
                    <div class="form_control_wrap">
                        <input type="text" name="ic" placeholder="* IČ" autofocus value="<?php echo set_value('ic', $record->ic); ?>" class="form_control" />
                    </div>

                    <div class="form_control_wrap">
                        <input type="text" name="rc" placeholder="* RČ" value="<?php echo set_value('rc', $record->rc); ?>" class="form_control" />
                    </div>

                    <div class="form_control_wrap">
                        <?php
                            $disabled = $userinfo['is_automatic_filling_enabled'] == 1 ? ' disabled = "disabled"' : '';
                            $placeholder = $userinfo['is_automatic_filling_enabled'] == 1 
                                ? 'Editaci názvu je potřeba povolit v nastavení.' 
                                : 'Název / Příjmení';
                        ?>
                        <input type="text"<?php echo $disabled; ?> placeholder="<?php echo $placeholder; ?>" name="name" value="<?php echo set_value('name', $record->name); ?>" class="form_control" />
                    </div>

                    <div class="form_control_wrap">
                        <?php
                            $placeholder = $userinfo['is_automatic_filling_enabled'] == 1 
                                ? 'Editaci jména je potřeba povolit v nastavení.' 
                                : 'Jméno';
                        ?>
                        <input type="text"<?php echo $disabled; ?> placeholder="<?php echo $placeholder; ?>" name="firstname" value="<?php echo set_value('firstname', $record->firstname); ?>" class="form_control" />
                    </div>

                    <div class="form_control_wrap">
                        <input type="text" name="birthdate" placeholder="* Datum narození" value="<?php echo set_value('birthdate', ($record->birthdate != '' && $record->birthdate != '0000-00-00') ? date("d.m.Y", strtotime($record->birthdate)) : ''); ?>" class="form_control datepicker" />
                    </div>

                    <div class="form_control_wrap">
                        <input type="text" name="note" placeholder="Poznámka" value="<?php echo set_value('note', $record->note); ?>" class="form_control" />
                    </div>

                    <div class="form_control_wrap">
                        <input type="text" name="clientname" placeholder="Identifikátor" value="<?php echo set_value('clientname', $record->clientname); ?>" class="form_control" />
                    </div>
                </div>

                <p>
                    <span class="error">*</span> IČ nebo RČ nebo datum narození je nutné vyplnit (RČ včetně lomítka). 
                    Při vyplnění datumu narození je nutné zadat taky název subjektu.
                </p>

                <p>
                    <strong>
                        Upozornění:
                    </strong>
                    monitoring probíhá zejména podle IČ/RČ a podpůrně podle názvu/jména (vložíte-li jej, což doporučujeme). 
                    Monitoring fyzických osob bez RČ probíhá pouze při současném zadání datumu narození a jména.
                </p>

                <p class="text-center">
                    <input type="submit" class="form_submit" name="send" value="ULOŽIT"/>
                </p>
            </form>
        </div>

    </div>
</main>