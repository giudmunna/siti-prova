<?php
function getContent() {
    $file = __DIR__ . '/content.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true);
    }
    return [];
}

function getCorsoBySlug(array $content, string $slug): ?array {
    foreach ($content['corsi']['lista'] ?? [] as $corso) {
        if (($corso['slug'] ?? '') === $slug) {
            return $corso;
        }
    }
    return null;
}

function corsiByCategoria(array $content): array {
    $gruppi = [];
    foreach ($content['corsi']['lista'] ?? [] as $corso) {
        $cat = $corso['categoria'] ?? 'altro';
        $gruppi[$cat][] = $corso;
    }
    return $gruppi;
}

function corsoUrl(array $corso): string {
    return 'corso.php?slug=' . urlencode($corso['slug'] ?? '');
}

function corsoHasSchedule(array $corso): bool {
    return !empty($corso['orari_settimana']) || !empty($corso['orari']);
}

function phoneTel(string $number): string {
    $digits = preg_replace('/\D+/', '', $number);
    if ($digits !== '' && $digits[0] !== '0' && strlen($digits) <= 10) {
        $digits = '39' . $digits;
    }
    return '+' . ltrim($digits, '+');
}

$content = getContent();
?>
