<main>
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body">
            <form method="post" action="<?php echo current_url(); ?>" enctype="multipart/form-data">
                <h1>Načíst osoby ze souboru</h1>
    
                <div class="rs_form__body__help">
    
                    <div class="tooltip tooltip--top tooltip--help">
                        <span class="tooltip__handle">?</span>
                        <div class="tooltip__content" style="display: none;">
                            <p>
                                Pro hromadné načtení osob ke sledování lze využít souboru MS Excel ve formátu XLSX nebo CSV. Soubor uložte jako soubor s příponou .xlsx (.csv). 
                                Položky IČ a RČ musí být ve stejně označeném sloupci (IČ, RČ). Soubor musí odpovídat níže uvedenému vzoru.
                            </p>
                            <p>
                                Monitoring probíhá zejména podle IČ/RČ a podpůrně podle názvu/jména (vložíte-li jej, což doporučujeme). Monitoring 
                                fyzických osob bez RČ probíhá pouze při současném zadání datumu narození a jména.
                            </p>
                        </div>
                    </div>
    
                </div>

                <?php
                    $afterImportFrequencyId = $this->config->item('NOTIFICATION_FREQUENCY_AFTER_IMPORT_ID');
                    $is_insolvencewatch = $userinfo['is_insolvencewatch'] == true;
                    $is_vatdebtorswatch = $userinfo['is_vatdebtorswatch'] == true;
                    $is_likvidacewatch = $userinfo['is_likvidacewatch'] == true;
                    $is_accountchangewatch = $userinfo['is_accountchangewatch'] == true;
                    $is_claimswatch = $userinfo['is_claimswatch'] == true;
                    $is_orwatch = $userinfo['is_orwatch'] == true;
                    $is_orskwatch = $userinfo['is_orskwatch'] == true;

                    $hasSpisNotification = $is_insolvencewatch && $userinfo['spis_notification_frequency_id'] == $afterImportFrequencyId;
                    $hasVatdebtorsNotification = $is_vatdebtorswatch && $userinfo['vatdebtors_notification_frequency_id'] == $afterImportFrequencyId;
                    $hasLikvidaceNotification = $is_likvidacewatch && $userinfo['likvidace_notification_frequency_id'] == $afterImportFrequencyId;
                    $hasAccountsNotification = $is_accountchangewatch && $userinfo['accounts_notification_frequency_id'] == $afterImportFrequencyId;
                    $hasClaimsNotification = $is_claimswatch && $userinfo['claims_notification_frequency_id'] == $afterImportFrequencyId;
                    $hasOrNotification = $is_orwatch && $userinfo['or_notification_frequency_id'] == $afterImportFrequencyId;
                    $hasOrskNotification = $is_orskwatch && $userinfo['orsk_notification_frequency_id'] == $afterImportFrequencyId;

                    $hasIfilterNotification = $is_insolvencewatch && $userinfo['ifilter_notification_frequency_id'] == $afterImportFrequencyId;

                    $hasAnyAfterImportNotification = $hasSpisNotification
                        || $hasVatdebtorsNotification
                        || $hasLikvidaceNotification
                        || $hasAccountsNotification
                        || $hasClaimsNotification
                        || $hasOrNotification
                        || $hasOrskNotification
                        || $hasIfilterNotification;

                    if (isset($importpassed) && isset($importid) && $importpassed && isset($result)) {
                        $result .= '<br />';
                        $result .= '<br />';
                        $result .= 'Bližší informace neleznete v ';
                        $result .= '<a href="monitoring-rejstriku/import-report/'. $importid .'" title="Report insolvence" style="text-decoration: underline;">';
                        $result .= 'reportu insolvence importovaných subjektů';
                        $result .= '</a>';
                    }

                    $msg['message'] = isset($result) ? $result : '';
                    $this->load->view('inc/message', $msg);
                    if ($msg['message'] != '') {
                        echo '<br /><br />';
                    }

                    if (isset($importpassed) && $importpassed && $hasAnyAfterImportNotification) {

                        echo '<div class="warning" id="notification-sending-message">';
                        echo '<img src="images/ajax-horizontal-loader.gif" alt="" class="progressbar" /><br />';
                        echo 'Probíhá generování a zasílání reportu pro:<br />';
                        if ($hasSpisNotification) {
                            echo $this->config->item('SPIS_NOTIFICATION_NAME').'<br />';
                        }
                        if ($hasVatdebtorsNotification) {
                            echo $this->config->item('VATDEBTORS_NOTIFICATION_NAME').'<br />';
                        }
                        if ($hasLikvidaceNotification) {
                            echo $this->config->item('LIKVIDACE_NOTIFICATION_NAME').'<br />';
                        }
                        if ($hasAccountsNotification) {
                            echo $this->config->item('ACCOUNTS_NOTIFICATION_NAME').'<br />';
                        }
                        if ($hasClaimsNotification) {
                            echo $this->config->item('CLAIMS_NOTIFICATION_NAME').'<br />';
                        }
                        if ($hasOrNotification) {
                            echo $this->config->item('OR_NOTIFICATION_NAME').'<br />';
                        }
                        if ($hasOrskNotification) {
                            echo $this->config->item('ORSK_NOTIFICATION_NAME').'<br />';
                        }
                        if ($hasIfilterNotification) {
                            echo $this->config->item('IFILTER_NOTIFICATION_NAME').'<br />';
                        }

                        echo '</div>';

                        echo '<script type="text/javascript">';

                        echo '$(function() {';
                            echo 'var messageDiv = $("#notification-sending-message");';
                            
                            echo '$.get('. base_url() .'monitoring-rejstriku/import-notifikace", function(message) {';
                                echo 'messageDiv.html(message);';
                            echo '}).fail(function() {';
                                echo 'messageDiv.html(';
                                    echo '\'<span class="error">Zasílání reportu selhalo, opakujte operaci nebo nás kontaktujte na <a href="'. $this->config->item('CONTACT_EMAIL') .'">'. $this->config->item('CONTACT_EMAIL') .'</a></span>.\');';
                            echo '});';
                        echo '});';

                        echo '<script>';
                    }
                ?>


                <div class="rs_form__body__smaller">
                    <p>
                        Formát nahrávaného souboru musí odpovídat vzoru:
                        <a href="files/vzor.xlsx">xlsx</a>, 
                        <a href="files/vzor.xls">xls</a> nebo 
                        <a href="files/vzor.csv">csv</a>.
                    </p>
                </div>
                <hr/>
                <div class="rs_form__body__smaller">
    
                <div class="form_control_wrap form_control_wrap--padded">
                    <label class="alignleft">Nahrát soubor</label>
    
                    <input type="submit" class="form_submit form_submit--alignright form_submit--respo form_submit--respo_desktop" name="s" value="Vložit"/>

                    <div class="form_control_wrap form_control_wrap--space-right form_control_wrap--block">
                        <input type="file" class="styled_file" id="ms_soubor" name="import"/>

                        <label for="ms_soubor">
                            <span class="btn_area" data-title="procházet"></span>
                            <span class="btn_desc">Soubor nevybrán</span>
                        </label>

                        <?php
                            $delete_missing_checked = "";

                            if ((isset($_POST['submit']) && $this->input->post('delete-missing') == "1")
                                || (!isset($_POST['submit']) && $userinfo['import_delete_missing_default'] == "1")) {
                                $delete_missing_checked = ' checked="checked"';
                            }
                        ?>

                        <input type="checkbox" class="rs_styled_checkbox" id="ms_vymazat" value="1"<?php echo $delete_missing_checked; ?> name="delete-missing" />
                        <label for="ms_vymazat">
                            <span class="btn_area"></span>
                            <span class="btn_desc">Smazat subjekty, které nejsou v souboru</span>
                        </label>

                        <br /><br />
                        <div>
                            <div class="rs_styled_select">
                                <select name="datasource">
                                    <?php
                                        foreach($datasources as $i) {
                                            echo '<option value="'. $i['id'] .'"'. set_select('datasource', $i['id'], isset($_POST['datasource']) == false && $i['id'] == $userinfo['data_source_id']) .'>Zdroj dat - '. $i['name'] .'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php
                        if ($hasAnyAfterImportNotification) {
                    ?>
                    <div id="report-after-import">
                        Dle <a href="monitoring-rejstriku/nastaveni">nastavení upozorňování</a> Vám bude po dokončení 
                        importu zaslán report pro:
                        <ul>
                            <?php 
                                if ($hasSpisNotification) {
                                    echo '<li>'. $this->config->item('SPIS_NOTIFICATION_NAME') .'</li>';
                                }
                                if ($hasLikvidaceNotification) {
                                    echo '<li>'. $this->config->item('LIKVIDACE_NOTIFICATION_NAME') .'</li>';
                                }
                                if ($hasIfilterNotification) {
                                    echo '<li>'. $this->config->item('IFILTER_NOTIFICATION_NAME') .'</li>';
                                }
                                if ($hasVatdebtorsNotification) {
                                    echo '<li>'. $this->config->item('VATDEBTORS_NOTIFICATION_NAME') .'</li>';
                                }
                                if ($hasAccountsNotification) {
                                    echo '<li>'. $this->config->item('ACCOUNTS_NOTIFICATION_NAME') .'</li>';
                                }
                            ?>
                        </ul>
                        Report bude obsahovat přehled nových událostí, které vznikli od posledního reportu.
                    </div>
                    <?php
                        }
                    ?>

    
                    <input type="submit" class="form_submit form_submit--alignright form_submit--respo form_submit--respo_mobile" name="s" value="Vložit"/>
                </div>
    
    
                    <p>
                        <strong class="error">Upozornění:</strong> monitoring probíhá zejména podle IČ/RČ a podpůrně podle
                        názvu/jména (vložíte-li jej, což doporučujeme). Monitoring fyzických osob bez RČ probíhá pouze při
                        současném zadání datumu narození a jména.
                    </p>
    
                </div>
            </form>
        </div>

    </div>
</main>