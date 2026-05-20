<?php
function getContent() {
    $file = __DIR__ . '/content.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true);
    }
    return [];
}

$content = getContent();
?>