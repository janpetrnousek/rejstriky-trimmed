<main>
    <div class="inner">
        <div class="typo typo__lustration typo__lustration--<?php echo $subjectIsProblematic ? 'error' : 'success'; ?>">
            <h1>
                Karta subjektu: <strong title="<?php echo $subject_or['name']; ?>"><?php echo character_limiter_ex($subject_or['name'], $this->config->item('DETAIL_TITLE')); ?></strong>
                <?php
                    if ($subjectIsProblematic) {
                        $this->load->view('inc/alert', array('width' => '32', 'height' => '30'));
                    } else {
                        $this->load->view('inc/tick', array('width' => '28', 'height' => '28'));
                    }
                ?>

                <?php
                    $addtowatch['subject'] = $subject_or;
                    $this->load->view('detail/addtowatch', $addtowatch);
                ?>
            </h1>
            <ul class="accordion">
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
                                <a href="https://or.justice.cz/ias/ui/print-pdf?subjektId=<?php echo $subject_or['justice_id']; ?>&typVypisu=UPLNY&full=false" class="accordion__icon accordion__icon--4" title="PDF export">
                                    PDF export
                                </a>

                                <?php 
                                    $this->load->view('detail/sharedialog');
                                ?>

                            </div>
                        </header>
                        <div class="accordion__item__content">
                            <?php
                                // decompress raw data
                                $subject_or['raw_data'] = gzuncompress($subject_or['raw_data']);
                                
                                // determine new or old format
                                $tdname = determineFormat($subject_or['raw_data']);
                                
                                $subject_or['raw_data'] = preProcessRawData($subject_or['raw_data'], $tdname);
                                $subject_or['raw_data'] = removeBold($subject_or['raw_data']);
                                $subject_or['raw_data'] = removeInsolvencyHeadings($subject_or['raw_data']);

                                $headings = getheadings();
                                        
                                // base data
                                echo formatBase($headings, $subject_or, $tdname);

                                // other sections	
                                $sectionText = '';
                                foreach($headings as $h) {
                                    if ($h['title'] == 'Statutární orgán') {
                                        $sectionText .= formatStatutarniOrgan($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname);
                                    } else if ($h['title'] == 'Vedoucí odštěpného závodu') {
                                        $sectionText .= formatVedouciOdstepnehoZavodu($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname);
                                    } else if ($h['title'] == 'Společníci') {
                                        $sectionText .= formatCommonPersons($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname, 'Podíl');
                                    } else if ($h['title'] == 'Odštěpné závody') {
                                        $sectionText .= formatCommonPersons($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname, 'Sídlo nebo umístění:');
                                    } else if ($h['title'] == 'Odštěpné závody') {
                                        $sectionText .= formatCommonPersons($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname, 'Sídlo nebo umístění:');
                                    } else if ($h['title'] == 'Statutární orgán - představenstvo') {
                                        $sectionText .= formatCommonPersons($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname);
                                    } else if ($h['title'] == 'Dozorčí rada') {
                                        $sectionText .= formatCommonPersons($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname);
                                    } else if ($h['title'] == 'Správní rada') {
                                        $sectionText .= formatCommonPersons($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname);
                                    } else if ($h['title'] == 'Předmět podnikání') {
                                        $sectionText .= formatPredmetPodnikani($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname);
                                    } else if ($h['title'] == 'Předmět činnosti') {
                                        $sectionText .= formatPredmetPodnikani($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname);
                                    } else if ($h['title'] == 'Účel nadace') {
                                        $sectionText .= formatPredmetPodnikani($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname);
                                    } else {
                                        // general handling
                                        $sectionText .= formatCommonItems($subject_or['raw_data'], $h['head'], $headings, $h['title'], $tdname);
                                    }
                                }

                                // blur persons if user is not logged in
                                $sectionText = blurPersons($sectionText);

                                echo $sectionText;
                            ?>
                        </div>
                    </article>
                </li>
                <?php 
                    $this->load->view('detail/sections/advanced', array('subject_vat' => $subject_vat, 'subject_isir' => $subject_isir)); 
                    $this->load->view('detail/sections/relations');
                    $this->load->view('detail/sections/agreementheader', array('subject_or' => $subject_or)); 
                    $this->load->view('detail/sections/links', array('subject' => $subject));
                ?>
            </ul>
        </div>
    </div>
</main>