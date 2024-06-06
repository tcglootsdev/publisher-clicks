window.PtMenu = function () {
    const partId = 'pt-menu';

    const setActive = (item) => {
        $('.pt-menu-item' + '.' + partId + '-' + item).addClass('show');
        $('.' + partId + '-' + item + ' .menu-link').addClass('active');
        $(' .' + partId + '-' + item)
            .parent('.pt-menu-sub')
            .addClass('show')
            .parent('.pt-menu-item')
            .addClass('here hover show')
    }

    return {
        setActive
    }
}();
