  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const scheduleRows = document.getElementById('schedule-rows');
      const addButton = document.getElementById('add-schedule-row');

      if (!scheduleRows || !addButton) {
        return;
      }

      addButton.addEventListener('click', function () {
        const rows = scheduleRows.querySelectorAll('.schedule-row');
        const index = rows.length;
        const row = document.createElement('tr');
        row.className = 'schedule-row';
        row.innerHTML = '<td><input type="text" name="schedule[' + index + '][giorno]" value="" placeholder="es. Lunedì"></td><td><input type="text" name="schedule[' + index + '][fasce]" value="" placeholder="09:00, 18:30"></td><td><button type="button" class="btn-admin secondary small remove-schedule-row">Rimuovi</button></td>';
        scheduleRows.appendChild(row);
      });

      scheduleRows.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-schedule-row');
        if (!button) {
          return;
        }

        const row = button.closest('.schedule-row');
        if (!row) {
          return;
        }

        const rows = scheduleRows.querySelectorAll('.schedule-row');
        if (rows.length > 1) {
          row.remove();
        }
      });
    });
  </script>
</body>
</html>
