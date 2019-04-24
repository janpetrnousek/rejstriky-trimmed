<main>
    <div class="inner">
        <div class="typo typo__lustration typo__lustration--<?php echo $subjectIsProblematic ? 'error' : 'success'; ?>">
            <h1>
                Karta subjektu: <strong title="<?php echo $subject_isir['name']; ?>"><?php echo character_limiter_ex($subject_isir['name'], $this->config->item('DETAIL_TITLE')); ?></strong>
                <?php
                    if ($subjectIsProblematic) {
                        $this->load->view('inc/alert', array('width' => '32', 'height' => '30'));
                    } else {
                        $this->load->view('inc/tick', array('width' => '28', 'height' => '28'));
                    }
                ?>

                <?php
                    $addtowatch['subject'] = $subject_isir;
                    $this->load->view('detail/addtowatch', $addtowatch);
                ?>
            </h1>
            <ul class="accordion">
                <?php 
                    $this->load->view('detail/sections/isir', array('subject_isir' => $subject_isir));
                    $this->load->view('detail/sections/advanced', array('subject_vat' => $subject_vat, 'subject_isir' => $subject_isir)); 
                    $this->load->view('detail/sections/relations');
                    $this->load->view('detail/sections/links', array('subject' => $subject));
                ?>
            </ul>
        </div>
    </div>
</main>