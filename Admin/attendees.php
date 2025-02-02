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
            <h3 class="page-title">Event Attendees</h3>

            <!-- CSV Download Button -->
            <div style="margin-bottom: 15px;">
                <!-- Using PHP to pass the event_id from GET -->
                <a href="export_csv.php?event_id=<?php echo isset($_GET['id']) ? (int) $_GET['id'] : 0; ?>" class="btn btn-primary">
                    Download CSV
                </a>
            </div>

            <div class="row">
                <!-- OVERVIEW -->
                <div class="panel panel-headline">
                    <div class="panel-body">
                        <table class="table table-bordered" id="attendees_table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Data loaded via AJAX -->
                            </tbody>
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
        $('#attendees_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "fetch_attendees.php",
                "type": "GET",
                "data": function(d) {
                    d.event_id = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>"; // Pass event ID
                }
            },
            "columns": [
                { "data": null, "render": function (data, type, row, meta) {
                        return meta.row + 1; // Auto increment SL column
                    }},
                { "data": "name" },
                { "data": "email" },
                { "data": "phone" }
            ]
        });
    });
</script>
