document.addEventListener('DOMContentLoaded', function() {
    // Initialiser DataTable si nécessaire
    if (document.getElementById('doctors-table')) {
        $('#doctors-table').DataTable({
            responsive: true
        });
    }
    
    const deleteForms = document.querySelectorAll('form[action*="destroy"]');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce médecin ?')) {
                e.preventDefault();
            }
        });
    });
    // Autres scripts spécifiques aux médecins
});
