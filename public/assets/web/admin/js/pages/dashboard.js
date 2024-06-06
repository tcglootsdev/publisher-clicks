window.Page = (() => {
    const pageId = "pg-dashboard";

    const loadAndDisplayStatistic = () => {
        Web.ajax(
            'get',
            '/web/clicks/statistic',
            (success, data, error) => {
                if (!success) {
                    Admin.alert('warning', error);
                    return;
                }
                $('.' + pageId + '-clicks').html(Number(data.number.clicks).toLocaleString());
            });
    }

    return {
        init: function () {
            PtMenu.setActive('dashboard');
            loadAndDisplayStatistic();
        }
    }
})();
