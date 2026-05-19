<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: index.php");
    exit;
}

if ($_POST) {
    $eventsFile = 'events.json';
    $events = file_exists($eventsFile) ? json_decode(file_get_contents($eventsFile), true) : [];

    $newEvent = [
        "title" => $_POST['title'],
        "date"  => $_POST['date'],
        "description" => $_POST['description'],
        "location" => $_POST['location']
    ];

    $events[] = $newEvent;
    file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));

    echo "<script>alert('Evento aggiunto con successo!'); window.location='dashboard.php';</script>";
}
?>

<form method="POST">
  <input type="text" name="title" placeholder="Titolo Evento" required><br><br>
  <input type="date" name="date" required><br><br>
  <textarea name="description" placeholder="Descrizione" rows="5" required></textarea><br><br>
  <input type="text" name="location" placeholder="Luogo" required><br><br>
  <button type="submit">Salva Evento</button>
</form>