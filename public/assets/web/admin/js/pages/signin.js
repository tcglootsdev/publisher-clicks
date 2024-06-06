window.Page = function () {
    const setFormValidation = function () {
        $("#pg-signin-form").validate({
            errorClass: 'text-danger',
            rules: {
                username: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                username: {
                    required: "Please enter your username",
                },
                password: {
                    required: "Please enter your password",
                    minlength: "The password must be at least 6 characters long.",
                }
            },
            submitHandler: function(element, event) {
                event.preventDefault();
                const loadingElem = Admin.showPageLoading();
                Web.ajax('post', '/web/auth/signin', Web.getFormData(element), (success, data, error) => {
                    Admin.hidePageLoading(loadingElem);
                    if (!success) {
                        Web.alert('warning', error);
                        return;
                    }
                    window.location.href = '/admin';
                });
            }
        });
    }

    return {
        init: function () {
            setFormValidation();
        }
    }
}();
