<main>
    <div class="inner">
        <div class="hp__banners hp__banners--new">
            <ul class="two">
                <li>
                    <a href="monitoring-rejstriku">
                        <article data-equal-group="hp_banners">
                            <span class="hp__banners__stripe">
                                <span class="hp__banners__icon hp__banners__icon--1">
                                    <img src="images/icons/hp__banners_icon--1.png" alt="Monitoring" width="48" height="46"/>
                                </span>
                                <img src="images/hp__banners__stripe--1.png" alt="Monitoring" width="166" height="187"/>
                            </span>
                            <h2>
                                <strong>Monitoring rejstříků</strong></h2>
                            <p>
                                Nenechte si ujít informace o zákaznících, obchodních partnerech či dlužnících. Chraňte se před finančními ztrátami způsobenými insolvencí, dluhy na DPH, exekucemi apod.
                            </p>
                            <span class="btn aligncenter">Zjistit více</span>
                        </article>
                    </a>
                </li>
                <li>
                    <a href="rejstrikovy-servis">
                        <article data-equal-group="hp_banners">
                            <span class="hp__banners__stripe">
                                <span class="hp__banners__icon hp__banners__icon--2">
                                    <img src="images/icons/hp__banners_icon--2.png" alt="Monitoring" width="43" height="48"/>
                                </span>
                                <img src="images/hp__banners__stripe--2.png" alt="Monitoring" width="167" height="191"/>
                            </span>
                            <h2>
                                <strong>Rejstříkový servis</strong></h2>
                            <p>
                                Zakládáte společnost nebo potřebujete změnit zápis v rejstříku? Je váš dlužník v insolvenci a potřebujete přihlásit pohledávku? Využijte servis garantovaný advokáty JKP!
                            </p>
                            <span class="btn aligncenter">Zjistit více</span>
                        </article>
                    </a>
                </li>
            </ul>
        </div>
        
        <section class="hp__central">
            <div class="hp__half-box hp__half-box--new-companies">
                <article data-equal-group="hp__half-box">
                    <h3>Nově vzniklé společnosti</h3>
                    <ol>
                        <li>
                            <ul>
                                <?php
                                    foreach ($new_companies as $nc) {
                                        echo '<li>';
                                        echo '<dl>';
                                        echo '<dt title="'. $nc['name'] .'"><a href="'. makeObchodniLink($nc) .'">'. character_limiter_ex($nc['name'], $this->config->item('HOME_NEWSUBJECTS_TITLE')) .'</a></dt>';
                                        echo '<dd>'. formatIc($nc['ic']) .'</dd>';
                                        echo '<dd title="'. $nc['address'] .'">'. character_limiter_ex($nc['address'], $this->config->item('HOME_NEWSUBJECTS_ADDRESS')) .'</dd>';
                                        echo '</dl>';
                                        echo '</li>';
                                    }
                                ?>
                            </ul>
                        </li>
                    </ol>
                    <a href="#" class="btn">zobrazit vše</a>
                </article>
            </div>
            <div class="hp__half-box hp__half-box--introduction">
                <article data-equal-group="hp__half-box">
                    <h3>Podnikatelské vazby</h3>
                    <div class="animation-how-to">
        
                        <span class="play" data-animation="play"></span>
        
                        <span class="animation-how-to__1"><span></span></span>
                        <span class="animation-how-to__2"></span>
                        <span class="animation-how-to__3 animation-how-to__plus"></span>
        
                        <span class="animation-how-to__4 animation-how-to__line"></span>
                        <span class="animation-how-to__5 animation-how-to__line animation-how-to__line--yellow"></span>
                        <span class="animation-how-to__6 animation-how-to__line"></span>
                        <span class="animation-how-to__7 animation-how-to__line"></span>
        
                        <span class="animation-how-to__8 animation-how-to__small-circle"></span>
                        <span class="animation-how-to__9 animation-how-to__small-circle"></span>
                        <span class="animation-how-to__10 animation-how-to__small-circle"></span>
        
                        <span class="animation-how-to__11 animation-how-to__plus"></span>
        
                        <span class="animation-how-to__12 animation-how-to__line  animation-how-to__line--yellow"></span>
        
                        <span class="animation-how-to__13 animation-how-to__small-circle"></span>
        
                    </div>
                    <p>
                        Zajímají vás vazby podnikatelů, personální propojení společností či aktivity jednotlivých podnikatelů? Neváhejte zadat konkrétní subjekt a požadované informace se vám zobrazí v přehledné grafice.</p>
                    <a href="#" class="btn" data-animation="play">instruktážní video</a>
                </article>
            </div>
        </section>
        
        <?php $this->load->view('testimonials/index'); ?>
    </div>
</main>
