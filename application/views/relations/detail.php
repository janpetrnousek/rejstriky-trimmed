<dl class="dl_equal">
    <dt>Název:</dt>
    <dd><?php echo $subject['name']; ?></dd>
    <dt>IČ:</dt>
    <dd><?php echo isset($subject['ic']) ? formatIc($subject['ic']) : 'neuvedeno'; ?></dd>
    <dt>Adresa:</dt>
    <dd><?php echo $subject['address']; ?></dd>
</dl>       

<h5>Propojené subjekty</h5>
<ul class="three_cols">
    <?php
        $separator = '|||';
        
        $relations = array();
        foreach($nodes as $node) {
            if (isset($subject['is_relation'])) {
                if ($node['id'] == $subject['id']) {
                    $rootkeyname = $root['id'] . $separator . $root['name'];
                    if (!isset($relations[$rootkeyname])) {
                        $relations[$rootkeyname] = array();
                    }
                    
                    array_push($relations[$rootkeyname], $node);
                }
            } else {
                $nodekeyname = $node['id'] . $separator . $node['name'];
                if (!isset($relations[$nodekeyname])) {
                    $relations[$nodekeyname] = array();
                }
                
                array_push($relations[$nodekeyname], $node);
            }
        }
        
        foreach ($relations as $key => $relation) {
            $keyparts = explode($separator, $key);
            
            $isBlurred = !isUserLoggedIn() && sizeof($relation) > 0 && $relation[0]['type'] == 'person';
            $class = $isBlurred ? ' class="blurred"' : '';

            $name = word_limiter($keyparts[1], 40);
            if ($isBlurred) {
                $name = mb_str_shuffle($name);
            }

            echo '<li id="target_'. $keyparts[0] .'"'. $class .'>';
            echo '<strong>'. $name .'</strong>';
            echo '<br />';

            foreach ($relation as $item) {
                echo '<table>';
                echo '<tr>';

                echo '<td class="vertical-align-top">';
                echo '<div style="background-color: #'. $item['relation_type_color'] .';" class="relation_identifier"></div>';
                echo '</td>';
                
                echo '<td class="vertical-align-top">';
                if ($item['relation_type_name'] == 'Člen statutárního orgánu') {
                    echo $item['relation_type_name'];
                    
                    if ($item['relation_type_name'] != $item['relation_type_detail_name']) {
                        echo ' - '. mb_strtolower($item['relation_type_detail_name']);
                    }
                } else {
                    echo $item['relation_type_detail_name'];
                }

                echo '</td>';

                echo '</tr>';
                echo '</table>';
            }

            echo '</li>';
        }
    ?>
</ul>