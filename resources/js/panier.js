document.addEventListener('DOMContentLoaded', () => {
const input = document.getElementById('dateExp');

input.addEventListener('input', function(e) {
    let value = input.value.replace(/\D/g, ''); 
    if (value.length >= 3) {
        value = value.slice(0,2) + '/' + value.slice(2,4); 
    }
    input.value = value;
});

const inputCarte = document.getElementById('numCarte');
if(inputCarte){
    inputCarte.addEventListener('input', function() {
        let value = inputCarte.value.replace(/\D/g, '');
        let formatted = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) formatted += ' ';
            formatted += value[i];
        }
        inputCarte.value = formatted;
    });
}

const inputCVV = document.getElementById('cvv');

inputCVV.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
});

 const priceElement = document.getElementById('price');
    const paiements = JSON.parse(priceElement.dataset.paiements);

    let total = 0;
    paiements.forEach(paiement => {
        total += paiement.Prix * paiement.NombrePlaces;
    });

    priceElement.innerText = 'Total : ' + total + ' $';


    // const validationPriceElement = document.getElementById('ValidationPrice');
    // const Validationpaiements = JSON.parse(validationPriceElement.dataset.paiements);

    // let Validationtotal = 0;
    // paiements.forEach(paiement => {
    //     total += paiement.Prix * paiement.NombrePlaces;
    // });

    // validationPriceElement.innerText = 'confirmer le paiement de ' + Validationtotal + ' $';
    

    
});
