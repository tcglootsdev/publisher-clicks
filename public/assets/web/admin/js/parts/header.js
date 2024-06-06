window.PtHeader = function () {
    const partId = 'pt-header';

    const setClickEvent = () => {
        $('.' + partId + '-signout').click(() => {
            Web.ajax('post', serverUrl + '/web/auth/signout', { _token: csrf_token }, (success, data, error) => {
                if (success) {
                    window.location.href = '/admin';
                } else {
                    Web.alert(error);
                }
            });
        });
    }

    return {
        init: () => {
            setClickEvent();
        },
    }
}();
