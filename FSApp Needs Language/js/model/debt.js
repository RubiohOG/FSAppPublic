class DebtModel extends Fronty.Model {
    constructor() {
      super();
      this.debts = [];
    }
  
    setDebts(debts) {
      this.debts = debts;
      this.notifyAll();
    }
  
    getDebts() {
      return this.debts;
    }
  
    addDebt(debt) {
      this.debts.push(debt);
      this.notifyAll();
    }
  
    clearDebts() {
      this.debts = [];
      this.notifyAll();
    }
  }
  