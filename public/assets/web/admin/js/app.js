window.Admin = (() => {
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
            if (window.PtHeader) {
                PtHeader.init();
            }
            if (window.Page) {
                Page.init();
            }
        },
        showPageLoading,
        hidePageLoading,
    }
})();

$(document).ready(() => {
    Admin.init();
});
