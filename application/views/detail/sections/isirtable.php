<?php
    if (sizeof($events) == 0) {
        echo '<p>Bez záznamu.</p>';
    } else {
?>
<div class="respo-table">

    <table>
        <thead>
            <tr>
                <th>Poř.</th>
                <th>Doba zveřejnění</th>
                <th>Text</th>
                <?php
                    if ($isSpisPrihlaskySection) {
                        echo '<th>Věřitel</th>';
                    }
                ?>
                <th>Hlavní přílohy</th>
                <th>Vedlejší přílohy</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($order == 'datum') {
                    // sorting by date
                    // reset publishdate for appropriate order text to the oldest date in section
                    for($i = 0; $i < sizeof($events); $i++) {
                        foreach ($events as $a) {
                            if ($events[$i]['order_text'] == $a['order_text'] && $events[$i]['publishdate'] > $a['publishdate']) {
                                $events[$i]['publishdate'] = $a['publishdate'];
                            }
                        }
                    }

                    function cmpByDate($a, $b) {
                        return strnatcmp($a['publishdate'], $b['publishdate']);
                    }

                    usort($events, 'cmpByDate');
                } else {
                    // sorting by order
                    function cmpByOrder($a, $b) {
                        $result = strnatcmp($a['order_text'], $b['order_text']);

                        if ($result == 0) {
                            $result = strnatcmp($a['publishdate'], $b['publishdate']);
                        }

                        return $result;
                    }
                    usort($events, 'cmpByOrder');
                }

                $current_order_text = '';
                $current_order_texts = array();
                foreach ($events as $i) {

                    if ($common->id > $this->config->item('SPIS_COUNT_MINOR_DOCS')) {
                        // skip if it is minor document
                        if ($i['is_minor'] == true) {
                            continue;
                        }
                    } else {
                        // are there any others with the same section?
                        $found = false;
                        for($subiter = 0; $subiter < sizeof($events); $subiter++) {
                            if (($i['order_text'] == $events[$subiter]['order_text']) && ($i['id'] != $events[$subiter]['id']) && ($i['id'] > $events[$subiter]['id'])) {
                                // there are some "younger docs" in the section - skip them
                                $found = true;
                                continue;
                            }
                        }

                        if ($found == true) {
                            continue;
                        }
                    }

                    $is_changed = $current_order_text != $i['order_text'];
                    if (!$is_changed && in_array($i['text'], $current_order_texts)) {
                        // text is already written in this order - duplicity we can skip this one
                        continue;
                    }

                    if ($is_changed) {
                        // empty array of texts in current order as order is changed
                        $current_order_texts = array();
                    }

                    $current_order_text = $i['order_text'];
                    array_push($current_order_texts, $i['text']);

                    if ($isSpisPrihlaskySection && $order == 'poradi') {
                        echo '<tr class="'. (substr($i['order_text'], -4) == ' - 1' ? 'pohledavka_main' : 'pohledavka_sub') .'">';
                    } else {
                        echo '<tr>';
                    }

                    echo '<td>'. ($is_changed ? $i['order_text'] : '') .'</td>';
                    echo '<td>'. ($is_changed ? date("d.m.Y H:i", strtotime($i['publishdate'])) : '') .'</td>';
                    echo '<td>'. $i['text'] .'</td>';

                    if ($isSpisPrihlaskySection) {
                        echo '<td>'. $i['creditor'] .'</td>';
                    }

                    // main attach
                    echo '<td>';
                    if ($i['document'] != '') {
                        echo '<a href="'. makeIsirDocLink($i['id'], $common->subject_name) .'">';
                        echo '<img src="images/pdf.png" alt="" />';
                        echo '</a>';
                    }
                    echo '</td>';

                    echo '<td>';

                    // look for the minors
                    if ($common->id > $this->config->item('SPIS_COUNT_MINOR_DOCS')) {
                        // group them by document and get newest only
                        $highest_id = NULL;
                        for($subiter = 0; $subiter < sizeof($events); $subiter++) {
                            if ($i['order_text'] == $events[$subiter]['order_text']) {
                                echo '<a name="event'. $events[$subiter]['id'] .'"></a>';
                                
                                if (($i['id'] != $events[$subiter]['id']) 
                                    && ($events[$subiter]['is_minor'] == true)
                                    && ($highest_id == NULL || $highest_id < $events[$subiter]['id'])) 
                                {
                                    $highest_id = $events[$subiter]['id'];
                                }
                            }
                        }

                        if ($highest_id != NULL) {
                            // we have found minor document - show it
                            echo '<a href="'. makeIsirDocLink($highest_id, $common->subject_name) .'">';
                            echo '<img src="images/pdf.png" alt="" />';
                            echo '</a>';
                        }
                    } else {
                        for($subiter = 0; $subiter < sizeof($events); $subiter++) {
                            if ($i['order_text'] == $events[$subiter]['order_text']) {
                                echo '<a name="event'. $events[$subiter]['id'] .'"></a>';
                                
                                if (($i['id'] != $events[$subiter]['id']) && ($i['id'] < $events[$subiter]['id'])) {
                                    // we have found minor document - show it
                                    echo '<a href="'. makeIsirDocLink($events[$subiter]['id'], $common->subject_name) .'">';
                                    echo '<img src="images/pdf.png" alt="" />';
                                    echo '</a>';
                                }
                            }
                        }
                    }

                    echo '</td>';

                    echo '</tr>';
                }
            ?>

        </tbody>
    </table>

</div>
<?php } ?>
