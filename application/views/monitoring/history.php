<main>
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body">
            <h1>Historie upozorňování</h1>

            <p>
                Stránka zobrazuje upozornění za poslední 3 měsíce. Pro kontrolu starších upozornění nás prosím
                <a href="kontakt">kontaktujte</a>.
            </p>

            <?php
                if (sizeof($emails) == 0) {
                    echo '<p>Žádné notifikace za poslední 3 měsíce nebyly nalezeny.</p>';
                } else {
            ?>

            <div class="rs_form__table_filters">
                <span>FILTR</span>
                <br />
                <br />
                <ul>
                    <?php if ($userinfo['is_insolvencewatch'] || $userinfo['is_claimswatch']) { ?>
                    <li>
                        <input type="checkbox" class="rs_styled_checkbox" name="filter_isir" id="filter_isir"
                                value="1" checked/>
                        <label for="filter_isir">
                            <span class="btn_area"></span>
                            <span class="btn_desc">Insolvenční rejstřík</span>
                        </label>
                    </li>
                    <?php } ?>
                    <?php if ($userinfo['is_orwatch'] || $userinfo['is_orskwatch'] || $userinfo['is_likvidacewatch']) { ?>
                    <li>
                        <input type="checkbox" class="rs_styled_checkbox" name="filter_or" id="filter_or"
                                value="1" checked/>
                        <label for="filter_or">
                            <span class="btn_area"></span>
                            <span class="btn_desc">Obchodní rejstřík</span>
                        </label>
                    </li>
                    <?php } ?>
                    <?php if ($userinfo['is_vatdebtorswatch']) { ?>
                    <li>
                        <input type="checkbox" class="rs_styled_checkbox" name="filter_vat" id="filter_vat"
                                value="1" checked/>
                        <label for="filter_vat">
                            <span class="btn_area"></span>
                            <span class="btn_desc">Neplatiči DPH</span>
                        </label>
                    </li>
                    <?php } ?>
                    <?php if ($userinfo['is_accountchangewatch']) { ?>
                    <li>
                        <input type="checkbox" class="rs_styled_checkbox" name="filter_account" id="filter_account"
                                value="1" checked/>
                        <label for="filter_account">
                            <span class="btn_area"></span>
                            <span class="btn_desc">Změna účtu DPH</span>
                        </label>
                    </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="respo-table">
                <table>
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Email</th>
                            <th>Předmět</th>
                            <th>Příloha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($emails as $i) {
                                $type = '';

                                //TODO: ADD REAL SUBJECTS HERE
                                if ($i['subject'] == 'ISIR - nové události' || $i['subject'] == 'Nové pohledávky') {
                                    $type = 'filter_isir';
                                } else if ($i['subject'] == 'Zapomenuté heslo') {
                                    $type = 'filter_vat';
                                } else if ($i['subject'] == 'Zapomenuté heslo123') {
                                    $type = 'filter_account';
                                } else if ($i['subject'] == 'Zapomenuté heslo456') {
                                    $type = 'filter_or';
                                }

                                echo '<tr class="'. $type .'">';

                                echo '<td>'. date("d.m.Y H:i", strtotime($i['date'])) .'</td>';
                                echo '<td>'. $i['email'] .'</td>';
                                echo '<td>'. $i['subject'] .'</td>';
                                echo '<td>';
                                
                                if ($i['attachment'] != '' && $i['attachment'] != NULL) {
                                    $attachment = basename($i['attachment']);
                                    echo '<a style="text-decoration: underline !important;" href="files/emailing/'. $attachment .'">';
                                    echo $attachment;
                                    echo '</a>';
                                } else {
                                    echo 'bez příloh';
                                }
                                
                                echo '</td>';

                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <script type="text/javascript">
                window.onload = function(e) { 
                    $("#filter_account").change(function() {
                        toggleRows("#filter_account", "filter_account");
                    });

                    $("#filter_isir").change(function() {
                        toggleRows("#filter_isir", "filter_isir");
                    });

                    $("#filter_or").change(function() {
                        toggleRows("#filter_or", "filter_or");
                    });

                    $("#filter_vat").change(function() {
                        toggleRows("#filter_vat", "filter_vat");
                    });

                    function toggleRows(checkSelector, className) {
                        if ($(checkSelector).is(':checked')) {
                            $("tr." + className).fadeIn();
                        } else {
                            $("tr." + className).fadeOut();
                        }
                    }
                };
            </script>
            
            <?php
                }
            ?>

        </div>

    </div>
</main>