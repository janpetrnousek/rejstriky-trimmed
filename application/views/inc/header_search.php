<header class="main_header">
    <?php $this->load->view('inc/header_topbar'); ?>
    <div class="inner">
        <form action="search" method="post" role="search" class="main_header__search" id="search-form">
            <ul class="main_header__search__tabs">
                <li class="main_header__search__tabs__tab--active">
                    <div class="tooltip">
                        <input type="radio" name="type" value="<?php echo $this->config->item('SEARCH_SCREENING'); ?>" id="type_lustrace" checked/>
                        <label for="type_lustrace" class="tooltip__handle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21.438" height="21.594"
                                 viewBox="0 0 21.438 21.594">
                                <path id="Check" class="cls-1"
                                      d="M345.254,142.145a9.456,9.456,0,1,1-9.386,9.445,9.426,9.426,0,0,1,9.386-9.445m0-1.349a10.795,10.795,0,1,0,10.727,10.794A10.761,10.761,0,0,0,345.254,140.8h0Zm-1.528,14.658a0.668,0.668,0,0,1-.949,0l-3.318-3.338a0.677,0.677,0,0,1,0-.954,0.666,0.666,0,0,1,.948,0l3.319,3.339A0.678,0.678,0,0,1,343.726,155.454Zm-0.945-.021a0.668,0.668,0,0,0,.948,0l6.755-6.838a0.677,0.677,0,0,0,0-.954,0.668,0.668,0,0,0-.948,0l-6.755,6.839A0.676,0.676,0,0,0,342.781,155.433Z"
                                      transform="translate(-334.531 -140.781)"/>
                            </svg>
                            Lustrace
                        </label>
                        <div class="tooltip__content">
                            <p>
                                Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="tooltip">
                        <input type="radio" name="type" value="<?php echo $this->config->item('SEARCH_DATA'); ?>" id="type_rejstriky"/>
                        <label for="type_rejstriky" class="tooltip__handle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16.687" height="22.812"
                                 viewBox="0 0 16.687 22.812">
                                <path id="Doc" class="cls-1"
                                      d="M489.986,162.391h-15.4a0.66,0.66,0,0,1-.647-0.671V140.267a0.659,0.659,0,0,1,.647-0.67h2.318a0.67,0.67,0,0,1,0,1.34h-1.67V161.05h14.1V140.937h-9.818a0.67,0.67,0,0,1,0-1.34h10.465a0.659,0.659,0,0,1,.648.67V161.72A0.66,0.66,0,0,1,489.986,162.391Zm-3.07-14.5H477.85a0.537,0.537,0,0,1,0-1.073h9.066A0.537,0.537,0,0,1,486.916,147.891Zm-0.177,2.98h-8.712a0.537,0.537,0,0,1,0-1.073h8.712A0.537,0.537,0,0,1,486.739,150.871Zm-3.687,2.98H478.2a0.536,0.536,0,0,1,0-1.072h4.848A0.536,0.536,0,0,1,483.052,153.851Zm3.687,0h-1.518a0.536,0.536,0,0,1,0-1.072h1.518A0.536,0.536,0,0,1,486.739,153.851Z"
                                      transform="translate(-473.938 -139.594)"/>
                            </svg>
                            údaje z rejstříků
                        </label>
                        <div class="tooltip__content">
                            <p>
                                Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="tooltip">
                        <input type="radio" name="type" value="<?php echo $this->config->item('SEARCH_RELATIONS'); ?>" id="type_vazby"/>
                        <label for="type_vazby" class="tooltip__handle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10.563" height="23.344"
                                 viewBox="0 0 10.563 23.344">
                                <path id="link" class="cls-1"
                                      d="M686.005,159.776a3.622,3.622,0,0,1-.573.75,3.543,3.543,0,0,1-2.529,1.055h-0.327a3.593,3.593,0,0,1-3.577-3.6v-8.555a3.593,3.593,0,0,1,3.577-3.6H682.9a3.592,3.592,0,0,1,3.577,3.6v0.756a0.669,0.669,0,1,1-1.337,0v-0.756a2.248,2.248,0,0,0-.656-1.595,2.214,2.214,0,0,0-1.584-.66h-0.327a2.251,2.251,0,0,0-2.24,2.256v8.555a2.25,2.25,0,0,0,2.24,2.255H682.9a2.215,2.215,0,0,0,1.584-.661,2.244,2.244,0,0,0,.656-1.594v-1.911a0.669,0.669,0,1,1,1.337,0v1.911A3.608,3.608,0,0,1,686.005,159.776Zm3.07-7.569a3.575,3.575,0,0,1-3.1,1.806h-0.327a3.593,3.593,0,0,1-3.577-3.6v-0.757a0.669,0.669,0,1,1,1.337,0v0.757a2.248,2.248,0,0,0,.656,1.595,2.216,2.216,0,0,0,1.584.66h0.327a2.25,2.25,0,0,0,2.24-2.255v-8.555a2.25,2.25,0,0,0-2.24-2.255h-0.327a2.213,2.213,0,0,0-1.584.661,2.244,2.244,0,0,0-.656,1.594v1.911a0.669,0.669,0,1,1-1.337,0v-1.911a3.589,3.589,0,0,1,1.048-2.546,3.541,3.541,0,0,1,2.529-1.055h0.327a3.543,3.543,0,0,1,2.529,1.055,3.591,3.591,0,0,1,1.048,2.546v8.555A3.6,3.6,0,0,1,689.075,152.207Z"
                                      transform="translate(-679 -138.25)"/>
                            </svg>

                            Podnikatelské vazby
                        </label>
                        <div class="tooltip__content">
                            <p>
                                Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="main_header__search__input">
                <input type="search" 
                       name="name" 
                       placeholder="Subjekt" 
                       id="subject-search"
                       maxlength="<?php echo $this->config->item('SEARCH_NAME_LENGTH'); ?>"
                       value="<?php echo set_value('name', isset($search->name) ? $search->name : ''); ?>" />

                <a href="#" class="main_header__search__advanced"><span>Rozšířené vyhledávání</span></a>

                <button type="submit" name="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="29.97" height="21.75" viewBox="0 0 29.97 21.75">
                        <path id="Search" class="cls-1"
                              d="M1281.62,223.388a0.881,0.881,0,0,1-.44-0.118l-11.83-6.83a0.881,0.881,0,0,1-.33-1.2,7.9,7.9,0,1,0-13.69-7.908,7.91,7.91,0,0,0,2.89,10.8,7.94,7.94,0,0,0,7.87.023,0.884,0.884,0,1,1,.88,1.533,9.722,9.722,0,0,1-9.63-.027,9.671,9.671,0,1,1,13.62-4.324l11.1,6.408A0.884,0.884,0,0,1,1281.62,223.388Z"
                              transform="translate(-1252.5 -201.625)"/>
                    </svg>
                </button>
            </div>

            <div class="main_header__search__more-box">
                <input type="checkbox" 
                       class="styled_checkbox" 
                       name="name_exact" 
                       value="1" 
                       <?php echo set_checkbox('name_exact', '1', isset($search->name_exact) && $search->name_exact == '1'); ?>
                       id="exact_search"/> 
                <label
                    for="exact_search" class="main_header__search__exact"><span class="btn_area"></span><span
                    class="btn_desc">Hledat přesný název</span></label>
                <ul>
                    <li>
                        <input type="text" 
                        class="form_control" 
                        name="ic" 
                        placeholder="IČ" 
                        value="<?php echo set_value('ic', isset($search->ic) ? $search->ic : ''); ?>"
                        maxlength="<?php echo $this->config->item('SEARCH_IC_LENGTH'); ?>"/>
                    </li>
                    <li>
                        <input type="text" 
                        class="form_control" 
                        name="rc" 
                        placeholder="Rodné číslo (včetně lomítka)" 
                        value="<?php echo set_value('rc', isset($search->rc) ? $search->rc : ''); ?>"
                        maxlength="<?php echo $this->config->item('SEARCH_RC_LENGTH'); ?>"/>
                    </li>
                    <li>
                        <div class="styled_select">
                            <select name="law_form_id">
                                <option value="-1" disabled="disabled" selected="selected">Právní forma</option>                                
                                <option value="0"></option>
                                <?php
                                    foreach ($law_forms as $lf) {
                                        echo '<option value="'. $lf['id'] .'"'. set_select('law_form_id', $lf['id'], isset($search->law_form_id) && $search->law_form_id == $lf['id']) .'>'. $lf['name'] .'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </li>
                    <li class="half_width">
                        <input type="text" 
                        class="form_control" 
                        name="address" 
                        placeholder="Adresa" 
                        value="<?php echo set_value('address', isset($search->address) ? $search->address : ''); ?>"
                        maxlength="<?php echo $this->config->item('SEARCH_ADDRESS_LENGTH'); ?>"/>
                    </li>
                    <li class="half_width">
                        <input type="text" 
                        class="form_control" 
                        name="spis_mark" 
                        placeholder="Spisová značka (např. C 34253 nebo INS 3828/2008)" 
                        value="<?php echo set_value('spis_mark', isset($search->spis_mark) ? $search->spis_mark : ''); ?>"
                        maxlength="<?php echo $this->config->item('SEARCH_SPISMARK_LENGTH'); ?>"/>
                    </li>
                </ul>
            </div>
        </form>
    </div>
</header>
