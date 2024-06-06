const TCGLootsWebAdmin = (() => {
    const startFormSubmit = (formElem) => {
        let submitBtn = $(formElem).find('button[type="submit"]');
        submitBtn = {
            labelElem: submitBtn.children('span.indicator-label'),
            progressElem: submitBtn.children('span.indicator-progress')
        }
        submitBtn.labelElem.hide();
        submitBtn.progressElem.show();
        return { submitBtn };
    }

    const endFormSubmit = ({ submitBtn }) => {
        // Because submitBtn is gotten in startFormSubmit function, it is unnecessary to get again
        if (!TCGLootsWeb.isEmpty(submitBtn)) {
            submitBtn.progressElem.hide();
            submitBtn.labelElem.show();
        }
    }

    const showPageLoading = () => {
        const loadingElem = document.createElement("div");
        document.body.prepend(loadingElem);
        loadingElem.classList.add("page-loader");
        loadingElem.classList.add("flex-column");
        loadingElem.classList.add("bg-dark");
        loadingElem.classList.add("bg-opacity-25");
        loadingElem.innerHTML = '<span class="spinner-border text-primary" role="status">';
        loadingElem.innerHTML += '<span className="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>';
        KTApp.showPageLoading();
        return loadingElem;
    };

    const hidePageLoading = (loadingElem) => {
        KTApp.hidePageLoading();
        loadingElem.remove();
    }

    return {
        init: () => {
            if (window.TCGLootsWebAdminPage) {
                TCGLootsWebAdminPage.init();
            }
        },
        startFormSubmit,
        endFormSubmit,
        showPageLoading,
        hidePageLoading,
    }
})();

$(document).ready(() => {
    TCGLootsWebAdmin.init();
});
