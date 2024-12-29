class PaymentModel extends Fronty.Model {
    constructor() {
      super();
      this.payments = []; // Lista de pagos
      this.selectedPayment = null; // Pago actualmente seleccionado
    }
  
    setPayments(payments) {
      this.payments = payments;
      this.notifyAll();
    }
  
    getPayments() {
      return this.payments;
    }
  
    addPayment(payment) {
      this.payments.push(payment);
      this.notifyAll();
    }
  
    removePayment(paymentId) {
      this.payments = this.payments.filter(payment => payment.id !== paymentId);
      this.notifyAll();
    }
  
    selectPayment(paymentId) {
      this.selectedPayment = this.payments.find(payment => payment.id === paymentId);
      this.notifyAll();
    }
  
    getSelectedPayment() {
      return this.selectedPayment;
    }
  }
  