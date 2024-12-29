document.addEventListener('DOMContentLoaded', function () {
    const confirmDeleteModal = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModal) {
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (button) {
                const paymentId = button.getAttribute('data-payment-id');
                if (paymentId) {
                    console.log("Payment ID to delete:", paymentId);
                    const paymentIdToDelete = document.getElementById('paymentIdToDelete');
                    paymentIdToDelete.value = paymentId;
                    console.log("Payment ID set in hidden input:", paymentIdToDelete.value);
                } else {
                    console.warn("El payment ID no está definido en el botón.");
                }
            } else {
                console.error("Botón que activó el modal no encontrado.");
            }
        });
    } else {
        console.error("Modal de confirmación no encontrado.");
    }
});
