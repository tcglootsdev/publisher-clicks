const PublisherClicksWebAdminPartHeader = function () {
    const partClass = '.pt-header';
    const signout = (elemId) => {
        PublisherClicksWeb.ajax('post', '/web/auth/signout', { _token: csrf_token }, (success, data, error) => {
            if (success) {
                window.location.href = '/admin';
            } else {
                PublisherClicksWeb.alert(error);
            }
        });
    }

    return {
        signout
    }
}();
