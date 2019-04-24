<main>
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body">
            <form action="<?php echo current_url(); ?>" method="post">
                <h1>Sledované osoby</h1>

                <div class="rs_form__body__help">
    
                    <div class="tooltip tooltip--top tooltip--help">
                        <span class="tooltip__handle">?</span>
                        <div class="tooltip__content" style="display: none;">
                            <p>
                                Vypsání subjektu <strong>tučným</strong> fontem znamená, že subjekt je nebo byl v
                                insolvenci. Pomlčka indikuje stav „bez záznamu“.<br/>
                                Ikona <img src="images/icons/watched_table__docs--red.png" alt="insolvence" width="13"
                                            height="15"> indikuje probíhající insolvenční řízení,<br/>
                                Ikona <img src="images/icons/watched_table__docs--violet.png" alt="likvidace" width="13"
                                            height="15"> indikuje vstup obchodní společnosti do likvidace,<br/>
                                Ikona <img src="images/icons/watched_table__docs--cyan.png" alt="neplatič DPH" width="13"
                                            height="15"> indikuje výskyt subjektu v seznamu neplatičů DPH,<br/>
                                Ikona <img src="images/icons/watched_table__docs--orange.png" alt="změna účtu" width="13"
                                            height="15"> indikuje změnu čísla účtu plátce DPH v uplynulých 30 dnech.<br/>
                                Pomocí ikon <img src="images/icons/watched_table__handle--edit.png" alt="upravit" width="17" height="17" /> a <img src="images/icons/watched_table__handle--delete-show.png" alt="vymazat" width="17" height="17" /> lze měnit údaje o sledované osobě nebo ji smazat.
    
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
    
                <div class="form_control_wrap form_control_wrap--multi">
                    <input type="text" class="form_control" value="<?php echo isset($fillintext) ? $fillintext : ''; ?>" name="name" placeholder="vyplňte jeden z údajů / jméno, IČ nebo RČ." />
                    <button type="submit" class="button_common" name="q">
                        <svg xmlns="http://www.w3.org/2000/svg" width="29.97" height="21.75" viewBox="0 0 29.97 21.75">
                            <path id="Search" class="cls-1"
                                    d="M1281.62,223.388a0.881,0.881,0,0,1-.44-0.118l-11.83-6.83a0.881,0.881,0,0,1-.33-1.2,7.9,7.9,0,1,0-13.69-7.908,7.91,7.91,0,0,0,2.89,10.8,7.94,7.94,0,0,0,7.87.023,0.884,0.884,0,1,1,.88,1.533,9.722,9.722,0,0,1-9.63-.027,9.671,9.671,0,1,1,13.62-4.324l11.1,6.408A0.884,0.884,0,0,1,1281.62,223.388Z"
                                    transform="translate(-1252.5 -201.625)"></path>
                        </svg>
                        vyhledat
                    </button>
                </div>
    
                <hr/>
    
                <div class="rs_form__body__smaller">
                    <h3>Sledované osoby </h3>

                    <?php
                        if ($pagination != '') {
                            echo '<p>strana'. $pagination .'</p>';
                        }
                    ?>

                    <?php if (isset($watches[0]['size']) && $watches[0]['size'] > 0) { ?>
                    
                    <ul class="rs_form__body__filter rs_form__body__filter--left">
                        <li><a href="monitoring-rejstriku/export">export do souboru</a></li>
                        <li><a href="monitoring-rejstriku/smazatvse" data-confirm-delete>smazat všechny subjekty</a></li>
                    </ul>
                    <p class="rs_form__body__filter rs_form__body__filter--right">
                        řadit podle
                        <?php
                            if ($orderid == $this->config->item('ORDER_INSERT')) {
                                echo '<b>';
                            }
                            echo '<a href="monitoring-rejstriku/sledovane/'. $filterid .'/'. $this->config->item('ORDER_INSERT') .'/0">';
                            echo 'vložení';
                            echo '</a>';
                            if ($orderid == $this->config->item('ORDER_INSERT')) {
                                echo '</b>';
                            }
                            
                            if ($orderid == $this->config->item('ORDER_NAME')) {
                                echo '<b>';
                            }
                            echo '<a href="monitoring-rejstriku/sledovane/'. $filterid .'/'. $this->config->item('ORDER_NAME') .'/0">';
                            echo 'jména';
                            echo '</a>';
                            if ($orderid == $this->config->item('ORDER_NAME')) {
                                echo '</b>';
                            }
                        ?>
                    </p>

                    <?php } ?>
                </div>

                <?php
                    if (isset($watches[0]['size']) && $watches[0]['size'] == 0) {
                        $msg['message'] = $filterid == 0 
                            ? 'Žádné subjekty v tuto chvíli nesledujete. <a href="monitoring-rejstriku/vlozit" style="text-decoration: underline;">Vložte záznam ke sledování</a>'
                            : 'Žádné subjekty nebyly pro hledaný řeťězec nalezeny. <a href="monitoring-rejstriku/sledovane" style="text-decoration: underline;">Zobrazit všechny sledované subjekty</a>';

                        $this->load->view('inc/message', $msg);
                    } else {
                ?>
                <div class="respo-table">
                    <table class="watched_table">
                        <thead>
                            <tr>
                                <th>
                                    název / jméno<br/>
                                    <small>
                                        identifikátor<br/>
                                        <em>poznámka</em>
                                    </small>
                                </th>
                                <th>
                                    <small>
                                        IČ<br/>
                                        RČ<br/>
                                        datum narození
                                    </small>
                                </th>
                                <th>
                                    <small>
                                        <?php if ($userinfo['is_insolvencewatch']) { ?>
                                        <span class="square square--red"></span> insolvence<br/>
                                        <?php } ?>

                                        <?php if ($userinfo['is_likvidacewatch']) { ?>
                                        <span class="square square--violet"></span> likvidace<br/>
                                        <?php } ?>

                                        <?php if ($userinfo['is_vatdebtorswatch']) { ?>
                                        <span class="square square--cyan"></span> neplatič DPH<br/>
                                        <?php } ?>

                                        <?php if ($userinfo['is_accountchangewatch']) { ?>
                                        <span class="square square--orange"></span> změna účtu
                                        <?php } ?>
                                    </small>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $class = '';
                                foreach ($watches as $i) {
                                    $spisesLength = strlen($i['spises']);
                                    $hasIsirRecord = ($spisesLength > 0) && ($userinfo['is_insolvencewatch'] == true);
                                    $linkid = 0;
                                    $spises = array();
                                    
                                    if ($hasIsirRecord) {
                                        $spises_temp = explode(",", $i['spises']);
                                        foreach ($spises_temp as $s) {
                                            $s = trim($s);
                                            $s_parts = explode("|", $s);
                                            $s_parts[0] = trim($s_parts[0]);
                                            $s_parts[1] = trim($s_parts[1]);
                                            
                                            if ($linkid < $s_parts[0]) {
                                                $linkid = $s_parts[0];
                                            }
                                            
                                            if ($s_parts[1] != $this->config->item('SPIS_ENDED_PRAVOMOCNA') 
                                                && $s_parts[1] != $this->config->item('SPIS_STATUS_NOTAVAILABLE')) {
                                                array_push($spises, $s_parts[0]);
                                            }
                                        }
                                    }

                                    if (isset($i['id']) == false) {
                                        break;
                                    }
                                    echo '<tr>';

                                    echo '<td>';

                                    // choose a name according the prefilling setting
                                    $name = '';
                                    if ($userinfo['is_automatic_filling_enabled'] == 0) {
                                        if ($i['firstname'] != '') {
                                            $name = $i['firstname'] .' '. $i['name'];
                                        } else {
                                            $name = $i['name'];
                                        }
                                    } else {
                                        $name = $i['official_name'];
                                    }

                                    if ($hasIsirRecord) {
                                        echo '<a href="inr1/spis/'. $linkid .'/A/'. url_title_ex($name) .'"><strong>'; //TODO: PUT PROPER URL HERE
                                    }

                                    echo $name;

                                    if ($hasIsirRecord) {
                                        echo '</strong></a>';
                                    }

                                    if ($i['clientname'] != NULL && $i['clientname'] != '') {
                                        echo '<br />'. $i['clientname'];
                                    }

                                    if ($i['note'] != NULL && $i['note'] != '') {
                                        echo '<br /><i title="'. $i['note'] .'">'. word_limiter($i['note'], 15) .'</i>';
                                    }

                                    echo '</td>';

                                    echo '<td>';

                                    echo ($i['ic'] != 0) ? '<dl><dt>IČ</dt><dd>'. formatIc($i['ic']) .'</dd></dl>' : '';
                                    echo ($i['rc'] != 0) ? '<dl><dt>RČ</dt><dd>'. $i['rc'] .'</dd></dl>' : '';
                                    echo ($i['birthdate'] != '0000-00-00' && $i['birthdate'] != '') ? '<dl><dt>dn.</dt><dd>'. date("d.m.Y", strtotime($i['birthdate'])) .'</dd></dl>' : '';

                                    echo '</td>';

                                    echo '<td>';
                                    echo '<ul class="watched_table__docs">';

                                    if ($userinfo['is_insolvencewatch'] && sizeof($spises) > 0) {
                                        $lastSpis = null;
                                        foreach ($spises as $s) {
                                            if ($lastSpis == null || $s > $lastSpis) {
                                                $lastSpis = $s;
                                            }
                                        }

                                        echo '<li>';
                                        echo '<a href="inr1/spis/'. $lastSpis .'/A/'. url_title_ex($i['name']) .'" title="Insolvence - nahlédnout do spisu">'; // TODO: ADD URL TO VYPIS HERE
                                        echo '<img src="images/icons/watched_table__docs--red.png" alt="insolvence" width="13" height="15"/>';
                                        echo '</a>';
                                        echo '</li>';
                                    }

                                    if ($userinfo['is_likvidacewatch'] == true && $i['likvidace'] == '1') {
                                        echo '<li>';
                                        echo '<a href="#" title="Likvidace - nahlédnout do spisu">'; // TODO: ADD URL TO VYPIS HERE
                                        echo '<img src="images/icons/watched_table__docs--violet.png" alt="likvidace" width="13" height="15"/>';
                                        echo '</a>';
                                        echo '</li>';
                                    }

                                    if ($userinfo['is_vatdebtorswatch'] == true && $i['vatdebtor'] == '1') {
                                        echo '<li>';
                                        echo '<a href="#" title="Neplatič DPH - nahlédnout do spisu">'; // TODO: ADD URL TO VYPIS HERE
                                        echo '<img src="images/icons/watched_table__docs--cyan.png" alt="neplatič DPH" width="13" height="15"/>';
                                        echo '</a>';
                                        echo '</li>';
                                    }       
                                    if ($userinfo['is_accountchangewatch'] == true && $i['account_change'] == '1') {
                                        echo '<li>';
                                        echo '<a href="#" title="Změna účtu - nahlédnout do spisu">'; // TODO: ADD URL TO VYPIS HERE
                                        echo '<img src="images/icons/watched_table__docs--orange.png" alt="změna účtu" width="13" height="15"/>';
                                        echo '</a>';
                                        echo '</li>';
                                    }

                                    echo '</ul>';
                                    echo '</td>';

                                    echo '<td class="text-right">';
                                    echo '<a href="monitoring-rejstriku/editovat/'. $i['id'] .'" class="watched_table__handle watched_table__handle--edit" title="Editovat sledovaný subjekt">';
                                    echo 'upravit';
                                    echo '</a>';
                                    echo '<a href="monitoring-rejstriku/smazat/'. $i['id'] .'/'. $filterid .'/'. $orderid .'/'. $pagenum .'" data-confirm-delete class="watched_table__handle watched_table__handle--delete" title="Smazat sledovaný subjekt">';
                                    echo 'smazat';
                                    echo '</a>';
                                    echo '</td>';

                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php
                    if ($pagination != '') {
                        echo '<p>strana'. $pagination .'</p>';
                    }
                ?>

                <?php } ?>

            </form>
        </div>
    </div>
</main>