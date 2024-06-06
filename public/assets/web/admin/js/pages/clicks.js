window.Page = (() => {
    const pageId = 'pg-clicks';

    let tableClicks = null;

    const loadAndDisplayClicks = () => {
        if (tableClicks) {
            tableClicks.ajax.reload();
            return;
        }
        tableClicks = $("." + pageId + "-table").DataTable({
            columns: [
                {title: 'Created At', data: '_created_at'},
                {title: 'Publisher', data: '_username'},
            ],
            ajax: {
                type: 'get',
                url: serverUrl + '/web/clicks',
                data: (data) => {
                    data.searchKey = 'user_id';
                    data.searchValue = Data.user_id;
                    data.with = ['publisher'];
                },
                dataType: 'json',
                dataSrc: (response) => {
                    if (!response.status) {
                        Web.alert('error', response.error);
                        return [];
                    }
                    const {data: clicks} = response;
                    for (let i = 0; i < clicks.length; i++) {
                        clicks[i]._created_at = moment(clicks[i].created_at).format('YYYY/MM/DD HH:mm');
                        clicks[i]._username = clicks[i].publisher.username;
                    }
                    return clicks;
                }
            },
        });
    }

    return {
        init: () => {
            PtMenu.setActive('publishers');
            loadAndDisplayClicks();
        }
    }
})();
