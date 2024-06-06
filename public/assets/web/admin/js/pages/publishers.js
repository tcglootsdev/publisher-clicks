window.Page = (() => {
    const pageId = 'pg-publishers';

    let tablePublishers = null;

    const loadAndDisplayPublishers = () => {
        if (tablePublishers) {
            tablePublishers.ajax.reload();
            return;
        }
        tablePublishers = $("." + pageId + "-table").DataTable({
            columns: [
                {title: 'Created At', data: '_created_at'},
                {title: 'Username', data: '_username'},
                {title: 'Clicks', data: '_clicks'},
            ],
            ajax: {
                type: 'get',
                url: serverUrl + '/web/users',
                data: (data) => {
                    data.searchKey = 'role';
                    data.searchValue = 'publisher';
                    data.with = ['clicks'];
                },
                dataType: 'json',
                dataSrc: (response) => {
                    if (!response.status) {
                        Web.alert('error', response.error);
                        return [];
                    }
                    const {data: publishers} = response;
                    for (let i = 0; i < publishers.length; i++) {
                        publishers[i]._created_at = moment(publishers[i].created_at).format('YYYY/MM/DD HH:mm');
                        publishers[i]._username = '<a href="' + serverUrl + '/admin/publishers/' + publishers[i].id + '/clicks' + '">' + publishers[i].username + '</a>';
                        publishers[i]._clicks = publishers[i].clicks.length;
                    }
                    return publishers;
                }
            },
        });
    }

    return {
        init: () => {
            PtMenu.setActive('publishers');
            loadAndDisplayPublishers();
        }
    }
})();
