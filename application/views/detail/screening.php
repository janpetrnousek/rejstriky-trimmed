<main>
    <div class="inner">
        <div class="typo typo__lustration typo__lustration--<?php echo $subject['isProblematic'] ? 'error' : 'success'; ?>">
            <h1>
                Lustrace subjektu: <strong><?php echo character_limiter_ex($subject['name'], $this->config->item('SCREENING_TITLE')); ?></strong>
                <?php 
                    if ($subject['isProblematic']) { 
                        $this->load->view('inc/alert', array('width' => 32, 'height' => 30));
                    } else {
                        $this->load->view('inc/tick', array('width' => 28, 'height' => 28));
                    }
                ?>

                <?php
                    $addtowatch['subject'] = $subject;
                    $this->load->view('detail/addtowatch', $addtowatch);
                ?>
            </h1>
            <div class="tooltip__content tooltip__content--<?php echo $subject['isProblematic'] ? 'error' : 'success'; ?> tooltip__content--auto-width">
                <p>
                    <?php
                        echo $subject['isProblematic'] 
                            ? 'Subjekt s problematickým záznamem'
                            : 'Bez rizikového záznamu';
                    ?>
                </p>
            </div>
            <dl class="typo__lustration__dl dl_equal">
                <dt>Subjekt v insolvenčním řízení</dt>
                <dd class="typo__lustration__dl__dd typo__lustration__dl__dd--<?php echo $subject['isProblematicIsir'] ? 'error' : 'success'; ?>">
                    <div class="tooltip">
                        <div class="tooltip__handle">
                            <?php $this->load->view('inc/'. ($subject['isProblematicIsir'] ? 'alert' : 'tick'), array('useDefault' => true)); ?>
                            <?php echo $subject['isProblematicIsir'] ? 'ANO' : 'NE'; ?>
                        </div>
                        <div class="tooltip__content tooltip__content--<?php echo $subject['isProblematicIsir'] ? 'error' : 'success'; ?> tooltip__content--auto-width">
                            <p>
                                <?php 
                                    echo $subject['isProblematicIsir'] 
                                        ? 'Subjekt s problematickým <a href="'. $subject['isirLink'] .'">záznamem</a>'
                                        : 'Bez rizikového záznamu';
                                ?>
                            </p>
                        </div>
                    </div>
                </dd>
                <dt>Zápis v seznamu nespolehlivých plátců DPH</dt>
                <dd class="typo__lustration__dl__dd typo__lustration__dl__dd--<?php echo $subject['isProblematicVat'] ? 'error' : 'success'; ?>">
                    <div class="tooltip">
                        <div class="tooltip__handle">
                            <?php $this->load->view('inc/'. ($subject['isProblematicVat'] ? 'alert' : 'tick'), array('useDefault' => true)); ?>
                            <?php echo $subject['isProblematicVat'] ? 'ANO' : 'NE'; ?>
                        </div>
                        <div class="tooltip__content tooltip__content--<?php echo $subject['isProblematicVat'] ? 'error' : 'success'; ?> tooltip__content--auto-width">
                            <p>
                                <?php
                                    echo $subject['isProblematicVat'] 
                                        ? 'Subjekt s problematickým záznamem'
                                        : 'Bez rizikového záznamu';
                                ?>
                            </p>
                        </div>
                    </div>
                </dd>
                <dt>Subjekt v likvidaci</dt>
                <dd class="typo__lustration__dl__dd typo__lustration__dl__dd--<?php echo $subject['isProblematicLikvidace'] ? 'error' : 'success'; ?>">
                    <div class="tooltip">
                        <div class="tooltip__handle">
                            <?php $this->load->view('inc/'. ($subject['isProblematicLikvidace'] ? 'alert' : 'tick'), array('useDefault' => true)); ?>
                            <?php echo $subject['isProblematicLikvidace'] ? 'ANO' : 'NE'; ?>
                        </div>
                        <div class="tooltip__content tooltip__content--<?php echo $subject['isProblematicLikvidace'] ? 'error' : 'success'; ?> tooltip__content--auto-width">
                            <p>
                                <?php
                                    echo $subject['isProblematicLikvidace'] 
                                        ? 'Subjekt s problematickým <a href="'. $subject['orLink'] .'">záznamem</a>'
                                        : 'Bez rizikového záznamu';
                                ?>
                            </p>
                        </div>
                    </div>
                </dd>
                <dt>Exekuce na podíl společníka</dt>
                <dd class="typo__lustration__dl__dd typo__lustration__dl__dd--<?php echo $subject['isProblematicExecutionAssociate'] ? 'error' : 'success'; ?>">
                    <div class="tooltip">
                        <div class="tooltip__handle">
                            <?php $this->load->view('inc/'. ($subject['isProblematicExecutionAssociate'] ? 'alert' : 'tick'), array('useDefault' => true)); ?>
                            <?php echo $subject['isProblematicExecutionAssociate'] ? 'ANO' : 'NE'; ?>
                        </div>
                        <div class="tooltip__content tooltip__content--<?php echo $subject['isProblematicExecutionAssociate'] ? 'error' : 'success'; ?> tooltip__content--auto-width">
                            <p>
                                <?php
                                    echo $subject['isProblematicExecutionAssociate'] 
                                        ? 'Subjekt s problematickým <a href="'. $subject['orLink'] .'">záznamem</a>'
                                        : 'Bez rizikového záznamu';
                                ?>
                            </p>
                        </div>
                    </div>
                </dd>
            </dl>
        
            <footer class="typo__lustration__footer<?php echo $subject['isProblematic'] ? '--success' : '--error'; ?>">
                <h2>
                    <?php
                        echo $subject['isProblematic'] 
                            ? 'Subjekt s problematickým záznamem'
                            : 'Subjeky bez rizikového záznamu';
                    ?>
                </h2>
                <p><a href="<?php echo makeGeneralDetailLink($source, $subject); ?>" class="more"><strong>přejít na výpis subjektu</strong></a></p>
            </footer>
        </div>
    </div>
</main>
