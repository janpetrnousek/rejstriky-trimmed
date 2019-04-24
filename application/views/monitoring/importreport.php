<main>
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body">
            <h1>Report insolvence subjektů</h1>

            <p>
                Soubor: <strong><?php echo $filename; ?></strong>
            </p>

            <div class="respo-table">
                <table>
                    <thead>
                        <tr>
                            <th>Název subjektu</th>
                            <th>IČ</th>
                            <th>RČ</th>
                            <th>Datum narození</th>
                            <th>Insolvence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($subjects as $i) {
                                echo '<tr>';

                                echo '<td>';

                                $hasIsirRecord = sizeof($i->spisids) > 0;
                                $spis_id = $hasIsirRecord ? max($i->spisids) : null;

                                if ($hasIsirRecord) {
                                    echo '<a href="inr1/spis/'. $spis_id .'/A/import"><strong>'; //TODO: PUT PROPER URL HERE
                                }

                                echo $i->name;

                                if ($hasIsirRecord) {
                                    echo '</strong></a>';
                                }
                                echo '</td>';

                                echo '<td>';
                                echo ($i->ic != 0) ? '<span class="darkgrayText">'. formatIc($i->ic) .'</span>' : '';
                                echo '</td>';

                                echo '<td>';
                                echo ($i->rc != 0) ? '<span class="greenText">'. $i->rc .'</span>' : '';
                                echo '</td>';

                                echo '<td>';
                                echo ($i->birthdate != '0000-00-00' && $i->birthdate != '') ? date("d.m.Y", strtotime($i->birthdate)) : '';
                                echo '</td>';

                                echo '<td>';
                                if ($hasIsirRecord) {
                                    echo '<a href="inr1/spis/'. $spis_id .'/A/import" class="edit icon" title="Insolvence - Nahlédnout do spisu">'; //TODO: PUT PROPER URL HERE
                                    echo '<img src="images/icons/watched_table__docs--red.png" alt="insolvence" width="13" height="15"/>';
                                    echo '</a>';
                                }
                                echo '</td>';

                                echo '</tr>';
                            }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>