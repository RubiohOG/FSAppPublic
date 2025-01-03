class PaymentService {
  constructor() {
 
  }

    createPayment(projectId, paymentData) {
        console.log(paymentData);
        console.log("ID del proyecto en el que se hará el pago:" + projectId);
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `/rest/project-details/${projectId}/add-payment`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(paymentData),
                success: resolve,
                error: (xhr) => {
                    console.error('Error al crear el pago:', xhr.responseText);
                    reject(new Error(xhr.responseText || 'Error desconocido al crear el pago'));
                }
            });
        });
    }

    deletePayment(projectId, paymentId) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `/rest/project-details/${projectId}/delete-payment/${paymentId}`,
                method: 'DELETE',
                success: resolve,
                error: (xhr) => {
                    console.error('Error al eliminar el pago:', xhr.responseText);
                    reject(new Error(xhr.responseText || 'Error desconocido al eliminar el pago'));
                }
            });
        });
    }

    editPayment(projectId, paymentId, paymentData) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `/rest/project-details/${projectId}/edit-payment/${paymentId}`,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(paymentData),
                success: resolve,
                error: (xhr) => {
                    console.error('Error al editar el pago:', xhr.responseText);
                    reject(new Error(xhr.responseText || 'Error desconocido al editar el pago'));
                }
            });
        });
    }
    
    
    
}