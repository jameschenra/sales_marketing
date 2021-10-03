function dateFormat(date, format='d/m/y') {
    // const year = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date);
    // const month = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(date);
    // const day = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date);

    const year = date.getFullYear();
    const month = ('0' + (date.getMonth() + 1)).slice(-2);
    const day = ('0' + date.getDate()).slice(-2);

    let formattedString = '';
    switch (format) {
        case 'd/m/y':
            formattedString = day + '/' + month + '/' + year; 
            break;
        case 'y/m/d':
            formattedString = year + '/' + month + '/' + day; 
            break;
        case 'm/d/y':
            formattedString = month + '/' + day + '/' + year; 
            break;
        default:
            formattedString = day + '/' + month + '/' + year; 
            break;
    }
    
    return formattedString;
}