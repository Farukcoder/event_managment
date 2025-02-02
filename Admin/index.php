<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once "templete/head.php";
require_once "templete/header.php";
require_once "templete/leftmenu.php";

use App\classes\Event;

$event = new Event();

$totalEvent = $event->totalEvent();
$totalRegistered = $event->getTotalAttendeesByUser();

?>
<!-- MAIN -->
<div class="main">
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">
			<!-- OVERVIEW -->
			<div class="panel panel-headline">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-3">
							<div class="metric">
								<span class="icon"><i class="fa fa-download"></i></span>
								<p>
									<span class="number"><?= $totalEvent ?></span>
									<span class="title">Total Event</span>
								</p>
							</div>
						</div>
						<div class="col-md-3">
							<div class="metric">
								<span class="icon"><i class="fa fa-shopping-bag"></i></span>
								<p>
									<span class="number"><?= $totalRegistered ?></span>
									<span class="title">Total Registered</span>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END OVERVIEW -->
		</div>
	</div>
	<!-- END MAIN CONTENT -->
</div>
<!-- END MAIN -->
<?php
require_once "templete/foot.php";
require_once "templete/footer.php"
?>