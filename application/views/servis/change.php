<main>
        
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body longlabels">
            <h1>
                Změna údajů v obchodním rejstříku
                <br /><small>Požadujete změnit zápis v obchodním rejstříku? Jste správně!</small>
            </h1>

            <?php
                if (isset($result)) {
                    $msg['message'] = $result;
                    $this->load->view('inc/message', $msg);
                    echo '<br /><br />';
                }
            ?>
    
            <h2>VYHLEDEJTE SPOLEČNOST</h2>

            <div class="form_control_wrap form_control_wrap--multi">
                <input type="text" class="form_control" name="company_ic_input" placeholder="Zadejte IČ" value="<?php echo set_value('company_ic'); ?>" />
                <button type="button" name="q">
                    <svg xmlns="http://www.w3.org/2000/svg" width="29.97" height="21.75" viewBox="0 0 29.97 21.75">
                        <path id="Search" class="cls-1"
                                d="M1281.62,223.388a0.881,0.881,0,0,1-.44-0.118l-11.83-6.83a0.881,0.881,0,0,1-.33-1.2,7.9,7.9,0,1,0-13.69-7.908,7.91,7.91,0,0,0,2.89,10.8,7.94,7.94,0,0,0,7.87.023,0.884,0.884,0,1,1,.88,1.533,9.722,9.722,0,0,1-9.63-.027,9.671,9.671,0,1,1,13.62-4.324l11.1,6.408A0.884,0.884,0,0,1,1281.62,223.388Z"
                                transform="translate(-1252.5 -201.625)"></path>
                    </svg>
                </button>
            </div>

            <form action="<?php echo current_url(); ?>" method="post">

                <section id="search_results" class="rs_form__body__search-results">
                    <h3>
                        SPOLEČNOST: <span id="company_name"><?php echo set_value('company_name'); ?></span>
                        <input type="hidden" name="company_name" value="<?php echo set_value('company_name'); ?>" />
                        <input type="hidden" name="company_ic" value="<?php echo set_value('company_ic'); ?>" />
                    </h3>
                </section>

                <h2>V ČEM ZÁPIS ZMĚNÍME?</h2>

                <ul>
                    <li>
                        <article class="rs_hp__cenik__item">
                            <div class="form_control_wrap">
                                <input type="checkbox" class="rs_styled_checkbox" name="alter_address" id="alter_address" <?php echo set_checkbox('alter_address', 1); ?>
                                        value="1"/>
                                <label for="alter_address">
                                    <span class="btn_area"></span>
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_ALTER_ADDRESS'); ?></span>
                                    <div class="rs_hp__cenik__item__price">1.800 Kč a kolek</div>
                                    <p>komplet podklady a podání na soud</p>
                                </label>
                            </div>
                        </article>
                    </li>
                    <li>
                        <article class="rs_hp__cenik__item">
                            <div class="form_control_wrap">
                                <input type="checkbox" class="rs_styled_checkbox" name="alter_member" id="alter_member" <?php echo set_checkbox('alter_member', 1); ?>
                                        value="1"/>
                                <label for="alter_member">
                                    <span class="btn_area"></span>
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_ALTER_MEMBER'); ?></span>
                                    <div class="rs_hp__cenik__item__price">1.800 Kč a kolek</div>
                                    <p>komplet podklady a podání na soud</p>
                                </label>
                            </div>
                        </article>
                    </li>
                    <li>
                        <article class="rs_hp__cenik__item">
                            <div class="form_control_wrap">
                                <input type="checkbox" class="rs_styled_checkbox" name="alter_prokura" id="alter_prokura" <?php echo set_checkbox('alter_prokura', 1); ?>
                                        value="1"/>
                                <label for="alter_prokura">
                                    <span class="btn_area"></span>
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_ALTER_PROKURA'); ?></span>
                                    <div class="rs_hp__cenik__item__price">1.800 Kč a kolek</div>
                                    <p>komplet podklady a podání na soud</p>
                                </label>
                            </div>
                        </article>
                    </li>
                    <li>
                        <article class="rs_hp__cenik__item">
                            <div class="form_control_wrap">
                                <input type="checkbox" class="rs_styled_checkbox" name="alter_agreement" id="alter_agreement" <?php echo set_checkbox('alter_agreement', 1); ?>
                                        value="1"/>
                                <label for="alter_agreement">
                                    <span class="btn_area"></span>
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_ALTER_AGREEMENT'); ?></span>
                                    <div class="rs_hp__cenik__item__price witharrow">upřesnit zadání</div>
                                    <p>s plnou mocí pro konkrétní změnu, včetně podání na soud</p>
                                </label>
                            </div>
                        </article>
                    </li>
                    <li>
                        <article class="rs_hp__cenik__item">
                            <div class="form_control_wrap">
                                <input type="checkbox" class="rs_styled_checkbox" name="other" id="other" <?php echo set_checkbox('other', 1); ?>
                                        value="1" data-toggle-form-element="other_text" />
                                <label for="other">
                                    <span class="btn_area"></span>
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_ALTER_OTHER'); ?></span>
                                </label>
                            </div>
                        </article>
                    </li>
                </ul>

                <div class="form_control_wrap <?php echo ((isset($_POST['other']) && $_POST['other'] == 1) ? '' : 'hide'); ?>">
                    <textarea class="form_control" name="other_text" id="other_text" placeholder="Jiný požadavek"><?php echo set_value('other_text'); ?></textarea>
                </div>
    
                <?php 
                    $d['userinfo'] = $userinfo;
                    $this->load->view('servis/contact_form', $d);
                ?>
            </form>
        </div>
    </div>
</main>

<script type="text/javascript">
    window.onload = function() {
        $("[name='company_ic_input'").keypress(function(e) {
            $("[name='company_ic").val($(this).val());

            if (e.which == 13) {
                findCompany();
            }
        });

        $("[name='q']").click(findCompany);

        function findCompany() {
            $.post(
                "<?php echo base_url(); ?>/rejstrikovy-servis/hledat", 
                { 'ic': $("[name='company_ic_input']").val() },
                function(result) {
                    if (result != '') {
                        $("#company_name").html(result);
                        $("[name='company_name").val(result);
                    } else {
                        $("#company_name").html('Nenalezena.');
                        $("[name='company_name").val('');
                    }
                });
        }
    }
</script>