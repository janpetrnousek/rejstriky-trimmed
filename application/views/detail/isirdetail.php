<main>
    <div class="inner">
        <div class="typo typo__lustration typo__lustration--error">

            <h1>
                Insolvenční spis: <strong title="<?php echo $subject_isir['name']; ?>"><?php echo character_limiter_ex($subject_isir['name'], $this->config->item('DETAIL_TITLE')); ?></strong>
                <?php
                    $this->load->view('inc/alert', array('width' => '32', 'height' => '30'));
                ?>
            </h1>

            <ul class="accordion">
                <?php 
                    $this->load->view('detail/sections/isir', array('subject_isir' => $subject_isir));

                    foreach($isir_sections as $section) {
                        echo '<li class="accordion__item">';
                        echo '<article>';
                        
                        echo '<header class="accordion__item__header">';
                        echo '<h2 class="sectionheader" data-section-id="'. $section['id'] .'">Oddíl '. $section['name'] .' - '. $section['description'] .'</h2>';
                        echo '</header>';
                        
                        echo '<div class="accordion__item__content">';
                        echo '<img src="images/loader.gif" alt="" />';
                        echo '</div>';
                        
                        echo '</article>';
                        echo '</li>';
                    }
                ?>
            </ul>

        <div>
    </div>
</main>