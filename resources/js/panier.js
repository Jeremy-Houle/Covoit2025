document.addEventListener('DOMContentLoaded', () => {
// const input = document.getElementById('dateExp');

// input.addEventListener('input', function(e) {
//     let value = input.value.replace(/\D/g, ''); 
//     if (value.length >= 3) {
//         value = value.slice(0,2) + '/' + value.slice(2,4); 
//     }
//     input.value = value;
// });

// const inputCarte = document.getElementById('numCarte');
// if(inputCarte){
//     inputCarte.addEventListener('input', function() {
//         let value = inputCarte.value.replace(/\D/g, '');
//         let formatted = '';
//         for (let i = 0; i < value.length; i++) {
//             if (i > 0 && i % 4 === 0) formatted += ' ';
//             formatted += value[i];
//         }
//         inputCarte.value = formatted;
//     });
// }

// const inputCVV = document.getElementById('cvv');

// inputCVV.addEventListener('input', function() {
//     this.value = this.value.replace(/\D/g, '');
// });

 const priceElement = document.getElementById('price');
    if (priceElement && priceElement.dataset && priceElement.dataset.paiements) {
        try {
            const paiements = JSON.parse(priceElement.dataset.paiements);
            let total = 0;
            paiements.forEach(paiement => {
                total += (Number(paiement.Prix) || 0) * (Number(paiement.NombrePlaces) || 0);
            });
            priceElement.innerText = 'Total : ' + total + ' $';
        } catch (e) {
            // ignore parse errors
        }
    }

    const validationPriceElement = document.getElementById('ValidationPrice');
    if (validationPriceElement && validationPriceElement.dataset && validationPriceElement.dataset.paiements) {
        try {
            const validationPaiements = JSON.parse(validationPriceElement.dataset.paiements);
            let validationTotal = 0;
            validationPaiements.forEach(paiement => {
                validationTotal += (Number(paiement.Prix) || 0) * (Number(paiement.NombrePlaces) || 0);
            });
            validationPriceElement.innerText = 'Valider le paiement de  : ' + validationTotal + ' $';
        } catch (e) {
            // ignore parse errors
        }
    }
    
});
