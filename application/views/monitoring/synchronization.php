<main>
    <div class="inner rs_form">

        <?php $this->load->view('inc/leftmenu'); ?>

        <div class="rs_form__body">
            <h1>Synchronizace</h1>

            <?php
                if (sizeof($logs) == 0) {
                    echo '<p>Žádné záznamy nebyly nalezeny.';
                    echo '<br /><br />';
                    echo 'Váš uživatelský účet nevyužívá žádnou z možností synchronizace s externími systémy ';
                    echo '(Helios, Money S3, ...). Pro více informací o synchronizaci a její nastavení nás <a href="kontakty">kontaktujte</a>.';
                    echo '</p>';
                } else {
            ?>

            <p>Váš účet používá synchronizaci se systémem Helios.</p>
            <div class="respo-table">
                <table>
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Správa</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($logs as $i) {
                            echo '<tr>';

                            echo '<td>'. date("d.m.Y H:i", strtotime($i['date'])) .'</td>';
                            
                            $textClass = '';
                            if (strpos($i['message'], 'Synchronizace nebyla úspěšná.') !== false) {
                                $textClass = ' class="error bold"';
                            } else if (strpos($i['message'], 'Záznam nelze synchronizovat') !== false) {
                                $textClass = ' class="error"';
                            } else if (strpos($i['message'], 'Synchronizace byla úspěšná.') !== false) {
                                $textClass = ' class="success bold"';
                            }
                            
                            echo '<td'. $textClass .'>'. nl2br($i['message']) .'</td>';

                            echo '</tr>';
                        }
                    ?>
                </table>
                
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</main>