<?php if (isset($subject['linkOr']) || isset($subject['linkAres'])) { ?>
<li class="accordion__item">
    <article>
        <header class="accordion__item__header">
            <h2>VÝPISY Z REJSTŘÍKŮ</h2>
        </header>
        <div class="accordion__item__content">
            <ul class="typo__links">
                <?php if (isset($subject['linkOr'])) { ?>
                <li>
                    <a href="<?php echo $subject['linkOr']; ?>" target="_blank" class="more">obchodní rejstřík</a>
                </li>
                <?php } ?>

                <?php if (isset($subject['linkAres'])) { ?>
                <li>
                    <a href="<?php echo $subject['linkAres']; ?>" target="_blank" class="more">ARES</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </article>
</li>
<?php } ?>