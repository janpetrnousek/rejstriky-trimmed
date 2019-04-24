<main>
    <div class="inner">
        <div class="typo">
            <h1 id="found_subjects_heading">Vyhledané subjekty</h1>
            <p>
                Zobrazujeme subjekty z obchodního a insolvenčního rejstříku.
            </p>
            <p id="found_subjects_notfound">
                Žádný subjekt nebyl nalezen.
            </p>
            <ul class="typo__subjects" id="found_subjects">
                <li id="found_subjects_indicator">
                    <img src="images/loader.gif" alt="" />
                </li>
            </ul>

            <h2 id="found_similar_subjects_heading">Podobné subjekty</h2>
            <p id="found_similar_subjects_notfound">
                Žádný podobný subjekt nebyl nalezen.
            </p>
            <ul class="typo__subjects" id="found_similar_subjects">
                <li id="found_similar_subjects_indicator">
                    <img src="images/loader.gif" alt="" />
                </li>
            </ul>

            <script id="subject-template-problematic" type="text/x-custom-template">
                <li>
                    <article class="subject-item subject-item--invalid">
                        <h2 class="subject-item__title">
                            <a href="{subjectLink}" title="{subjectName}">{subjectShortName}</a>
                            <div class="tooltip">
                                <span class="tooltip__handle">
                                    <?php $this->load->view('inc/alert', array('useDefault' => true)); ?>
                                </span>
                                <div class="tooltip__content tooltip__content--error tooltip__content--auto-width">
                                    <p>subjekt s problematickým <a href="{subjectScreeningLink}">záznamem</a></p>
                                </div>
                            </div>
                        </h2>
                        <ul class="subject-item__meta">
                            <li>IČ: {subjectIc}</li>
                            <li title="{subjectAddress}">Sídlo: {subjectShortAddress}</li>
                        </ul>
                    </article>
                </li>
            </script>
            <script id="subject-template-ok" type="text/x-custom-template">
                <li>
                    <article class="subject-item subject-item--valid">
                        <h2 class="subject-item__title">
                            <a href="{subjectLink}" title="{subjectName}">{subjectShortName}</a>
                            <div class="tooltip">
                                <span class="tooltip__handle">
                                    <?php $this->load->view('inc/tick', array('useDefault' => true)); ?>
                                </span>
                                <div class="tooltip__content tooltip__content--success tooltip__content--auto-width">
                                    <p>Bez rizikového záznamu</p>
                                </div>
                            </div>
                        </h2>
                        <ul class="subject-item__meta">
                            <li>IČ: {subjectIc}</li>
                            <li title="{subjectAddress}">Sídlo: {subjectShortAddress}</li>
                        </ul>
                    </article>
                </li>
            </script>
        </div>
    </div>
</main>