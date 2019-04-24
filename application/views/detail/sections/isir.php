<li class="accordion__item accordion__item--active">
    <article>
        <header class="accordion__item__header">
            <h2>základní info</h2>
            <div class="accordion__item__header__icons">
                <a href="#" class="accordion__icon accordion__icon--2" title="vytisknout" data-print>
                    vytisknout
                </a>
                <a href="#" class="accordion__icon accordion__icon--3" title="sdílet" data-share>
                    sdílet
                </a>
            </div>
        </header>
        <div class="accordion__item__content">
            <dl class="dl_equal">
                <dt>Název</dt>
                <dd><?php echo $subject_isir['name']; ?></dd>
                <dt>IČ</dt>
                <dd><?php echo isIcDefined($subject_isir['ic']) ? formatIc($subject_isir['ic']) : 'neuvedeno'; ?></dd>
                <dt>RČ</dt>
                <dd><?php echo isIcDefined($subject_isir['rc']) ? $subject_isir['rc'] : 'neuvedeno'; ?></dd>
                <dt>Adresa</dt>
                <dd><?php echo $subject_isir['address'] != '' ? $subject_isir['address'] : 'neuvedeno'; ?></dd>
                <dt class="padded">Aktivní spis / stav</dt>
                <dd class="padded">                
                    <?php
                        echo $this->content_lib->format_spises($subject_isir['relating'], $this->config->item('SPIS_ACTIVE'));
                    ?>
                </dd>
                <dt>Neaktivní spisy</dt>
                <dd>                
                    <?php
                        echo $this->content_lib->format_spises($subject_isir['relating'], $this->config->item('SPIS_NOT_ACTIVE'));
                    ?>
                </dd>
                <dt>Zobrazený spis</dt>
                <dd>
                    <?php 
                        echo $subject_isir['court_name'] 
                            .' '. 
                            $subject_isir['senat_number'] 
                            .' '. 
                            $subject_isir['number_prefix'] 
                            .' '. 
                            $subject_isir['number_id'] 
                            .'/'. 
                            $subject_isir['number_year'] 
                            .' - '. 
                            $subject_isir['court_fullname']; 
                    ?>
                </dd>
                <dt>Stav</dt>
                <dd><?php echo $subject_isir['status_name']; ?></dd>
                <dt class="padded">Insolvenční správce</dt>
                <dd class="padded">
                    <?php
                        if ($subject_isir['supervisors'] == null) {
                            echo 'neuvedeno';
                        } else {
                    ?>
                    <strong class="fixed">
                        <?php echo $subject_isir['supervisors']['type']; ?>:
                    </strong>
                    <?php 
                        echo $subject_isir['supervisors']['name']; 
                        if (isIcDefined($subject_isir['supervisors']['ic'])) {
                            echo ', IČ:'. formatIc($subject_isir['supervisors']['ic']);
                        }
                        
                        echo '<br />';
                        echo $subject_isir['supervisors']['address'];
                        echo '<br />';
                        echo 'datum ustanovení: '. date("d.m.Y", strtotime($subject_isir['supervisors']['date_start']));                    
                    ?>
                    <?php
                        }
                    ?>
                </dd>
            </dl>                            
        </div>
    </article>
</li>
