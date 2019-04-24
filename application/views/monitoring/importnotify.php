<p class="success">
    <strong>Generování reportu proběhlo úspěšně.</strong>
</p>

<?php
    if (sizeof($emails_collection) > 0) {
        echo 'Zaslány byly následujúci zprávy:<br /><br />';
        echo '<div class="respo-table">';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<td>Adresát</td>';
        echo '<td>Předmět</td>';
        echo '</tr>';
        echo '</thead>';
        
        foreach ($emails_collection as $email) {
            echo '<tr>';
            echo '<td>'. $email['to'] .'</td>';
            echo '<td>'. $email['subject'] .'</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p>Při generováni reportu nebyly zjišteny žádné nové události a žádné emaily nebyly odeslány.</p>';
    }
?>