function confirmation(callback) {
    Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        reverseButtons: true,
    }).then((result) => {
        if (result.value) {
            callback();
        }
    })
}

function errorModal(title, message) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
    });
}