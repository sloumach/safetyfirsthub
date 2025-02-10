document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('exampleModal');
    const checkbox = document.getElementById('dontShowAgain');
    const hidePopup = localStorage.getItem('hideNewsletterPopup');

    // Fonction pour nettoyer le modal et backdrop Bootstrap
    const cleanupModal = () => {
        document.querySelector('.modal-backdrop')?.remove();
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
    };

    // Vérifier si la popup doit être cachée
    if (hidePopup === 'true' && modalElement) {
        modalElement.remove();
        return;
    }

    if (!modalElement) return;

    // Initialisation sécurisée du modal
    try {
        modalElement.setAttribute('data-bs-backdrop', 'static');
        const modalInstance = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true
        });

        setTimeout(() => modalInstance.show(), 2000);

        // Nettoyage après fermeture
        modalElement.addEventListener('hidden.bs.modal', cleanupModal);

        // Gestion du checkbox "Ne plus afficher"
        checkbox?.addEventListener('change', function () {
            if (this.checked) {
                localStorage.setItem('hideNewsletterPopup', 'true');
                modalInstance.hide();
                setTimeout(() => {
                    modalElement.remove();
                    cleanupModal();
                }, 300);
            }
        });
    } catch (error) {
        console.error('Bootstrap Modal Error:', error);
    }
});