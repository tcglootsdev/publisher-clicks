const TCGLootsWebAdminPartHeader = function () {
    const partClass = '.pt-header';
    const signout = (elemId) => {
        TCGLootsWeb.ajax('post', '/web/auth/signout', { _token: csrf_token }, (success, data, error) => {
            if (success) {
                window.location.href = '/admin';
            } else {
                TCGLootsWeb.alert(error);
            }
        });
    }

    return {
        signout
    }
}();
