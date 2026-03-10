function mask(input, type) {
    let value = input.value.toUpperCase();

    switch (type) {
        case 'CPF':
            value = value.replaceAll(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            break;
        default:
            break;
    }

    input.value = value;
}