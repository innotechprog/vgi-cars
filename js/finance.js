function calculateInstallment(principal, annualRate, months) {
  const monthlyRate = annualRate / 100 / 12;
  if (monthlyRate === 0) {
    return principal / months;
  }

  const factor = Math.pow(1 + monthlyRate, months);
  return (principal * monthlyRate * factor) / (factor - 1);
}

function initFinanceCalculator() {
  const form = document.getElementById("financeForm");
  const result = document.getElementById("financeResult");

  if (!form || !result) {
    return;
  }

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const price = Number(document.getElementById("financePrice").value || 0);
    const deposit = Number(document.getElementById("financeDeposit").value || 0);
    const interest = Number(document.getElementById("financeInterest").value || 0);
    const months = Number(document.getElementById("financeMonths").value || 1);

    const financed = Math.max(price - deposit, 0);
    const monthly = calculateInstallment(financed, interest, months);

    const formatter = new Intl.NumberFormat("en-ZA", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });

    result.textContent = `Estimated monthly installment: R ${formatter.format(monthly)}`;
  });
}

document.addEventListener("DOMContentLoaded", initFinanceCalculator);
