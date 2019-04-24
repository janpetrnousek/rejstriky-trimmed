<?php

    function getheadings() {
        // as much identical as possible with http://wwwinfo.mfcr.cz/ares/xml_doc/xslt/or/ares_or_h1.0.3.xsl.cz
        return array(
            'predmetPodnikani' => array('head' => '<span class="nounderline">Předmět podnikání: </span>', 'title' => 'Předmět podnikání'),
            'predmetCinnosti' => array('head' => '<span class="nounderline">Předmět činnosti: </span>', 'title' => 'Předmět činnosti'),
            'predmetCinnostiPodnikani' => array('head' => '<span class="nounderline">Předmět činnosti podnikání: </span>', 'title' => 'Předmět činnosti podnikání'),
            'doplnkovaCinnost' => array('head' => '<span class="nounderline">Doplňková činnost: </span>', 'title' => 'Doplňková činnost'),
            'vedouciOdstepnehoZavodu' => array('head' => '<span class="nounderline">Vedoucí odštěpného závodu: </span>', 'title' => 'Vedoucí odštěpného závodu', 'isPerson' => true),
            'zrizovatelZahranicniOsoba' => array('head' => '<span class="nounderline">Zřizovatel - zahraniční osoba: </span>', 'title' => 'Zřizovatel - zahraniční osoba', 'isPerson' => true),
            'zrizovatelOdstepnehoZavodu' => array('head' => '<span class="nounderline">Zřizovatel odštěpného závodu: </span>', 'title' => 'Zřizovatel odštěpného závodu', 'isPerson' => true),
            'zrizovatel' => array('head' => '<span class="nounderline">Zřizovatel: </span>', 'title' => 'Zřizovatel', 'isPerson' => true),
            'statutarniOrgan' => array('head' => '<span class="nounderline">Statutární orgán: </span>', 'title' => 'Statutární orgán', 'isPerson' => true),
            'statutarniOrganPredstavenstvo' => array('head' => '<span class="nounderline">Statutární orgán - představenstvo: </span>', 'title' => 'Statutární orgán - představenstvo', 'isPerson' => true),
            'statutarniOrganReditel' => array('head' => '<span class="nounderline">Statutární orgán - ředitel: </span>', 'title' => 'Statutární orgán - ředitel', 'isPerson' => true),
            'statutarniOrganVybor' => array('head' => '<span class="nounderline">Statutární orgán - výbor: </span>', 'title' => 'Statutární orgán - výbor', 'isPerson' => true),
            'statutarniOrganVyborSpolecenstvi' => array('head' => '<span class="nounderline">Statutární orgán: výbor společenství: </span>', 'title' => 'Statutární orgán: výbor společenství', 'isPerson' => true),
            'statutarniOrganZrizovatele' => array('head' => '<span class="nounderline">Statutární orgán zřizovatele - zahraniční osoby: </span>', 'title' => 'Statutární orgán zřizovatele - zahraniční osoby', 'isPerson' => true),
            'statutarniOrganKomplementar' => array('head' => '<span class="nounderline">Statutární orgán - komplementář: </span>', 'title' => 'Statutární orgán - komplementář', 'isPerson' => true),
            'statutarniOrganPredsedaDruzstva' => array('head' => '<span class="nounderline">Statutární orgán - předseda družstva: </span>', 'title' => 'Statutární orgán - předseda družstva', 'isPerson' => true),
            'statutarniOrganSpolecnosti' => array('head' => '<span class="nounderline">Statutární orgán společnosti: </span>', 'title' => 'Statutární orgán společnosti', 'isPerson' => true),
            'statutarniOrganKomplementaru' => array('head' => '<span class="nounderline">Statutární orgán komplementářů: </span>', 'title' => 'Statutární orgán komplementářů', 'isPerson' => true),
            'statutarniReditel' => array('head' => '<span class="nounderline">Statutární ředitel: </span>', 'title' => 'Statutární ředitel', 'isPerson' => true),
            'kontrolniOrgan' => array('head' => '<span class="nounderline">Kontrolní orgán: </span>', 'title' => 'Kontrolní orgán', 'isPerson' => true),
            'spravniRada' => array('head' => '<span class="nounderline">Správní rada: </span>', 'title' => 'Správní rada', 'isPerson' => true),
            'dozorciRada' => array('head' => '<span class="nounderline">Dozorčí rada: </span>', 'title' => 'Dozorčí rada', 'isPerson' => true),
            'zakladatel' => array('head' => '<span class="nounderline">Zakladatel: </span>', 'title' => 'Zakladatel', 'isPerson' => true),
            'spolecnici' => array('head' => '<span class="nounderline">Společníci: </span>', 'title' => 'Společníci', 'isPerson' => true),
            'spolecniciKomplementari' => array('head' => '<span class="nounderline">Společníci - komplementáři: </span>', 'title' => 'Společníci - komplementář', 'isPerson' => true),
            'spolecniciKomandiste' => array('head' => '<span class="nounderline">Společníci - komanditisté: </span>', 'title' => 'Společníci - komanditisté', 'isPerson' => true),
            'jedinyAkcionar' => array('head' => '<span class="nounderline">Jediný akcionář: </span>', 'title' => 'Jediný akcionář', 'isPerson' => true),
            'akcie' => array('head' => '<span class="nounderline">Akcie: </span>', 'title' => 'Akcie'),
            'zakladniKapital' => array('head' => '<span class="nounderline">Základní kapitál: </span', 'title' => 'Základní kapitál'),
            'nadacniKapital' => array('head' => '<span class="nounderline">Nadační kapitál: </span>', 'title' => 'Nadační kapitál'),
            'upsanyZakladniKapital' => array('head' => '<span class="nounderline">Upsaný základní kapitál: </span>', 'title' => 'Upsaný základní kapitál'),
            'ostatniSkutecnosti' => array('head' => '<span class="nounderline">Ostatní skutečnosti: </span>', 'title' => 'Ostatní skutečnosti'),
            'udajeOExekucich' => array('head' => '<span class="nounderline">Údaje o exekucích: </span>', 'title' => 'Údaje o exekucích'),
            'prokura' => array('head' => '<span class="nounderline">Prokura: </span>', 'title' => 'Prokura', 'isPerson' => true),
            'likvidator' => array('head' => '<span class="nounderline">Likvidátor: </span>', 'title' => 'Likvidátor', 'isPerson' => true),
            'revizor' => array('head' => '<span class="nounderline">Revizor: </span>', 'title' => 'Revizor', 'isPerson' => true),
            'ucelSpolecenstvi' => array('head' => '<span class="nounderline">Účel společenství: </span>', 'title' => 'Účel společenství'),
            'nazevNejvyssihoOrganu' => array('head' => '<span class="nounderline">Název nejvyššího orgánu: </span>', 'title' => 'Název nejvyššího orgánu'),
            'ucel' => array('head' => '<span class="nounderline">Účel: </span>', 'title' => 'Účel'),
            'ucelNadacnihoFondu' => array('head' => '<span class="nounderline">Účel nadačního fondu: </span>', 'title' => 'Účel nadačního fondu'),
            'ucelNadace' => array('head' => '<span class="nounderline">Účel nadace: </span>', 'title' => 'Účel nadace'),
            // commented out because it spoils predseda: string in every other section
            // (e.g. https://or.justice.cz/ias/ui/rejstrik-firma.vysledky?subjektId=700616&typ=UPLNY)
            // here it spoils dozorci rada, because it contains predseda
            //'predseda' => array('head' => '<span class="nounderline">předseda: </span>', 'title' => 'předseda'),
            'vedouciOrganizacniSlozky' => array('head' => '<span class="nounderline">Vedoucí organizační složky: </span>', 'title' => 'Vedoucí organizační složky', 'isPerson' => true),
            'kontrolniKomise' => array('head' => '<span class="nounderline">Kontrolní komise: </span>', 'title' => 'Kontrolní komise', 'isPerson' => true),
            'clenoveDruzstva' => array('head' => '<span class="nounderline">Členové družstva: </span>', 'title' => 'Členové družstva', 'isPerson' => true),
            'druhObecneProspesnychSluzeb' => array('head' => '<span class="nounderline">Druh obecně prospěšných služeb: </span>', 'title' => 'Druh obecně prospěšných služeb'),
            'vycetMajetku' => array('head' => '<span class="nounderline">Výčet majetku: </span>', 'title' => 'Výčet majetku'),
            'zakladniClenskyVklad' => array('head' => '<span class="nounderline">Základní členský vklad: </span>', 'title' => 'Základní členský vklad'),
            'zapisovanyZakladniKapital' => array('head' => '<span class="nounderline">Zapisovaný základní kapitál: </span>', 'title' => 'Zapisovaný základní kapitál'),
            'odstepneZavody' => array('head' => '<span class="nounderline">Odštěpné závody: </span>', 'title' => 'Odštěpné závody'),
            'clenoveSdruzeni' => array('head' => '<span class="nounderline">Členové sdružení: </span>', 'title' => 'Členové sdružení', 'isPerson' => true),
            'predstavenstvo' => array('head' => '<span class="nounderline">Představenstvo: </span>', 'title' => 'Představenstvo', 'isPerson' => true),
            'nadace' => array('head' => '<span class="nounderline">Nadace: </span>', 'title' => 'Nadace'),
            'nadacniFond' => array('head' => '<span class="nounderline">Nadační fond: </span>', 'title' => 'Nadační fond'),
            'likvidatori' => array('head' => '<span class="nounderline">Likvidátoři: </span>', 'title' => 'Likvidátoři', 'isPerson' => true),
            'reditele' => array('head' => '<span class="nounderline">Ředitelé: </span>', 'title' => 'Ředitelé', 'isPerson' => true),
            'revizori' => array('head' => '<span class="nounderline">Revizoři: </span>', 'title' => 'Revizoři', 'isPerson' => true),
            'spolecniciBezVkladu' => array('head' => '<span class="nounderline">Společníci bez vkladu: </span>', 'title' => 'Společníci bez vkladu', 'isPerson' => true),
            'spolecniciSVkladem' => array('head' => '<span class="nounderline">Společníci s vkladem: </span>', 'title' => 'Společníci s vkladem', 'isPerson' => true),
            'akcionari' => array('head' => '<span class="nounderline">Akcionáři: </span>', 'title' => 'Akcionáři', 'isPerson' => true),

            // commented out, part of 'Ostatni skutecnosti'
            //'udajeOZakladatelich' => array('head' => '<span class="nounderline">Údaje o zakladatelích: </span>', 'title' => 'Údaje o zakladatelích', 'isPerson' => true),
            //'udajeOZrizovatelichOdstepnehoZavodu' => array('head' => '<span class="nounderline">Údaje o zřizovatelích odštěpného závodu: </span>', 'title' => 'Údaje o zřizovatelích odštěpného závodu', 'isPerson' => true),
            //'udajeOZrizovatelichPrispevkovychOrganizaci' => array('head' => '<span class="nounderline">Údaje o zřizovatelích příspěvkových organizací: </span>', 'title' => 'Údaje o zřizovatelích příspěvkových organizací', 'isPerson' => true),

            // commented out, not found anywhere
            //'pravniNastupceZahranicniOsoby' => array('head' => '<span class="nounderline">Právní nástupce zahraniční osoby: </span>', 'title' => 'Právní nástupce zahraniční osoby', 'isPerson' => true),
            //'pravniNastupciZrizovatele' => array('head' => '<span class="nounderline">Právní nástupci zřizovatele: </span>', 'title' => 'Právní nástupci zřizovatele', 'isPerson' => true),
        );
    }

    function getbaseinfo($headings, $raw_data, $tdname, $title, $removedeleted = true) {
        $result = '';
        $isfound = false;
        $inner = prepareBeforeFormat($headings, $raw_data, $removedeleted);

        foreach($inner->query('//'. $tdname) as $td) {
            $value = trim_ex($td->nodeValue);

            if ($value != '') {
                if ($isfound) {
                    $result = $value;
                    break;
                }

                $isfound = $value == $title;
            }
        }

        if (endsWith($result, ':')) {
            $result = 'údaj není známý';
        }

        return $result;
    }

    function prepareBeforeFormat($headings, $raw_data, $removedeleted = true) {
        $predmetPos = $raw_data;
        $text = substr($predmetPos, 0, findNextHeading($predmetPos, $headings));

        // remove not actual data
        if ($removedeleted) {
            $inner_dom = new DOMDocument();
            @$inner_dom->loadHTML($text);
            $inner = new DOMXPath($inner_dom);

            foreach($inner->query("//*[@class='underline']/..") as $oldNode) {
                $oldNode->parentNode->removeChild($oldNode);
            }

            $text = $inner_dom->saveXML(null, LIBXML_NOEMPTYTAG);
        }

        $inner_dom = new DOMDocument();
        @$inner_dom->loadHTML($text);
        return new DOMXPath($inner_dom);
    }

    function checkDeleted($headings, $raw_data, $tdname) {
        $inner = prepareBeforeFormat($headings, $raw_data);

        $isDeleted = false;
        foreach($inner->query('//'. $tdname) as $td) {
            $value = trim_ex($td->nodeValue);
            $isDeleted = $value == 'Datum výmazu:';

            if ($isDeleted) {
                break;
            }
        }

        return $isDeleted;
    }

    function formatBase($headings, $subject, $tdname) {
        $result = '';
        $subresult = '<dl class="dl_equal">';
        $inner = prepareBeforeFormat($headings, $subject['raw_data']);

        $lastreached = false;
        $lasttextreached = false;

        $isDeleted = false;
        foreach($inner->query('//'. $tdname) as $td) {
            $value = trim_ex($td->nodeValue);
            $isDeleted = $value == 'Datum výmazu:';

            if ($isDeleted) {
                $result .= '<dt></dt><dd><b>Subjekt byl smazán z obchodního rejstříku</b><br /><br /></dd>';
                break;
            }
        }

        if (!$isDeleted) {
            foreach($inner->query('//'. $tdname) as $td) {
                $value = trim_ex($td->nodeValue);

                if ($value != '') {
                    if (!$lasttextreached) {
                        if ($lastreached) {
                            $lasttextreached = true;
                        }

                        $isheading = endsWith($value, ':');

                        $subresult .= $isheading ? '<dt>' : '<dd>';
                        $subresult .= $value;
                        $subresult .= $isheading ? '</dt>' : '</dd>';

                        if ($value == 'Právní forma:') {
                            $lastreached = true;
                        }
                    } else {
                        $subresult .= '<dt></dt><dd>'. $value .'</dd>';
                    }
                }
            }
        }

        $base_kapital = get_base_kapital($subject['raw_data'], $headings, $tdname);
        if ($base_kapital != '') {
            $subresult .= '<dt>Základní kapitál:</dt>';
            $subresult .= '<dd>';
            $subresult .= $base_kapital;
            $subresult .= '</dd>';
        }

        $result .= $subresult .'</dl>';

        return $result;
    }

    function get_base_kapital($raw, $headings, $tdname) {
        $result = '';
        foreach ($headings as $key => $h) {
            if (stristr($key, 'zakladniKapital')) {
                $tempresult = formatCommonItems($raw, $h['head'], $headings, $h['title'], $tdname);
                $tempresult = strip_tags($tempresult);

                if (strstr($tempresult, "Základní kapitál") != false) {
                    $result = str_replace("Základní kapitál", "", $tempresult);
                    break;
                }
            }
        }

        return $result;
    }

    function findNextHeading($text, $headings) {
        $next = strlen($text);
        foreach ($headings as $heading) {
            $current = strpos($text, $heading['head']);
            if ($current <= $next && $current > 0) {
                $next = $current;
            }
        }

        return $next;
    }

    function makeHeading($text) {
        return '<h5>'. $text .'</h5>';
    }

    function blurPersons($raw_data) {
        if (isUserLoggedIn()) {
            // no need to blur if we're logged in
            return $raw_data;
        }

        $inner_dom = new DOMDocument();
        @$inner_dom->loadHTML('<?xml encoding="utf-8" ?>'. $raw_data);
        $inner = new DOMXPath($inner_dom);

        $birthdateNodes = $inner->query("//*[contains(text(),'dat. nar.')]");
        foreach($birthdateNodes as $birthdateNode) {
            $parentNode = $inner->query("ancestor::div[contains(@class,'div-cell')][1]", $birthdateNode);
            $parentNode = $parentNode->length > 0
                ? $parentNode->item(0)
                : $birthdateNode;

            // mark text to be scrambled
            $parentNode->nodeValue = mb_str_shuffle($parentNode->nodeValue);

            $class = $parentNode->getAttribute("class");
            $parentNode->setAttribute("class", $class . ' blurred');
        }

        // save node by node, inspired at: https://stackoverflow.com/a/28626737/2470765
        $result = '';
        foreach ($inner->evaluate('/html/body/node()') as $node) {
            $result .= $inner_dom->saveHtml($node);
        }      
        
        return $result;
    }

    function formatStatutarniOrgan($raw_data, $heading, $headings, $headingtitle, $tdname) {
        $result = '';
        $subresult = '';

        $text = getSectionText($raw_data, $heading, $headings);
        if ($text == false) {
            return;
        }

        $result .= makeHeading($headingtitle);
        $result .= '<ul class="three_cols">';

        $tds = removeNotActualData($text, $tdname);

        $firstheadingoutput = false;
        $zpusobJednaniOutput = false;
        for($i = 0; $i < $tds->length; $i++) {
            $value = trim_ex(utf8_decode($tds->item($i)->nodeValue));

            if ($zpusobJednaniOutput) {
                $zpusobJednaniOutput = false;
            }

            if ($value == 'Způsob jednání:') {
                $zpusobJednaniOutput = true;
            }

            if ($value != '' && strlen($value) > 2) {
                $isheading = endsWith($value, ':');
                
                if (detectOutput($value, $isheading, $tds, $i)) {
                    if ($firstheadingoutput && $isheading) {
                        $subresult .= '</li>';
                    }

                    if ($isheading) {
                        $firstheadingoutput = true;
                    }

                    if ($isheading) {
                        $subresult .= $zpusobJednaniOutput ? '<li style="width: 100%; clear: both;">' : '<li>';
                    }

                    $subresult .= formatOutput($tds, $i);
                }
            }
        }

        return $result . $subresult . '</ul>';
    }

    function formatCommonPersons($raw_data, $heading, $headings, $headingtitle, $tdname, $innerHeading = '') {
        $result = '';
        $subresult = '';

        $text = getSectionText($raw_data, $heading, $headings);
        if ($text == false) {
            return;
        }

        $result .= makeHeading($headingtitle);
        $result .= '<ul class="three_cols">';

        $tds = removeNotActualData($text, $tdname);

        $firstheadingoutput = false;
        for($i = 0; $i < $tds->length; $i++) {
            $value = trim_ex(utf8_decode($tds->item($i)->nodeValue));

            if ($value != '' && strlen($value) > 2) {
                $isInnerHeading = $innerHeading != '' && stristr($value, $innerHeading) !== false;
                $isheading = endsWith($value, ':');

                if (detectOutput($value, $isheading, $tds, $i)) {
                    if ($firstheadingoutput && $isheading && !$isInnerHeading) {
                        $subresult .= '</li>';
                    }

                    if ($isheading) {
                        $firstheadingoutput = true;
                    }

                    if ($isheading && !$isInnerHeading) {
                        $subresult .= '<li>';
                    }

                    $subresult .= formatOutput($tds, $i);
                }
            }
        }

        return $result . $subresult . '</ul>';
    }

    function formatVedouciOdstepnehoZavodu($raw_data, $heading, $headings, $headingtitle, $tdname) {
        $result = '';
        $subresult = '';

        $text = getSectionText($raw_data, $heading, $headings);
        if ($text == false) {
            return;
        }

        $result .= makeHeading($headingtitle);
        $result .= '<ul class="three_cols">';

        $tds = removeNotActualData($text, $tdname);

        for($i = 0; $i < $tds->length; $i++) {
            $value = trim_ex(utf8_decode($tds->item($i)->nodeValue));

            if ($value != '' && strlen($value) > 2 && detectOutput($value, false, $tds, $i)) {
                $subresult .= '<li>'. formatOutput($tds, $i) .'</li>';
            }
        }

        return $result . $subresult . '</ul>';
    }

    function formatPredmetPodnikani($raw_data, $heading, $headings, $headingtitle, $tdname) {
        $result = '';
        $subresult = '';

        $text = getSectionText($raw_data, $heading, $headings);
        if ($text == false) {
            return;
        }

        $result .= makeHeading($headingtitle);
        $result .= '<ul>';

        $tds = removeNotActualData($text, $tdname);

        $firstheadingoutput = false;
        for($i = 0; $i < $tds->length; $i++) {
            $value = trim_ex(utf8_decode($tds->item($i)->nodeValue));

            if ($value != '' && strlen($value) > 2) {
                $subresult .= '<li>'. $value .'</li>';
            }
        }

        return $subresult != '' ? ($result . $subresult . '</ul>') : '';
    }

    function formatCommonItems($raw_data, $heading, $headings, $headingtitle, $tdname) {
        $result = '';
        $subresult = '';

        $text = getSectionText($raw_data, $heading, $headings);
        if ($text == false) {
            return;
        }

        $result .= makeHeading($headingtitle);

        $tds = removeNotActualData($text, $tdname);

        $firstheadingoutput = false;
        for($i = 0; $i < $tds->length; $i++) {
            $value = trim_ex(utf8_decode($tds->item($i)->nodeValue));

            if ($value != '' && strlen($value) > 2) {
                $subresult .= '<p>'. $value .'</p>';
            }
        }

        return $subresult != '' ? ($result . $subresult) : '';
    }

    function removeNotActualData($text, $tdname) {
        $inner_dom = new DOMDocument();
        @$inner_dom->loadHTML($text);
        $inner = new DOMXPath($inner_dom);

        foreach($inner->query("//*[@class='underline']/..") as $oldNode) {
            $oldNode->parentNode->removeChild($oldNode);
        }

        return $inner->query('//'. $tdname);
    }

    function detectOutput($value, $isheading, $tds, $i) {
        $output = !$isheading;

        if ($isheading) {
            // search next value whether it is not a heading
            for($a = $i + 1; $a < $tds->length; $a++) {
                $nextValue = trim_ex(utf8_decode($tds->item($a)->nodeValue));
                if ($nextValue != '' && strlen($nextValue) > 2) {
                    $output = !endsWith($nextValue, ':');
                    break;
                }
            }
        }

        return $output;
    }

    function getSectionText($raw_data, $heading, $headings) {
        $result = false;

        $predmetPos = strstr($raw_data, $heading);
        if ($predmetPos !== FALSE) {
            $predmetPos = str_replace($heading, '', $predmetPos);
            $result = substr($predmetPos, 0, findNextHeading($predmetPos, $headings));
        }

        return $result;
    }

    function formatOutput($tds, $i) {
        $html = utf8_decode($tds->item($i)->ownerDocument->saveXML($tds->item($i)));

        $strip_list = array('br', 'a');
        foreach ($strip_list as $tag)
        {
            $html = preg_replace('/<\/?' . $tag . '(.|\s)*?>/', '', $html);
        }

        return $html;
    }

    function preProcessRawData($raw_data, $tdname) {
        $inner_dom = new DOMDocument();
        @$inner_dom->loadHTML($raw_data);
        $inner = new DOMXPath($inner_dom);

        // remove td which define zapsano/vymazano
        foreach($inner->query("//". $tdname ."[starts-with(normalize-space(.), 'zapsáno')]") as $timeNode) {
            $timeNode->parentNode->removeChild($timeNode);
        }

        $raw_data = $inner_dom->saveXML(null, LIBXML_NOEMPTYTAG);

        // escape html entities
        $raw_data = html_entity_decode($raw_data, ENT_NOQUOTES, "utf-8");

        return $raw_data;
    }

    function determineFormat($raw_data) {
        return !strstr($raw_data, '<div class="div-cell') ? 'td' : 'div[contains(@class, "div-cell")]';
    }

    function getSpisMark($raw_data) {
        $tdname = determineFormat($raw_data);
        $headings = getheadings();
        $content_base = preProcessRawData($raw_data, $tdname);

        return getbaseinfo($headings, $content_base, $tdname, 'Spisová značka:', true);
    }

    function removeBold($raw_data) {
        return str_ireplace("font-weight: bold;", "", $raw_data);
    }

    function removeInsolvencyHeadings($raw_data) {
        $toremove = array("Údaje o insolvencích:", "Údaje o insolvenci:");
        return str_ireplace($toremove, "", $raw_data);
    }

    function get_or_uplny_link($justiceId) {
        return 'https://or.justice.cz/ias/ui/rejstrik-firma.vysledky?subjektId='. $justiceId .'&typ=UPLNY';
    }

?>