<main>
        
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body longlabels">
            <form action="<?php echo current_url(); ?>" method="post">
                <h1>
                    Založení nové společnosti
                </h1>

                <?php
                    if (isset($result)) {
                        $msg['message'] = $result;
                        $this->load->view('inc/message', $msg);
                        echo '<br /><br />';
                    }
                ?>
    
                <h2>O JAKOU SPOLEČNOST MÁTE ZÁJEM?</h2>

                <ul>
                    <li>
                        <article class="rs_hp__cenik__item">
                            <div class="form_control_wrap">
                                <input type="checkbox" class="rs_styled_checkbox" name="sro_basic" id="sro_basic" <?php echo set_checkbox('sro_basic', 1); ?>
                                        value="1"/>
                                <label for="sro_basic">
                                    <span class="btn_area"></span>
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_CREATE_SROBASIC'); ?></span>
                                    <div class="rs_hp__cenik__item__price">7.500 Kč</div>
                                    <p>základní parametry, živnost volná, cena komplet (včetně notáře a kolků)</p>

                                </label>
                            </div>
                        </article>
                    </li>
                    <li>
                        <article class="rs_hp__cenik__item">
                            <div class="form_control_wrap">
                                <input type="checkbox" class="rs_styled_checkbox" name="sro_extra" id="sro_extra" <?php echo set_checkbox('sro_extra', 1); ?>
                                        value="1" />
                                <label for="sro_extra">
                                    <span class="btn_area"></span>
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_CREATE_SROEXTRA'); ?></span>
                                    <div class="rs_hp__cenik__item__price">8.500 Kč</div>
                                    <p>zvláštní parametry, koncesovaná živnost apod.</p>
                                </label>
                            </div>
                        </article>
                    </li>
                    <li>
                        <article class="rs_hp__cenik__item">
                            <div class="form_control_wrap">
                                <input type="checkbox" class="rs_styled_checkbox" name="osvc_to_sro" id="osvc_to_sro" <?php echo set_checkbox('osvc_to_sro', 1); ?>
                                        value="1" />
                                <label for="osvc_to_sro">
                                    <span class="btn_area"></span>
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_CREATE_OSCVTOSRO'); ?></span>
                                    <div class="rs_hp__cenik__item__price witharrow">upřesnit zadání</div>
                                    <p>převod podnikání na nové s.r.o.</p>
                                </label>
                            </div>
                        </article>
                    </li>
                    <li>
                        <article class="rs_hp__cenik__item">
                            <div class="form_control_wrap">
                                <input type="checkbox" class="rs_styled_checkbox" name="as_basic" id="as_basic" <?php echo set_checkbox('as_basic', 1); ?>
                                        value="1" />
                                <label for="as_basic">
                                    <span class="btn_area"></span>
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_CREATE_ASBASIC'); ?></span>
                                    <div class="rs_hp__cenik__item__price">23.500 Kč</div>
                                    <p>základní parametry, živnost volná, cena komplet (včetně notáře a kolků)</p>
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
                                    <span class="btn_desc"><?php echo $this->config->item('REGSERVIS_CREATE_OTHER'); ?></span>
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