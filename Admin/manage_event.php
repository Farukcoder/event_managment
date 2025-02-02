<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once "templete/head.php";
require_once "templete/header.php";
require_once "templete/leftmenu.php";
?>
<!-- MAIN -->
<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <h3 class="page-title">Event List</h3>
            <div class="row">
                <!-- OVERVIEW -->
                <div class="panel panel-headline">
                    <div class="panel-body">
                        <table class="table table-bordered" id="event_table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Photo</th>
                                <th>Event Date</th>
                                <th>Total Members</th>
                                <th>Registered Members</th>
                                <th>Status</th>
                                <th style="width: 250px;">Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- END OVERVIEW -->
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN -->
<?php
require_once "templete/foot.php";
require_once "templete/footer.php";
?>

<script>
    $(document).ready(function() {
        $('#event_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "event/fetch_events.php",
                "type": "GET",
                "dataSrc": function (json) {
                    console.log(json); // Debugging: Check the response in the console
                    return json.data;
                },
                "error": function(xhr, error, thrown) {
                    console.log("AJAX Error:", xhr.responseText); // Debugging
                }
            },
            "columns": [
                { "data": null, "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return meta.row + 1; // Auto increment SL column
                    }},
                { "data": "title" },
                { "data": "photo", "orderable": false, "render": function (data) {
                        return `<img src="assets/img/event/${data}" alt="Event Photo" height="50" width="50">`;
                    }},
                { "data": "event_date", "render": function (data) {
                        let dateObj = new Date(data);
                        let year = dateObj.getFullYear();
                        let month = ("0" + (dateObj.getMonth() + 1)).slice(-2); // Ensure two digits
                        let day = ("0" + dateObj.getDate()).slice(-2); // Ensure two digits
                        return `${year}-${month}-${day}`; // Format YYYY-MM-DD
                    }},
                { "data": "total_members" },
                { "data": "registered_members" },
                { "data": "status", "render": function (data) {
                        return data == 1
                            ? '<span class="badge bg-success text-white">Active</span>'
                            : '<span class="badge bg-danger text-white">Inactive</span>';
                    }},
                { "data": "id", "orderable": false, "render": function (data, type, row) {
                        let statusButton = row.status == 1
                            ? `<a class="btn btn-danger btn-sm status-toggle" href="status.php?id=${data}&events=events&inactive=inactive" title="Deactivate">
                            <i class="fa fa-toggle-off"></i>
                        </a>`
                            : `<a class="btn btn-success btn-sm status-toggle" href="status.php?id=${data}&events=events&active=active" title="Activate">
                            <i class="fa fa-toggle-on"></i>
                        </a>`;

                        return `
                    <a href="attendees.php?id=${data}" class="btn btn-success btn-sm" title="View">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="edit_event.php?id=${data}" class="btn btn-info btn-sm" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    ${statusButton}
                    <a href="delete.php?id=${data}&events=events&filename=${row.photo}" class="btn btn-danger btn-sm delete-confirm" title="Delete">
                        <i class="fa fa-trash"></i>
                    </a>`;
                    }}
            ],
            "order": [[1, "asc"]] // Default order by Title
        });
    });

</script>
