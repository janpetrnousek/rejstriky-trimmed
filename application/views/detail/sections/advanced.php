<li class="accordion__item">
    <article>
        <header class="accordion__item__header">
            <h2>Rozšířené info</h2>
            <div class="accordion__item__header__icons">
                <a href="#" class="accordion__icon accordion__icon--2" title="vytisknout" data-print>
                    vytisknout
                </a>
            </div>
        </header>
        <div class="accordion__item__content">
            <dl class="dl_equal">
                <dt>Plátce DPH</dt>
                <dd><?php echo $subject_vat['isPayer'] ? 'ANO' : 'NE'; ?></dd>
                <?php if ($subject_vat['isPayer']) { ?>
                <dt>DIČ</dt>
                <dd><?php echo $subject_vat['dic']; ?></dd>
                <?php if ($subject_vat['isPayer']) { ?>
                <dt>Finanční úřad</dt>
                <dd><?php echo $subject_vat['bureau']; ?></dd>
                <?php } ?>
                <dt>Registrované účty DPH</dt>
                <dd>
                    <p><?php $accounts = implode(', ', $subject_vat['accounts']); echo $accounts != '' ? $accounts : 'žádné'; ?></p>
                </dd>
                <?php } ?>
                <dt>Nespolehlivý plátce DPH</dt>
                <dd>
                    <span class="lustration lustration--<?php echo $subject_vat['isUnreliableVat'] ? 'error' : 'success'; ?>">

                    <?php $this->load->view('inc/'. ($subject_vat['isUnreliableVat'] ? 'alert' : 'tick'), array('useDefault' => true)); ?>

                    <?php echo $subject_vat['isUnreliableVat'] ? 'ANO' : 'NE'; ?></span>
                    <?php echo $subject_vat['isPayer'] ? ' / <a href="'. $subject_vat['link'] .'" class="more">přejít na úřední výpis</a>' : ''; ?></dd>
                <dt>Záznam v Insolvenčním rejstříku</dt>
                <dd>
                    <span class="lustration lustration--<?php echo $subject_isir['hasRecord'] ? 'error' : 'success'; ?>">

                    <?php $this->load->view('inc/'. ($subject_isir['hasRecord'] ? 'alert' : 'tick'), array('useDefault' => true)); ?>

                    <?php
                        echo $subject_isir['hasRecord'] ? 'ANO' : 'NE';
                        echo $subject_isir['hasRecord'] ? ' / <a href="'. $subject_isir['link'] .'" class="more">zobrazit spis</a>' : '';
                    ?>
                </dd>
            </dl>
        </div>
    </article>
</li>