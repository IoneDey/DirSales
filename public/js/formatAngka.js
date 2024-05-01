// function formatAngka(event) {
//     let value = event.target.value;
//     value = value.replace(/\D/g, '');

//     if (value.length > 0) {
//         // let numberValue = parseInt(value);
//         let numberValue = parseFloat(value);

//         if (!isNaN(numberValue)) {
//             let formatter = new Intl.NumberFormat('id-ID', {
//                 style: 'decimal',
//                 minimumFractionDigits: 0,
//                 maximumFractionDigits: 2
//             });
//             value = formatter.format(numberValue);
//         }
//     }
//     event.target.value = value;
// }

function formatAngka(event) {
    let value = event.target.value;

    // Menghapus semua karakter selain digit, koma, dan titik
    value = value.replace(/[^\d,.]/g, '');

    // Menghapus semua koma kecuali yang terakhir
    let indexOfLastComma = value.lastIndexOf(',');
    if (indexOfLastComma !== -1) {
        value = value.replace(/,/g, (match, offset) => offset !== indexOfLastComma ? '' : match);
    }

    if (value.length > 0) {
        // Menghapus titik desimal yang tidak dibutuhkan
        value = value.replace(/\./g, '');

        // Memformat nilai input sesuai dengan format rupiah
        let parts = value.split(',');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        value = parts.join(',');
    }

    event.target.value = value;
}

// Mendefinisikan fungsi formatAngka di dalam objek window
window.formatAngka = formatAngka;

// Menangkap perubahan nilai input
document.addEventListener('livewire:load', function () {
    Livewire.hook('message.processed', (message, component) => {
        if (message.updateQueue.hasOwnProperty('hargajual')) {
            let inputElement = document.getElementById('hargajual');
            inputElement.value = formatAngka(inputElement.value);
        }
    });
});
