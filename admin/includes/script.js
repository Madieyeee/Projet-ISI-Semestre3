//Ca ne marchais pas, je l'ai enlevé

function ouvrirModal(event, action, id = null) {
    const modal = document.getElementById('modal');
    modal.style.display = 'block';
    document.getElementById('action').value = action;
    document.getElementById('modalTitle').textContent = action === 'ajouter' ? 'Ajouter un élève' : 'Modifier un élève';

    if (action === 'modifier') {
        const btn = event.currentTarget;
        document.getElementById('id').value = id;
        document.getElementById('modalMatricule').value = btn.dataset.matricule;
        document.getElementById('modalNom').value = btn.dataset.nom;
        document.getElementById('modalEmail').value = btn.dataset.email;
    } else {
        document.getElementById('id').value = '';
        document.getElementById('modalMatricule').value = '';
        document.getElementById('modalNom').value = '';
        document.getElementById('modalEmail').value = '';
        document.getElementById('modalPassword').value = '';
    }
}

function fermerModal() {
    document.getElementById('modal').style.display = 'none';
}

window.onclick = function(e) {
    if (e.target === document.getElementById('modal')) fermerModal();
}