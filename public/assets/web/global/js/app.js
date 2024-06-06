window.Web = (() => {
    const isEmpty = (value) => {
        if (
            value === undefined ||
            value === null ||
            typeof value === 'string' && value.length === 0 ||
            typeof value === 'object' && Object.keys(value) === 0
        ) {
            return true;
        }
        return false;
    }

    const ajax = (type, url, data, fileUpload, callback) => {
        if (typeof data === 'function') {
            callback = data;
            data = null;
        }

        if (typeof fileUpload === 'function') {
            callback = fileUpload;
            fileUpload = false;
        }

        if (
            type !== 'GET' &&
            type !== 'get' &&
            type !== 'POST' &&
            type !== 'post' &&
            type !== 'DELETE' &&
            type !== 'delete'
        ) {
            callback(false, null, 'Invalid ajax option.');
        }

        $.ajax({
            type: type,
            url: url,
            data: data,
            dataType: 'json',
            // async: false,
            processData: fileUpload ? false : true,
            contentType: fileUpload ? false : 'application/x-www-form-urlencoded',
            success: function (response) {
                console.log()
                if (typeof response === 'object') {
                    if (Number(response.status) === 1) {
                        callback(true, response.data);
                    } else {
                        callback(false, null, response.error);
                    }
                } else {
                    callback(false, null, 'Invalid Response.');
                }
            },
            error: function (err) {
                callback(false, null, 'Ajax error occured.');
            }
        });
    };

    const alert = (type, text, callback) => {
        if (
            type !== 'success' &&
            type !== 'error' &&
            type !== 'warning'
            && type !== 'info'
            && type !== 'question'
        ) {
            text = type;
            type = 'info';
        }

        Swal.fire({
            text,
            icon: type,
            confirmButtonText: type === 'question' ? 'Yes' : 'Ok',
            cancelButtonText: 'No',
            showCancelButton: type === 'question'
        }).then((eventStatus) => {
            if (eventStatus.isConfirmed && typeof callback === 'function') {
                callback();
            }
        });
    };

    const getFormData = (formElem) => {
        const formData = {};
        const serializedData = $(formElem).serializeArray();
        serializedData.forEach((dataObj) => {
            formData[dataObj.name] = dataObj.value;
        });
        return formData;
    }

    const ucfirst = (string) => {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

    return {
        isEmpty,
        ajax,
        getFormData,
        alert,
        ucfirst,
    }
})();
