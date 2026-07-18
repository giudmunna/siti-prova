<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
admin_require_login();

$conn = db_connect();
$error = '';
$saved = false;

$defaults = [
    'site.name' => 'Sirio Fit & Dance - Alcamo',
    'site.legal_name' => 'SIRIO SSD ARL',
    'site.tagline' => 'Scuola di Danza e Fitness',
    'site.email' => 'SIRIOSSDARL@GMAIL.COM',
    'site.phone_mobile' => '379 1420911',
    'site.phone_landline' => '0924 040296',
    'site.address' => 'Via A. Pertini, 16 — 91011 Alcamo (TP)',
    'site.vat' => '02877410817',
    'home.hero_title' => 'Sirio Fit & Dance',
    'home.hero_subtitle' => 'Passione • Movimento • Energia',
    'home.hero_button' => 'Scopri i nostri corsi',
    'chi_siamo.title' => 'Chi Siamo',
    'chi_siamo.text' => 'La Sirio Fit & Dance di Alcamo è una scuola di danza e fitness attiva da anni sul territorio.',
    'corsi.title' => 'I Nostri Corsi',
    'eventi.title' => 'Eventi & Spettacoli',
    'eventi.text' => 'Scopri i prossimi eventi, stage e spettacoli della Sirio Fit & Dance.',
    'contatti.title' => 'Contattaci',
    'contatti.button' => 'Invia Messaggio',
];

if ($conn === null) {
    $error = 'Impossibile connettersi al database MySQL.';
} else {
    $result = $conn->query('SELECT content_key, content_value FROM site_content WHERE content_key = "site_data" LIMIT 1');
    $siteData = [];
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $decoded = json_decode($row['content_value'], true);
        if (is_array($decoded)) {
            $siteData = $decoded;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updates = [];
        foreach ($defaults as $key => $defaultValue) {
            $parts = explode('.', $key);
            $value = trim((string) ($_POST[$key] ?? ''));
            if ($value === '') {
                $value = $defaultValue;
            }
            $ref = &$siteData;
            foreach ($parts as $part) {
                if (!isset($ref[$part]) || !is_array($ref[$part])) {
                    $ref[$part] = [];
                }
                $ref = &$ref[$part];
            }
            $ref = $value;
            $updates[$key] = $value;
        }

        $json = json_encode($siteData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $stmt = $conn->prepare('INSERT INTO site_content (content_key, content_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE content_value = VALUES(content_value)');
        $key = 'site_data';
        $stmt->bind_param('ss', $key, $json);
        $stmt->execute();
        $stmt->close();
        $saved = true;
    }
}

$pageTitle = 'Gestione testi';
$activePage = 'testi';
require __DIR__ . '/includes/layout-top.php';
?>

<div class="admin-card">
  <h1>Gestione testi del sito</h1>
  <p class="lead">Modifica i testi che compaiono in home, chi siamo, corsi, eventi e contatti senza toccare il codice.</p>

  <?php if (!empty($error)): ?>
    <div class="admin-alert error" role="alert"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($saved): ?>
    <div class="admin-alert success" role="status">Testi aggiornati con successo.</div>
  <?php endif; ?>

  <form class="admin-form" method="POST">
    <?php foreach ($defaults as $key => $defaultValue): ?>
      <?php
        $parts = explode('.', $key);
        $value = $defaultValue;
        $ref = $siteData;
        foreach ($parts as $part) {
            if (isset($ref[$part])) {
                $ref = $ref[$part];
            } else {
                $ref = $defaultValue;
                break;
            }
        }
        if (is_array($ref)) {
            $value = $defaultValue;
        } else {
            $value = $ref;
        }
      ?>
      <div class="field">
        <label for="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($key) ?></label>
        <input type="text" id="<?= htmlspecialchars($key) ?>" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
      </div>
    <?php endforeach; ?>
    <button type="submit" class="btn-admin">Salva testi</button>
  </form>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>