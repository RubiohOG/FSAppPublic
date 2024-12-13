document.addEventListener('DOMContentLoaded', function () {
    const editPaymentModal = document.getElementById('editPaymentModal');
    editPaymentModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const paymentId = button.getAttribute('data-payment-id');
        const paymentName = button.getAttribute('data-payment-name');
        const amount = button.getAttribute('data-amount');
        const beneficiaries = JSON.parse(button.getAttribute('data-beneficiaries'));

        document.getElementById('editPaymentId').value = paymentId;
        document.getElementById('editPaymentName').value = paymentName;
        document.getElementById('editAmount').value = amount;

        document.querySelectorAll('#editPaymentForm .form-check-input').forEach(checkbox => {
            checkbox.checked = false;
        });
        console.log(beneficiaries);
        beneficiaries.forEach(beneficiary => {
            const checkbox = document.getElementById('editBeneficiary_' + beneficiary);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    });
});
