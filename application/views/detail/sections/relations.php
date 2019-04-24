<?php if (isset($graph_data)) { ?>
<li class="accordion__item accordion__item--active accordion__item--relationships">
    <article>
        <header class="accordion__item__header">
            <h2>VAZBY SUBJEKTU</h2>

            <div class="accordion__item__header__icons">
                <a href="#" id="relations-print" class="accordion__icon accordion__icon--2" title="vytisknout" data-print>
                    vytisknout
                </a>
            </div>
        </header>
        <div class="accordion__item__content">
            <div id="subject_data"></div>

            <div class="relations-panel">
                <div id="timeline">
                    <div id="timeline_input">
                        <input type="text" name="referencedate" id="timeline_date" readonly="readonly" value="<?php echo isset($referencedate) ? $referencedate : date("d.m.Y"); ?>" />
                    </div>
                    <div id="timeline_linediv">
                        <a href="#" title="předchozí změna" id="timeline_back">
                            <img src="images/timeline_arrow_left.png" alt="předchozí změna" />
                        </a>
                        <div id="timeline_line">
                            <div></div>
                            <table>
                                <tr>
                                    
                                </tr>
                            </table>
                        </div>
                        <a href="#" title="následujíci změna" id="timeline_forward">
                            <img src="images/timeline_arrow_right.png" alt="následujíci změna" />
                        </a>
                    </div>
                </div>

                <div id="relations_graph">
                    <div id="center-container"><div id="infovis"></div></div>
                </div>

                <div id="graphstructure">
                    <?php
                        foreach ($relation_types as $r) {
                            $id = 'rt-'. $r['color'];
                            echo '<input type="checkbox" checked="checked" id="'. $id .'" class="relationtype_checkbox" data-color="'. $r['color'] .'" name="'. $id .'" value="true" />';
                            echo '<span style="background-color: #'. $r['color'] .'; width: 20px; height: 5px; display: inline-block; margin: -5px 10px 5px 5px;"></span>';
                            echo '<label for="'. $id .'">';
                            echo $r['display_name']; 
                            echo '</label>';
                            echo '<br />';
                        }
                    ?>

                    <hr />

                    <input type="checkbox" checked="checked" id="graphstructure_checkbox" name="graphstructure_checkbox" value="true" />
                    <label for="graphstructure_checkbox">
                        Strukturování
                        <img src="images/relations_info.png" alt="" id="graphstructure_tooltip" title="Zobrazují se původně vyhledané i další zadané vazby" />
                    </label>

                </div> 

                <div id="zoomcontrols">
                    <img src="images/relations_zoomcontrols.png" alt="" usemap="#zoomcontrolsmap">

                    <map name="zoomcontrolsmap">
                        <area shape="rect" coords="0,0,29,28" title="přiblížit" alt="přiblížit" id="zoom_in" href="#">
                        <area shape="rect" coords="0,29,29,57" title="oddálit" alt="oddálit" id="zoom_out" href="#">
                    </map>
                </div>

                <div id="directions">
                    <img src="images/relations_arrows.png" alt="" usemap="#directionsmap">

                    <map name="directionsmap">
                        <area shape="rect" coords="18,6,35,18" title="posunout graf nahoru" alt="posunout graf nahoru" id="direction_top" href="#">
                        <area shape="rect" coords="6,19,18,36" title="posunout graf doleva" alt="posunout graf doleva" id="direction_left" href="#">
                        <area shape="rect" coords="36,18,48,35" title="posunout graf doprava" alt="posunout graf doprava" id="direction_right" href="#">
                        <area shape="rect" coords="18,36,36,47" title="posunout graf nadolu" alt="posunout graf nadolu" id="direction_bottom" href="#">
                    </map>
                </div>

                <div id="navigation">
                    <div class="content">
                        <canvas id="birdeyeback"></canvas>
                        <div id="birdeye" title="potáhnutím se lze navigovat po grafu"></div>
                    </div>
                </div>
            </div>
                    
        </div>             
    </article>
</li>
<?php } ?>
