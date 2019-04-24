<?php if (isset($subject_or)) { ?>
<li class="accordion__item">
    <article>
        <header class="accordion__item__header">
            <h2>HLAVIČKA DO SMLOUVY</h2>
        </header>
        <div class="accordion__item__content">
            <?php
                echo $subject_or['name'];
                echo "<br />";
                echo 'se sídlem '. $subject_or['address'];
                echo "<br />";
                echo 'IČ: '. formatIc($subject_or['ic']);
                echo "<br />";
                
                $spis_mark_text = getSpisMark($subject_or['raw_data']);
                if ($spis_mark_text != '') {
                    echo 'zapsaná u: '. str_replace(array($subject_or['spis_mark'], ' vedená u '), '', $spis_mark_text) . ', sp. zn. '. $subject_or['spis_mark'];
                    echo "<br />";
                }

                echo 'zastoupena: '. ((sizeof($subject_or['statutars']) == 1) ? $subject_or['statutars'][0]['name'] : '...');
            ?>
        </div>
    </article>
</li>
<?php } ?>