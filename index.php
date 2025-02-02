<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once 'header.php';

use App\classes\Event;

$event = new Event();

$activeEvent =  $event->all_active_event();
?>

<style>
    .card:hover .card-img-top {
        transform: scale(1.05);
        filter: brightness(1);
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .badge {
        font-size: 0.9rem;
    }

    .btn-outline-primary {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
    }
</style>

<!-- Page Content -->
<div class="container py-5">
    <h1 class="text-center mb-5 text-primary font-weight-bold">Upcoming Events</h1>

    <div class="row">
        <?php
        $count = 0;
        foreach ($activeEvent as $value) {
        ?>
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card shadow-sm border-0 w-100" style="border-radius: 15px; overflow: hidden;">
                    <!-- Event Image -->
                    <img class="card-img-top" src="Admin/assets/img/event/<?= $value['photo'] ?>" alt="Event Image"
                        style="height: 200px; object-fit: cover; filter: brightness(0.9); transition: transform 0.3s ease;">
                    
                    <div class="card-body d-flex flex-column" style="min-height: 250px;">
                        <h5 class="card-title text-dark font-weight-bold"><?= htmlspecialchars($value['title']) ?></h5>

                        <p class="card-text text-muted flex-grow-1">
                            <?= nl2br(htmlspecialchars(substr($value['content'], 0, 120))) ?>...
                        </p>

                        <!-- Event Details -->
                        <div class="d-flex justify-content-between mt-3">
                            <span class="badge badge-success p-2">
                                <i class="fas fa-users"></i> <?= $value['total_members'] ?> Members
                            </span>
                            <span class="badge badge-warning p-2">
                                <i class="fas fa-check-circle"></i> <?= $value['registered_members'] ?> Registered
                            </span>
                        </div>

                        <span class="badge badge-primary p-2 mt-2">
                          <i class="fas fa-calendar-alt"></i> <?= date("F d, Y", strtotime($value['event_date'])) ?>
                          </span>
                        <!-- Read More Button -->
                        <a href="event.php?id=<?= $value['id'] ?>" class="btn btn-outline-primary btn-block mt-4">
                            Event Booking &rarr;
                        </a>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-light text-muted text-center">
                        <small>
                            <i class="fas fa-clock"></i> Posted on <?= date("F d, Y", strtotime($value['created_time'])) ?>
                            by <a href="#" class="text-primary font-weight-bold"><?= htmlspecialchars($value['user_name']) ?></a>
                        </small>
                    </div>
                </div>
            </div>

        <?php
            $count++;
            if ($count % 3 == 0) {
                echo '</div><div class="row">'; // Close row after every 3 cards and start a new row
            }
        }
        ?>
    </div> <!-- Closing the last row -->
</div>

</div>
<!-- /.container -->
<?php
require_once 'footer.php';
?>