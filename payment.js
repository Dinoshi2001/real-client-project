document.addEventListener('DOMContentLoaded', function () {
    

    let checkoutBtn = document.getElementById('pay-checkout');
    let proceedBtn = document.getElementById('proceedBtn');
    let form = document.getElementById('paymentForm')
    
    // New Form Validation
    const cardNumberInput = document.getElementById("card-number");

    const expiryMonthInput = document.getElementById("expiration-month");
    const expiryYearInput = document.getElementById("expiration-year");
    const vccInput = document.getElementById("cvn");
    
    cardNumberInput.addEventListener("input", (e) => {
        let value = e.target.value.replace(/\s+/g, "").replace(/(\d{4})/g, "$1 ").trim();
        e.target.value = value;
    });
    
    



    function validateCardDetails(cardNumber, expiryMonth, expiryYear, vcc) {
        let cardNumberPattern = /^\d{16}$/;
        let expiryMonthPattern = /^(0[1-9]|1[0-2])$/;
        let expiryYearPattern = /^\d{4}$/;
        let vccPattern = /^\d{3}$/;

        return cardNumberPattern.test(cardNumber) &&
            expiryMonthPattern.test(expiryMonth) &&
            expiryYearPattern.test(expiryYear) &&
            validateExpirationDate(expiryMonth,expiryYear) &&
            vccPattern.test(vcc);
    }

    function validateExpirationDate(month, year) {
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth() + 1;
        const currentYear = currentDate.getFullYear();

        if (year < currentYear || (year == currentYear && month < currentMonth)) {
            return false;
        }

        return true;
    }
    
    
    
  
    
    
    
    proceedBtn.addEventListener('click',(e)=>{
        e.preventDefault();
        showPopup('loadspinner')
        form.submit();
    
        })
        
    
        
        
        
        checkoutBtn.addEventListener('click',(e)=>{
            e.preventDefault();
            
            let cardNumber = cardNumberInput.value.replace(/\s+/g, "");
            let expiryMonth = expiryMonthInput.value;
            let expiryYear = expiryYearInput.value;
            let vcc = vccInput.value;
            
            if (validateCardDetails(cardNumber, expiryMonth, expiryYear, vcc)) {
                showPopup('confirmationPopup')
            console.log('validation is success')
        } else {
            alert("Please enter valid card details.");
        }

        
    })
});



