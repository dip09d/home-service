<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <h1>
                    <?php echo $main_title; ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <?php echo $breadcrumb; ?>
            </div>
        </div>
    </section>

    <!-- Booking Details Content -->
    <section class="content">
        <div class="well box-header" style="margin-top: 20px;">

            <!-- First Row -->
            <h3 class="text-center"><?php echo $cus_title; ?></h3>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Name</label>
                    <p><?php echo $booking->member_name; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Email</label>
                    <p><?php echo $booking->member_email; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Phone</label>
                    <p><?php echo $booking->member_phone; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Duration</label>
                    <p><?php echo $booking->duration_hours; ?> hrs</p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Address</label>
                    <p><?php echo $booking->member_address_1; ?></p>
                    <p><?php echo $booking->member_address_2; ?></p>
                    <p><?php echo $booking->member_landmark; ?></p>
                    <p><?php echo $booking->member_address_type; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Latitude</label>
                    <p><?php echo $booking->member_lat; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Longitude</label>
                    <p><?php echo $booking->member_lng; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <p><?php echo $booking->category_name; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sub Category</label>
                    <p><?php echo $booking->subcategory_name; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Booking Date</label>
                    <p><?php echo date('d M,Y', strtotime($booking->booking_date)); ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Booking Time</label>
                    <p><?php echo $booking->booking_time; ?></p>
                </div>
            </div>

            <h3 class="text-center"><?php echo $wor_title; ?></h3>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Name</label>
                    <p><?php echo $booking->worker_name; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Email</label>
                    <p><?php echo $booking->worker_email; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Phone</label>
                    <p><?php echo $booking->worker_phone; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Alternate Phone</label>
                    <p><?php echo $booking->worker_alt_phone; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Whatsaap Number</label>
                    <p><?php echo $booking->worker_whatsapp; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Register Date</label>
                    <p><?php echo $booking->worker_register_date; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Address</label>
                    <p><?php echo $booking->worker_address; ?></p>
                    <p><?php echo $booking->worker_landmark; ?></p>
                    <p><?php echo $booking->worker_pincode; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Latitude</label>
                    <p><?php echo $booking->worker_lat; ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Langtitude</label>
                    <p><?php echo $booking->worker_lng; ?></p>
                </div>
            </div>

            <!-- Second Row -->
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <p>
                        <?php
                        switch ($booking->status) {
                            case 1:
                                echo '<span class="badge badge-warning">Pending</span>';
                                break;
                            case 2:
                                echo '<span class="badge badge-info">Accepted</span>';
                                break;
                            case 3:
                                echo '<span class="badge badge-primary">Progress</span>';
                                break;
                            case 4:
                                echo '<span class="badge badge-success">Completed</span>';
                                break;
                            case 5:
                                echo '<span class="badge badge-danger">Cancelled</span>';
                                break;
                            default:
                                echo '<span class="badge badge-secondary">Unknown</span>';
                        }
                        ?>
                    </p>
                </div>
            </div>

            <!-- Optional: Download Invoice -->
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-12">
                    <?php
                    $token = md5(date('Y-m-d') . '-ORGUP');
                    $invoice_url = SITE_URL . '/invoice/invoice_details/' . md5($booking->invoice_id) . '?auth=' . $token;
                    ?>
                    <a class="btn btn-primary" target="_blank" href="<?php echo $invoice_url; ?>&is_download=1">
                        Download Invoice
                    </a>
                </div>
            </div>

        </div>
    </section>

</div>
