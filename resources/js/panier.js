
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
