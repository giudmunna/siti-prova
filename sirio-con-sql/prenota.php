<?php
$bodyClass = 'page-prenota';
require 'header.php';

require_once __DIR__ . '/includes/db.php';

$corsoSlug = trim($_GET['corso'] ?? '');
$corsoSelezionato = $corsoSlug !== '' ? getCorsoBySlug($content, $corsoSlug) : null;
$status = $_GET['status'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $email = '';
    $telefono = trim((string) ($_POST['telefono'] ?? ''));
    $corsoId = isset($_POST['corso_id']) ? (int) $_POST['corso_id'] : 0;
    $messaggio = trim((string) ($_POST['messaggio'] ?? ''));

    if ($nome !== '' && $messaggio !== '') {
        $conn = db_connect();
        if ($conn !== null) {
            $stmt = $conn->prepare('INSERT INTO reservations (corso_id, nome, email, telefono, messaggio) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('issss', $corsoId, $nome, $email, $telefono, $messaggio);
            $stmt->execute();
            $stmt->close();
        }
        header('Location: prenota.php?status=success');
        exit;
    }

    header('Location: prenota.php?status=error');
    exit;
}
?>

<section class="section">
  <div class="container">
    <h2>Prenota una prova</h2>
    <p class="lead">Compila il modulo: la richiesta verrà salvata nel database locale di XAMPP e comparirà anche nella sezione amministrazione.</p>

    <?php if ($status === 'success'): ?>
      <div class="admin-alert success" role="status" style="max-width: 760px; margin: 24px auto 0;">Prenotazione ricevuta. Ti contatteremo appena possibile.</div>
    <?php elseif ($status === 'error'): ?>
      <div class="admin-alert error" role="alert" style="max-width: 760px; margin: 24px auto 0;">Compila tutti i campi richiesti.</div>
    <?php endif; ?>

    <div class="contact-grid" style="margin-top: 32px;">
      <div class="contact-info">
        <p><strong>Perché questo sistema?</strong></p>
        <p>La prenotazione viene salvata direttamente in archivio e resa visibile nell’area admin, senza bisogno di inviare una email.</p>
        <p style="margin-top: 12px;"><strong>Corso selezionato:</strong> <?= htmlspecialchars($corsoSelezionato['titolo'] ?? 'Nessuno') ?></p>
      </div>

      <form action="prenota.php" method="POST" class="contact-form">
        <input type="text" name="nome" placeholder="Nome e Cognome" required>
        <input type="tel" name="telefono" placeholder="Telefono (facoltativo)">
        <select name="corso_id" required>
          <option value="">Seleziona un corso</option>
          <?php foreach (getAllCourses() as $corso): ?>
            <option value="<?= (int) $corso['id'] ?>" <?= ($corsoSelezionato && ($corsoSelezionato['slug'] ?? '') === $corso['slug']) ? 'selected' : '' ?>><?= htmlspecialchars($corso['titolo']) ?></option>
          <?php endforeach; ?>
        </select>
        <textarea name="messaggio" placeholder="Dicci quando vuoi venire e quale corso ti interessa..." rows="7" required></textarea>
        <button type="submit" class="btn">Invia prenotazione</button>
      </form>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>