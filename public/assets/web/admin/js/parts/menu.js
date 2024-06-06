window.PtMenu = function () {
    const partId = 'pt-menu';

    const setActive = (menuItem) => {
        $('.' + partId + '-' + menuItem).addClass('show');
    }

    return {
        setActive
    }
}();
