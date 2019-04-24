<div class="ms_hp__cenik__slider">

<p><strong>Kolik subjektů budete sledovat?</strong></p>
<div class="ms_hp__slider_wrap" id="ms_hp__slider_wrap"></div>
<ul class="ms_hp__cenik__slider__nav">
    <?php
        foreach ($this->config->item('USER_ACCOUNTS') as $a) {
            echo '<li>'. $a['subjects'] .'</li>';
        }
    ?>
    <li>na míru</li>
</ul>

<ul class="two_cols">
    <li><p><strong>Co budeme monitorovat?</strong></p>
        <ul class="ms_hp__cenik__subject">
            <li><p class="mb-2 mt-5"><strong>INSOLVENČNÍ REJSTŘÍK</strong></p></li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" name="is_insolvencewatch"
                           id="is_insolvencewatch"
                           value="1"
                           <?php echo $register_session['is_insolvencewatch'] ? 'checked' : ''; ?> />
                    <label for="is_insolvencewatch">
                        <span class="btn_area"></span>
                        <span class="btn_desc">INSOLVENCE</span>
                        <span class="tooltip">
                            <span class="tooltip__handle">?</span>
                            <span class="tooltip__content">
                                Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                                ut aliquip ex ea commodo consequat.
                            </span>
                        </span>
                    </label>
                </div>
            </li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" name="is_claimswatch"
                            id="is_claimswatch"
                            value="1"
                            <?php echo $register_session['is_claimswatch'] ? 'checked' : ''; ?> />
                    <label for="is_claimswatch">
                        <span class="btn_area"></span>
                        <span class="btn_desc">PŘÍHLÁŠENÉ POHLEDÁVKY (+10% ceny)</span>
                        <span class="tooltip">
                            <span class="tooltip__handle">?</span>
                            <span class="tooltip__content">
                            Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                                ut aliquip ex ea commodo consequat.
                            </span>
                        </span>
                    </label>
                </div>
            </li>

            <li><p class="mb-2 mt-5"><strong>OBCHODNÍ REJSTŘÍK - ČR</strong></p></li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" name="is_orwatch"
                            id="is_orwatch"
                            value="1"
                            <?php echo $register_session['is_orwatch'] ? 'checked' : ''; ?> />
                    <label for="is_orwatch">
                        <span class="btn_area"></span>
                        <span class="btn_desc">ZMĚNY SPOLEČNOSTÍ</span>
                        <span class="tooltip">
                            <span class="tooltip__handle">?</span>
                            <span class="tooltip__content">
                            Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                                ut aliquip ex ea commodo consequat.
                            </span>
                        </span>
                    </label>
                </div>
            </li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" name="is_likvidacewatch"
                            id="is_likvidacewatch"
                            value="1"
                            <?php echo $register_session['is_likvidacewatch'] ? 'checked' : ''; ?> />
                    <label for="is_likvidacewatch">
                        <span class="btn_area"></span>
                        <span class="btn_desc">LIKVIDACE (+10% ceny)</span>
                        <span class="tooltip">
                            <span class="tooltip__handle">?</span>
                            <span class="tooltip__content">
                            Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                                ut aliquip ex ea commodo consequat.
                            </span>
                        </span>
                    </label>
                </div>
            </li>

            <li><p class="mb-2 mt-5"><strong>OBCHODNÍ REJSTŘÍK - SLOVENSKO</strong></p></li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" name="is_orskwatch"
                            id="is_orskwatch"
                            value="1"
                            <?php echo $register_session['is_orskwatch'] ? 'checked' : ''; ?> />
                    <label for="is_orskwatch">
                        <span class="btn_area"></span>
                        <span class="btn_desc">ZMĚNY SPOLEČNOSTÍ</span>
                        <span class="tooltip">
                            <span class="tooltip__handle">?</span>
                            <span class="tooltip__content">
                            Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                                ut aliquip ex ea commodo consequat.
                            </span>
                        </span>
                    </label>
                </div>
            </li>

            <li><p class="mb-2 mt-5"><strong>DPH A BANKOVNÍ ÚČTY</strong></p></li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" name="is_vatdebtorswatch"
                            id="is_vatdebtorswatch"
                            value="1"
                            <?php echo $register_session['is_vatdebtorswatch'] ? 'checked' : ''; ?> />
                    <label for="is_vatdebtorswatch">
                        <span class="btn_area"></span>
                        <span class="btn_desc">NEPLATIČE DPH (+10% ceny)</span>
                        <span class="tooltip">
                            <span class="tooltip__handle">?</span>
                            <span class="tooltip__content">
                                Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                                    ut aliquip ex ea commodo consequat.
                            </span>
                        </span>
                    </label>
                </div>
            </li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" name="is_accountchangewatch"
                            id="is_accountchangewatch"
                            value="1"
                            <?php echo $register_session['is_accountchangewatch'] ? 'checked' : ''; ?> />
                    <label for="is_accountchangewatch">
                        <span class="btn_area"></span>
                        <span class="btn_desc">ZMĚNY BANKOVNÍCH ÚČTŮ (+10% ceny)</span>
                        <span class="tooltip">
                            <span class="tooltip__handle">?</span>
                            <span class="tooltip__content">
                            Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                                ut aliquip ex ea commodo consequat.
                            </span>
                        </span>
                    </label>
                </div>
            </li>

        </ul>
    </li>
    <li><p><strong>Využijte slevy!</strong></p>
        <ul class="ms_hp__cenik__subject">
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" name="discount_year"
                            id="discount_year"
                            value="1"
                            <?php echo $register_session['discount_year'] ? 'checked' : ''; ?> />
                    <label for="discount_year">
                        <span class="btn_area"></span>
                        <span class="btn_desc">Roční platba (<strong>ušetříte <span
                                id="discount_year_value"></span></strong>)</span>
                    </label>
                </div>
            </li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" name="discount_lawyer"
                            id="discount_lawyer"
                            value="1"
                            <?php echo $register_session['discount_lawyer'] ? 'checked' : ''; ?> />
                    <label for="discount_lawyer">
                        <span class="btn_area"></span>
                        <span class="btn_desc">advokátní nebo insolvenční kancelář (<strong>ušetříte <span
                                id="discount_lawyer_value"></span></strong>)</span>
                    </label>
                </div>
            </li>

            <li><p class="mb-2 mt-5">&nbsp;</p></li>
            <li><p class="mb-2 mt-5"><strong>Potřebujete program 'na míru'?</strong></p></li>

            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" disabled
                            value="1" />
                    <label>
                        <span class="btn_area list-like"></span>
                        <span class="btn_desc">
                            s námi lze sledovat jakýkoliv počet subjektů
                        </span>
                    </label>
                </div>
            </li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" disabled
                            value="1" />
                    <label>
                        <span class="btn_area list-like"></span>
                        <span class="btn_desc">
                            chcete-li sledovat víc subjektů než je v ponuce, tak nás prosím 
                            <a href="kontakt">kontaktujte</a>
                        </span>
                    </label>
                </div>
            </li>

            <li><p class="mb-2 mt-5"><strong>Potřebujete využívat naše API?</strong></p></li>

            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" disabled
                            value="1" />
                    <label>
                        <span class="btn_area list-like"></span>
                        <span class="btn_desc">
                            pomocí API lze propojit Vaše CRM, ERP s námi
                        </span>
                    </label>
                </div>
                
            </li>

            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" disabled
                            value="1" />
                    <label>
                        <span class="btn_area list-like"></span>
                        <span class="btn_desc">
                            pro propojení přes API je k dispozici <a href="api">specifikace</a>
                        </span>
                    </label>
                </div>
                
            </li>

            <li><p class="mb-2 mt-5"><strong>Potřebujete kompletní obraz rejstříku?</strong></p></li>

            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" disabled
                            value="1" />
                    <label>
                        <span class="btn_area list-like"></span>
                        <span class="btn_desc">
                            denně dodáme kompletní obraz rejstříku ve formé CSV, XML, JSON
                        </span>
                    </label>
                </div>
                
            </li>
            <li>
                <div class="form_control_wrap__label">
                    <input type="checkbox" class="rs_styled_checkbox" disabled
                            value="1" />
                    <label>
                        <span class="btn_area list-like"></span>
                        <span class="btn_desc">
                            pro realizaci nás prosím 
                            <a href="kontakt">kontaktujte</a>
                        </span>
                    </label>
                </div>
                
            </li>

        </ul>
    </li>
</ul>




</div>
<div class="ms_hp__cenik__pricing">
<br />
<br />
<br />
<br />
<br />
<br />
<p class="ms_hp__cenik__pricing__members">
    Maximální počet<br />
    sledovaných subjektů<br/>
    <strong><span id="ms_hp__cenik__output-members">120</span></strong>
</p>
<p class="ms_hp__cenik__pricing__price">
    Celkem měsíčně<br/>
    <strong><span id="ms_hp__cenik__output-price">1.200</span> <span id="ms_hp__cenik__output-price-info"> Kč
        <small>(bez DPH)</small></span>
    </strong>
</p>
</div>

<script type="text/javascript">
    window.onload = function(e) { 
        var startValue = <?php echo $register_session['account']; ?>;
        var registerId = <?php echo $registerId; ?>;
        var slider = document.getElementById('ms_hp__slider_wrap');

        var accountSlider = noUiSlider.create(slider, {
            start: startValue,
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': <?php echo sizeof($this->config->item('USER_ACCOUNTS')); ?>
            }
        });
        
        accountSlider.on('change', function (values) {
            formChange();
        });

        var submitButton = $('#changeProgramSubmit');
        var submitButtonHtml = submitButton.html();
        var submitButtonHref = submitButton.attr('href');

        function formChange() {
            var postData = {
                'account': parseInt(accountSlider.get(), 10),
                'discount_lawyer': $("#discount_lawyer").is(':checked') ? '1' : '0',
                'discount_year': $("#discount_year").is(':checked') ? '1' : '0',
                'is_insolvencewatch': $("#is_insolvencewatch").is(':checked') ? '1' : '0',
                'is_claimswatch': $("#is_claimswatch").is(':checked') ? '1' : '0',
                'is_orwatch': $("#is_orwatch").is(':checked') ? '1' : '0',
                'is_likvidacewatch': $("#is_likvidacewatch").is(':checked') ? '1' : '0',
                'is_orskwatch': $("#is_orskwatch").is(':checked') ? '1' : '0',
                'is_vatdebtorswatch': $("#is_vatdebtorswatch").is(':checked') ? '1' : '0',
                'is_accountchangewatch': $("#is_accountchangewatch").is(':checked') ? '1' : '0'
            };

            if (postData.account < <?php echo sizeof($this->config->item('USER_ACCOUNTS')); ?>) {
                $.post('<?php echo base_url(); ?>register/calculateprice/' + registerId, postData, function(data) {
                    data = JSON.parse(data);
                    $('#ms_hp__cenik__output-members').text(data['max_subjects']);
                    $('#ms_hp__cenik__output-price').text(data['totalprice']);
                    $("#ms_hp__cenik__output-price-info").show();
                    $('#discount_year_value').text(data['price_discount_year'] + ' Kč');
                    $('#discount_lawyer_value').text(data['price_discount_lawyer'] + ' Kč');

                    submitButton.attr('href', submitButtonHref);
                    submitButton.html(submitButtonValue);
                });
            } else {
                $('#ms_hp__cenik__output-members').text('dle dohody');
                $('#ms_hp__cenik__output-price').text('dohodou');
                $("#ms_hp__cenik__output-price-info").hide();
                $('#discount_year_value').text('6%');
                $('#discount_lawyer_value').text('5%');

                submitButton.attr('href', 'kontakt');
                submitButton.html('value', 'Kontaktovat');
            }
        }

        $("#discount_lawyer").change(formChange);
        $("#discount_year").change(formChange);

        $("#is_insolvencewatch").change(formChange);
        $("#is_claimswatch").change(formChange);
        $("#is_orwatch").change(formChange);
        $("#is_likvidacewatch").change(formChange);
        $("#is_orskwatch").change(formChange);
        $("#is_vatdebtorswatch").change(formChange);
        $("#is_accountchangewatch").change(formChange);

        formChange();
    };
</script>