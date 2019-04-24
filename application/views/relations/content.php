<main>
    <div class="inner">
        <div class="typo typo__lustration">
            <h1>
                Vazby subjektu: <strong><?php echo character_limiter_ex($root['name'], $this->config->item('SCREENING_TITLE')); ?></strong>
            </h1>

            <ul class="accordion">
                <?php 
                    $this->load->view('detail/sections/relations');
                ?>
            </ul>
        
        </div>
    </div>
</main>