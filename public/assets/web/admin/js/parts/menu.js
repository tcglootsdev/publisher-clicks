(() => {
    if (window.PtMenu !== undefined) return;
    window.PtMenu = function () {
        const partId = 'pt-menu';

        const realId = '.' + partId;

        const setActive = (menuItem) => {
            $(realId + ' .' + partId + '-' + menuItem).addClass('show');
        }

        return {
            setActive
        }
    }();
})();
