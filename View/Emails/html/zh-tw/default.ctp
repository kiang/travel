<?php
$content = explode("\n", $message);

foreach ($content as $line):
    echo '<p> ' . $line . '</p>';
endforeach;