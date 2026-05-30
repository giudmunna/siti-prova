<?php
/** @var array $corso */
$settimana = $corso['orari_settimana'] ?? [];
$orari = $corso['orari'] ?? [];
?>
<?php if (empty($settimana) && empty($orari)): ?>
  <p class="schedule-empty">Gli orari per questo corso saranno pubblicati a breve.</p>
<?php elseif (!empty($settimana)): ?>
  <div class="schedule-week">
    <?php foreach ($settimana as $giorno): ?>
      <div class="schedule-day">
        <div class="schedule-day-name"><?= htmlspecialchars($giorno['giorno'] ?? '') ?></div>
        <div class="schedule-slots">
          <?php foreach ($giorno['fasce'] ?? [] as $fascia): ?>
            <span class="schedule-slot"><?= htmlspecialchars($fascia) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="schedule-table-wrap" role="region" aria-label="Tabella orari">
    <table class="schedule-table">
      <thead>
        <tr>
          <th>Giorni</th>
          <th>Orario</th>
          <th>Livello</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orari as $riga): ?>
          <tr>
            <td><?= htmlspecialchars($riga['giorni'] ?? '—') ?></td>
            <td><?= htmlspecialchars($riga['orario'] ?? '—') ?></td>
            <td><?= htmlspecialchars($riga['livello'] ?? '—') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
