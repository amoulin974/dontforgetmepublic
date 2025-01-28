document.addEventListener('DOMContentLoaded', function () {
    const reservationModal = document.getElementById('reservationModal');

    reservationModal.addEventListener('show.bs.modal', function (event) {
        // Bouton déclencheur de la modale
        const button = event.relatedTarget;

        // Récupérer les données des attributs data
        if(button) {
            const selectedHoraire = button.getAttribute('data-horaire');
            const selectedDate = button.getAttribute('data-date');

            // Afficher les données dans la modale
            document.getElementById('selectedHoraire').textContent = selectedHoraire;
            document.getElementById('selectedDate').textContent = selectedDate;

            // Si nécessaire, mettre à jour des champs cachés
            document.getElementById('hiddenHoraire').value = selectedHoraire;
            document.getElementById('hiddenDateRdv').value = selectedDate;
        }
    });

    // Ajouter une notification
    document.getElementById('addNotificationBtn').addEventListener('click', function () {
        const reservationModal = bootstrap.Modal.getInstance(document.getElementById('reservationModal'));
        reservationModal.hide();

        const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
        notificationModal.show();
    });

    // Retour à la modal de réservation
    document.getElementById('goBackBtn').addEventListener('click', function () {
        const notificationModal = bootstrap.Modal.getInstance(document.getElementById('notificationModal'));
        notificationModal.hide();

        const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
        reservationModal.show();
    });

    // Validation d'une notification
    document.getElementById('saveNotificationBtn').addEventListener('click', function () {
        const typeNotification = document.querySelector('input[name="typeNotification"]:checked').value;
        const contenu = typeNotification === 'SMS'
            ? document.getElementById('smsInput').value
            : document.getElementById('mailInput').value;
        const duree = document.getElementById('duree').value;

        if (contenu.trim() === '') {
            alert('Veuillez renseigner le contenu de la notification.');
            return;
        }

        // Ajouter une notification à la liste
        const notificationItem = document.createElement('li');
        notificationItem.className = 'list-group-item';
        notificationItem.textContent = `${typeNotification} - ${contenu} - Rappel : ${duree}`;
        notificationsList.appendChild(notificationItem);

        // Ajouter un input caché pour chaque notification
        ['typeNotification', 'contenu', 'duree'].forEach((key) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `notifications[${notificationsList.children.length - 1}][${key}]`;
            input.value = key === 'contenu' ? contenu : key === 'typeNotification' ? typeNotification : duree;
            reservationForm.appendChild(input);
        });

        const notificationModal = bootstrap.Modal.getInstance(document.getElementById('notificationModal'));
        notificationModal.hide();

        const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
        reservationModal.show();
    });
    document.getElementById('smsOption').addEventListener('change', function () {
        document.getElementById('smsField').style.display = 'block';
        document.getElementById('mailField').style.display = 'none';
    });

    document.getElementById('mailOption').addEventListener('change', function () {
        document.getElementById('smsField').style.display = 'none';
        document.getElementById('mailField').style.display = 'block';
    });
});
