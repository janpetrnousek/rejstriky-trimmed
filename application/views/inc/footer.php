<?php $this->load->view('inc/registerdialog'); ?>
<footer class="main_footer">
    <div class="main_footer__prefooter">
        <div class="inner">

            <ul class="main_footer__top-nav">
                <li><a href="kontakt">kontakt</a></li>
                <li><a href="pravni-podminky">právní podmínky</a></li>
                <li><a href="#">poslední vyhledávané</a></li>
                <li>
                    <ul class="main_footer__top-nav__socials">
                        <li><a href="#"><img src="images/icons/footer_1.svg" alt="Messenger" width="23"
                                             height="25"/></a></li>
                        <li><a href="#"><img src="images/icons/footer_2.svg" alt="Facebook" width="22" height="22"/></a>
                        </li>
                        <li><a href="#"><img src="images/icons/footer_3.svg" alt="Twitter" width="22" height="22"/></a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="main_footer__precontent main_footer__precontent--headlines">
                <li>
                    <h5>rejstříky<span>.</span>info</h5>
                </li>
                <li>
                    <h5>EU</h5>
                </li>
            </ul>
            <ul class="main_footer__precontent">
                <li>
                    <ul class="main_footer__precontent__nav">
                        <li><a href="provozovatel">Provozovatel</a></li>
                        <li><a href="inzerce">Inzerce</a></li>
                        <li><a href="pravni-upozorneni">Právní upozornění</a></li>
                        <li><a href="zdroje-informaci">Zdroje informací</a></li>
                        <li><a href="#">Poslední vyhledávané</a></li>
                        <li><a href="kontakt">Kontakty</a></li>
                    </ul>
                </li>
                <li>
                    <img src="images/icons/footer_eu.png" class="main_footer__precontent__eu" alt="EU" width="275"
                         height="57"/>
                    <p>Projekt Informační portál Rejstriky.info s reg. č. CZ.01.4.04/0.0/0.0/16_066/0009515 je
                        spolufinancování Evropskou unií v rámci programu OP Podnikání a inovace pro
                        konkurenceschopnost</p>

                    <a href="https://www.advokatova.cz" target="_blank">
                        <img src="images/logo_jkp.png" alt="JKP" width="214" height="122" class="main_footer__precontent__partner" />
                    </a>
                </li>
            </ul>

        </div>
    </div>

    <div class="main_footer__real">
        <div class="inner">
            <div class="main_footer__real__wrap">
                <div class="main_footer__real__item main_footer__real__item--logo">
                    <img src="images/footer_logo.png" alt="rejstriky.info" width="104" height="44"/>
                </div>
                <div class="main_footer__real__item main_footer__real__item--copy">
                    <p>2017 &copy; Všechna práva vyhrazena | Internetové stránky používají soubory cookies</p>
                </div>
                <div class="main_footer__real__item main_footer__real__item--created">
                    <p>design <a href="#"><img src="images/footer_designed_by.png" alt="kulich jan" width="44"
                                               height="25"/></a></p>
                </div>
            </div>
        </div>
    </div>

</footer>

<?php 
    $parameters = array(
        'isir-id' => isset($isir_id) ? $isir_id : 'false',
        'search-id' => isset($search_id) ? $search_id : 'false',
        'search-type-id' => isset($search) ? $search->type_id : 'false',
        'search-type-relations' => isset($search) && $search->type_id == $this->config->item('SEARCH_RELATIONS') ? 'true' : 'false',
        'search-type-relations-value' => $this->config->item('SEARCH_RELATIONS')
    );

    echo script_url('js/main.min.js', $parameters);
?>

<?php
    if (isset($graph_data)) {
?>
    <script type="text/javascript">
        var graph_data = <?php echo $graph_data; ?>;
        var rootid = "<?php echo $root['id']; ?>";
        var baseurl = "<?php echo base_url(); ?>";
        var hidden_node_name = "<?php echo $this->config->item('RELATIONS_HIDDENNODE'); ?>";
    </script>

<?php
    echo script_url('js/relations.min.js', $parameters);
?>

<?php
    }
?>

</body>
</html>