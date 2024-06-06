window.PublisherClicksWebAdminPage = function () {
    const setFormValidation = function () {
        $("#pg-signin-frm").validate({
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
                const loadingElem = PublisherClicksWebAdmin.showPageLoading();
                PublisherClicksWeb.ajax('post', '/web/auth/signin', PublisherClicksWeb.getFormData(element), (success, data, error) => {
                    PublisherClicksWebAdmin.hidePageLoading(loadingElem);
                    if (!success) {
                        PublisherClicksWeb.alert('warning', error);
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
