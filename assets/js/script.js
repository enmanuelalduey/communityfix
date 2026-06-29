const inputs = document.querySelectorAll('input');
inputs.forEach(input => {
    input.addEventListener('blur', () => {
        if (input.value.length > 0) {
            input.classList.add('border-green-400');
            input.classList.remove('border-gray-200');
        } else {
            input.classList.remove('border-green-400');
            input.classList.add('border-gray-200');
        }
    });
});