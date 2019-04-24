<main>
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body">
            <form action="<?php echo current_url(); ?>" method="post">
                <h1>Nastavení upozorňování</h1>

                <?php
                    if (isset($message)) {
                        $msg['message'] = $message;
                        $this->load->view('inc/message', $msg);
                        echo '<br /><br />';
                    }
                ?>

                <div class="rs_form__body__smaller">
                    <h2>Frekvence upozorňování</h2>
    
                    <?php if ($userinfo['is_insolvencewatch'] == true) { ?>

                    <p class="nopadding"><strong>Upozorňování na zahájená insolvenční řízení a změny stavů řízení u
                        sledovaných osob:</strong>
                    </p>

                    <div class="form_control_wrap form_control_wrap--select">
                        <div class="rs_styled_select position-relative put-to-front">
                            <?php echo form_dropdown('spis_notification_frequency_id', $updates, $userinfo['spis_notification_frequency_id']); ?>
                        </div>
                    </div>

                    <div class="form_control_wrap form_control_wrap--offset right-options position-relative">
                        <div class="form_control_wrap">
                            <?php echo form_checkbox('is_spis_notification_empty', TRUE, $userinfo['is_spis_notification_empty'], array('class' => 'rs_styled_checkbox', 'id' => 'is_spis_notification_empty')); ?>
                            <label for="is_spis_notification_empty">
                                <span class="btn_area"></span>
                                <span class="btn_desc">Upozorňovat, že nejsou nové záznamy</span>
                            </label>
                        </div>
    
                        <div class="form_control_wrap">
                            <?php echo form_checkbox('is_spis_notification_minor_documents', TRUE, $userinfo['is_spis_notification_minor_documents'], array('class' => 'rs_styled_checkbox', 'id' => 'is_spis_notification_minor_documents')); ?>
                            <label for="is_spis_notification_minor_documents">
                                <span class="btn_area"></span>
                                <span class="btn_desc">Upozorňovat i na vedlejší dokumenty</span>
                            </label>
                        </div>
    
                        <div class="form_control_wrap">
                            <?php echo form_checkbox('is_spis_notification_compressed', TRUE, $userinfo['is_spis_notification_compressed'], array('class' => 'rs_styled_checkbox', 'id' => 'is_spis_notification_compressed')); ?>
                            <label for="is_spis_notification_compressed">
                                <span class="btn_area"></span>
                                <span class="btn_desc">Komprimovat přílohy</span>
                            </label>
                        </div>
                    </div>

                    <?php } ?>

                    <?php if ($userinfo['is_claimswatch'] == true) { ?>

                    <div class="form_control_wrap form_control_wrap--select">
                        <label for="ms_select_1"><strong>Upozorňování na přihlášené pohledávky v insolvenčním rejstříku:</strong></label>
                        <div id="ms_select_1" class="rs_styled_select">
                            <?php echo form_dropdown('claims_notification_frequency_id', $updates, $userinfo['claims_notification_frequency_id']); ?>
                        </div>
                    </div>

                    <?php } ?>

                    <?php if ($userinfo['is_orwatch'] == true) { ?>
                    
                    <div class="form_control_wrap form_control_wrap--select">
                        <label for="ms_select_1"><strong>Upozorňování na změny společností v obchodním rejstříku
                            společností:</strong></label>
                        <div id="ms_select_1" class="rs_styled_select">
                            <?php echo form_dropdown('or_notification_frequency_id', $updates, $userinfo['or_notification_frequency_id']); ?>
                        </div>
                    </div>

                    <?php } ?>

                    <?php if ($userinfo['is_likvidacewatch'] == true) { ?>

                    <div class="form_control_wrap form_control_wrap--select">
                        <label for="ms_select_1"><strong>Upozorňování na vstup do likvidace u sledovaných obchodních
                            společností:</strong></label>
                        <div id="ms_select_1" class="rs_styled_select">
                            <?php echo form_dropdown('likvidace_notification_frequency_id', $updates, $userinfo['likvidace_notification_frequency_id']); ?>
                        </div>
                    </div>

                    <?php } ?>

                    <?php if ($userinfo['is_orskwatch'] == true) { ?>

                    <div class="form_control_wrap form_control_wrap--select">
                        <label for="ms_select_1"><strong>Upozorňování na změny společností ve slovenském obchodním rejstříku
                            společností:</strong></label>
                        <div id="ms_select_1" class="rs_styled_select">
                            <?php echo form_dropdown('orsk_notification_frequency_id', $updates, $userinfo['orsk_notification_frequency_id']); ?>
                        </div>
                    </div>

                    <?php } ?>

                    <?php if ($userinfo['is_vatdebtorswatch'] == true) { ?>

                    <div class="form_control_wrap form_control_wrap--select">
                        <label for="ms_select_2"><strong>Upozorňování na neplatiče DPH:</strong></label>
                        <div class="rs_styled_select">
                            <?php echo form_dropdown('vatdebtors_notification_frequency_id', $updates, $userinfo['vatdebtors_notification_frequency_id']); ?>
                        </div>
    
                        <div class="form_control_wrap form_control_wrap--inline">
                        <?php echo form_checkbox('is_vatdebtors_notification_empty', TRUE, $userinfo['is_vatdebtors_notification_empty'], array('class' => 'rs_styled_checkbox', 'id' => 'is_vatdebtors_notification_empty')); ?>
                        <label for="is_vatdebtors_notification_empty">
                            <span class="btn_area"></span>
                            <span class="btn_desc">Upozorňovat, že nejsou nové záznamy</span>
                        </label>
                        </div>
                    </div>

                    <?php } ?>

                    <?php if ($userinfo['is_accountchangewatch'] == true) { ?>
                    
                    <div class="form_control_wrap form_control_wrap--select">
                        <label for="ms_select_3"><strong>Upozorňování na změny bankovních účtů:</strong></label>
                        <div class="rs_styled_select">
                            <?php echo form_dropdown('accounts_notification_frequency_id', $updates, $userinfo['accounts_notification_frequency_id']); ?>
                        </div>
                    </div>

                    <?php } ?>

                    <p>
                        <strong class="error">UPOZORNĚNÍ:</strong> časy upozorňování jsou pouze orientační, možná prodleva
                        je až
                        1 hod.
                    </p>
    
                    <?php if ($userinfo['is_insolvencewatch'] == true) { ?>

                    <h2>rozsah upozorňování - insolvence</h2>
    
                    <div class="form_control_wrap form_control_wrap--select">
                        <label for="ms_select_4">
                            <strong>Upozorňování na podobné záznamy: <span class="error">doporučujeme</span></strong>
                            <div class="tooltip">
                                <span class="tooltip__handle tooltip__handle--dark">?</span>
                                <div class="tooltip__content" style="display: none;">
                                    <p>
                                        Tato funkce představuje opatření proti chybám soudů při vkládání údajů o dlužnících do Insolvenčního
                                        rejstříku. Příkladem, pokud soud chybně zadá do rejstříku jednu z číslovek IČ u vámi sledované
                                        společnosti (anebo RČ u fyzické osoby), aplikace rozpozná podobnost se subjektem ve vašem účtu podle
                                        názvu společnosti a umožní Vám přidat si tuto osobu prostřednictvím upozorňovacího e-mailu ke
                                        sledovaným
                                        osobám. O "podobných záznamech" budete pravidelně informováni reportem. Minimalizuje se tak možnost,
                                        že
                                        by Vám unikl záznam o sledovaném subjektu.
                                    </p>
                                </div>
                            </div>
                        </label>

                        <div class="rs_styled_select">
                            <?php echo form_dropdown('ifilter_notification_frequency_id', $updates, $userinfo['ifilter_notification_frequency_id']); ?>
                        </div>
    
                    </div>
    
                    <h3 class="nopadding">Filtrace upozorňování</h3>

                    <?php
                        foreach ($filters as $i) {
                            $selected = $userinfo['notification_filtering_id'] == $i['id'];
                            $elementId = 'filtering_'. $i['id'];
                            $isStatusChangeOnly = $i['id'] == $this->config->item('USER_NOTIFICATION_FILTERING_STATUSCHANGESONLY');

                            echo '<div class="form_control_wrap">';
                            
                            echo $isStatusChangeOnly ? '<div class="form_control_wrap__label form_control_wrap__label--nopadding">' : '';
                            echo '<input type="radio" class="rs_styled_radio" value="'. $i['id'] .'" id="'. $elementId .'" name="notification_filtering_id" '. set_radio('notification_filtering_id', $i['id'], $selected) .'>';
                            
                            echo '<label for="'. $elementId .'">';
                            echo '<span class="btn_area"></span>';
                            echo '<span class="btn_desc">'. $i['name'] .'</span>';
                            echo '</label>';

                            if ($isStatusChangeOnly) {
                                echo '<div class="tooltip">';
                                echo '<span class="tooltip__handle tooltip__handle--dark">?</span>';
                                echo '<div class="tooltip__content" style="display: none;">';
                                echo '<p>Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi</p>';
                                echo '</div>';
                                echo '</div>';
                            }

                            echo $isStatusChangeOnly ? '</div>' : '';

                            echo '</div>';
                        }
                    ?>

                    <?php } ?>

                    <?php
                        function renderOrWatchOptions($prefix, $notification_types, $userinfo) {
                            foreach ($notification_types as $column => $localization) {

                                $name = $prefix . $column;
                                $selected = $userinfo[$name] == '1';
    
                                echo '<div class="form_control_wrap">';
                                
                                echo '<input type="checkbox" class="rs_styled_checkbox" value="1" id="'. $name .'" name="'. $name .'" '. set_checkbox($name, '1', $selected) .'>';
                                
                                echo '<label for="'. $name .'">';
                                echo '<span class="btn_area"></span>';
                                echo '<span class="btn_desc">'. $localization .'</span>';
                                echo '</label>';
    
                                echo '</div>';
                            }
                        }
                    ?>

                    <?php if ($userinfo['is_orwatch'] == true) { ?>

                    <h2>rozsah upozorňování - obchodní rejstřík</h2>

                    <?php renderOrWatchOptions('notifications_or_', $this->config->item('NOTIFICATION_OR_TYPES'), $userinfo); ?>

                    <?php } ?>

                    <?php if ($userinfo['is_orskwatch'] == true) { ?>

                    <h2>rozsah upozorňování - obchodní rejstřík Slovensko</h2>

                    <?php renderOrWatchOptions('notifications_orsk_', $this->config->item('NOTIFICATION_OR_TYPES'), $userinfo); ?>

                    <?php } ?>
    
                    <h2>Emailové adresy pro zasílání upozornění</h2>
    
                    <p>Upozornění budou standardně zasílána na emailovou adresu účtu: <span class="success"><?php echo $userinfo['email']; ?></span>
                        Je možné zadat maximálně <?php echo sizeof($extra_emails); ?> dalších emailů pro zasílání.
                    </p>
                    <ul class="form_control_wrap form_control_wrap--repeater">
                        <?php
                            foreach ($extra_emails as $i) {
                                echo '<li>';
                                echo '<input type="text" class="form_control" placeholder="E-mail" name="extra_emails[]" value="'. $i['email'] .'" />';
                                echo '</li>';
                            }								
                        ?>
                    </ul>
    
                    <h2>Automatické doplňování názvu/jména</h2>
    
                    <p>V případě subjektů zapsaných do obchodního rejstříku se název doplní automaticky. V ostatních případech se název 
                        doplní automaticky při zahájení insolvenčního řízení.	
                        <br/>
                        <strong>UPOZORNĚNÍ:</strong> Při aktivaci této funkce nelze zadat monitorování podle jména a data narození.
                    </p>
                    <?php
                        foreach ($automatic_filling as $id => $i) {
                            $checked = $userinfo['is_automatic_filling_enabled'] == $i['id'] ? ' checked="checked"' : '';
                            $elementId = 'filler_'. $id;

                            echo '<div class="form_control_wrap">';

                            echo $id > 0 ? '<div class="form_control_wrap__label form_control_wrap__label--nopadding">' : '';

                            echo '<input type="radio" class="rs_styled_radio" id="'. $elementId .'" value="'. $i['id'] .'" name="is_automatic_filling_enabled" '. $checked .'>';
                            echo '<label for="'. $elementId .'">';
                            echo '<span class="btn_area"></span>';
                            echo '<span class="btn_desc">'. $i['name'] .'</span>';
                            echo '</label>';

                            echo $id > 0 ? '</div>' : '';

                            echo '</div>';
                        }
                    ?>

                    <p class="text-center">
                        <input type="submit" class="form_submit" name="send" value="uložit"/>
                    </p>
    
                </div>
    
            </form>
        </div>

    </div>
</main>