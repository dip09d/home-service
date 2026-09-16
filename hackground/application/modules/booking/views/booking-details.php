<style>
.rating-wrapper {
    position: relative;
    display: inline-block;
    font-size: 24px;
    color: #ccc;
}

.rating-wrapper::before {
    content: "★★★★★";
}

.rating-fill {
    position: absolute;
    top: 0;
    left: 0;
    white-space: nowrap;
    overflow: hidden;
    color: #ffc107;
}

.rating-fill::before {
    content: "★★★★★";
}
</style>

<div class="content-wrapper">

    <section class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <h1><?php echo $main_title; ?></h1>
            </div>
            <div class="col-sm-6">
                <?php echo $breadcrumb; ?>
            </div>
        </div>
    </section>

    <section class="content">
        <?php
        if(empty($booking)){
            echo "<div class='alert alert-danger'>Booking details not found or failed to load.</div>";
            return;
        }
        ?>
        <div class="card-header border-bottom-0">

            <?php if($booking->status == 1) { ?>
                <div class="card-tools">
                    <button type="button" class="btn btn-site btn-sm" onclick="cancelBooking('<?php echo $booking->booking_id; ?>')">
                        <i class="icon-feather-minus"></i>
                        Cancel Booking
                    </button>
                </div>
            <?php } ?>
           
            <?php //if($booking->status < 3 ){ ?>
            <?php if($booking->status == 1 || $booking->status == 2 || $booking->status == 5) { ?>
                <div class="card-tools">
                    <button type="button" class="btn btn-site btn-sm" onclick="add()">
                        <i class="icon-feather-plus"></i>
                        Change/Assign Provider
                    </button>
                </div>
            <?php } ?>
          
        </div>
        <div class="well box-header" style="margin-top:20px;">

            <!-- BOOKING DETAILS -->
            <h3>Booking Details</h3>

            <div class="row">

                <div class="col-md-3">
                    <label>Booking ID</label>
                    <p>#<?php echo $booking->booking_id; ?></p>
                </div>

                <!-- <div class="col-md-3">
                    <label>Service</label>
                    <p><?php //echo $booking->category_name; ?></p>
                </div> -->

                <div class="col-md-3">
                    <label>Service</label>
                    <p><?php echo $booking->subcategory_name; ?></p>
                </div>

                <div class="col-md-3">
                    <label>Status</label>
                    <p>
                        <?php
                        $status_map = [
                            1 => ['Pending', 'warning'],
                            2 => ['Accepted', 'info'],
                            3 => ['Progress', 'primary'],
                            4 => ['Completed', 'success'],
                            5 => ['Cancelled', 'danger'],
                        ];

                        $st = $status_map[$booking->status] ?? ['Unknown', 'secondary'];

                        echo '<span class="badge badge-'.$st[1].'">'.$st[0].'</span>';
                        ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <label>Booking Date</label>
                    <p><?php echo date('d M Y', strtotime($booking->booking_date)).' '.date('h:i A', strtotime($booking->booking_time)); ?></p>
                </div>

                <div class="col-md-3">
                    <label>Booking Duration</label>
                    <p><?php echo $booking->duration_hours; ?> Hours</p>
                </div>

                <?php 
                if($booking_start_end_time) { 
                ?>
                    <div class="col-md-3">
                        <label>Start Time</label>
                        <p><?php echo date('d M Y', strtotime($booking_start_end_time->start_time)).' '.date('h:i A', strtotime($booking_start_end_time->start_time)); ?></p>
                    </div>

                    <div class="col-md-3">
                        <label>End Time</label>
                        <p>
                            <?php if($booking->status == 4) {
                                echo date('d M Y', strtotime($booking_start_end_time->end_time)).' '.date('h:i A', strtotime($booking_start_end_time->end_time));
                            } else if($booking->status == 3) { 
                                echo 'Running';
                            } ?>
                        </p>
                    </div>

                <?php } ?>

                <?php
                $first_hour_charge = isset($booking->price) ? $booking->price : 0;
                $next_hour_charge = isset($booking->next_hour_price) ? $booking->next_hour_price : 0;
                $late_night_charge = isset($booking->late_night_price) ? $booking->late_night_price : 0;
                if(isset($booking->payment_data) && $booking->payment_data) {
                    $payment_data = json_decode($booking->payment_data, true);
                    if(is_array($payment_data) && isset($payment_data['fees']) && is_array($payment_data['fees'])) {
                        $first_hour_charge = isset($payment_data['fees']['first_hour_charge']) ? $payment_data['fees']['first_hour_charge'] : $first_hour_charge;
                        $next_hour_charge = isset($payment_data['fees']['next_hour_price']) ? $payment_data['fees']['next_hour_price'] : $next_hour_charge;
                        $late_night_charge = isset($payment_data['fees']['late_night_charge']) ? $payment_data['fees']['late_night_charge'] : $late_night_charge;
                    }
                }
                ?>
                <!-- RATE -->
                <div class="col-md-3">
                    <label>First Hour Charge</label>
                    <p>₹ <?php echo number_format((float)$first_hour_charge, 2); ?></p>
                </div>
                <div class="col-md-3">
                    <label>Next Hour Charge</label>
                    <p>₹ <?php echo number_format((float)$next_hour_charge, 2); ?></p>
                </div>

                <!-- EXTRA PER MINUTE -->
                <div class="col-md-3">
                    <label>Late Night Charge (9PM)</label>
                    <p>
                        <?php
                        if($late_night_charge > 0) {
                            echo '₹ '.number_format((float)$late_night_charge, 2);
                        } else {
                            echo 'Not Applicable';
                        }
                        ?>
                    </p>
                </div>
                <?php if(!empty($reviews)) { ?>
                    <div class="col-md-3">
                        <label>Review</label>
                        <p>
                            <?php
                            if($reviews->average_review > 0) { ?>
                                <!-- // echo number_format($reviews->average_review); -->
                                <div class="star-rating" data-rating="<?php echo $reviews->average_review; ?>"></div>
                            <?php } else {
                                echo 'Not Applicable';
                            }
                            ?>
                        </p>
                    </div>
                <?php } ?>
               
                <!-- DOWNLOAD INVOICE -->
                <?php if($booking->status == 4) { 
                    if (!empty($booking->invoice_id)) { ?>

                    <div class="row" style="margin-top:30px;">
                        <div class="col-md-12 text-center">

                            <?php
                            $token = md5(date('Y-m-d').'-ORGUP');
                            $invoice_url = SITE_URL.'invoice/invoice_details/'.md5($booking->invoice_id).'?auth='.$token;
                            ?>

                            <a class="btn btn-success" target="_blank"
                            href="<?php echo $invoice_url; ?>&is_download=1">
                                Download Invoice
                            </a>
                            
                            <?php if($booking->invoice_status == 1) {
                                echo '<span class="badge badge-success">Paid</span>';
                            } else {
                                echo '<span class="badge badge-warning">Unpaid</span>';
                            } ?>

                        </div>
                    </div>

                <?php } else { ?>
                    
                    <div class="row" style="margin-top:30px;">
                        <div class="col-md-12 text-center">
                            <span class="badge badge-secondary">Invoice not generated for this booking</span>
                        </div>
                    </div>
                    
                <?php } 
                } ?>

            </div>

            <!-- CUSTOMER & WORKER SECTION -->
            <div class="row" style="margin-top:30px;">

                <!-- CUSTOMER DETAILS -->
                <div class="<?php echo ($booking->status == 4) ? 'col-md-6' : 'col-md-12'; ?>">
                    <h3>Customer Details</h3>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Name</label>
                            <p><?php echo $booking->member_name; ?></p>
                        </div>

                        <?php if(!empty($booking->member_email)) { ?>
                            <div class="col-md-6">
                                <label>Email</label>
                                <p><?php echo $booking->member_email; ?></p>
                            </div>
                        <?php } ?>

                        <div class="col-md-6">
                            <label>Phone</label>
                            <p><?php echo $booking->member_phone; ?></p>
                        </div>

                        <div class="col-md-12">
                            <label>Address</label>
                            <p>
                                <?php echo $booking->member_address_1; ?><br>
                                <?php echo $booking->member_address_2; ?><br>
                                <?php echo $booking->member_landmark; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <?php if($booking->status == 4) { ?>
                <!-- PROVIDER DETAILS -->
                <div class="col-md-6">
                    <h3>Provider Details</h3>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Name</label>
                            <p><?php echo $booking->worker_name ?: 'Not Assigned'; ?></p>
                        </div>

                        <?php if(!empty($booking->worker_email)) { ?>
                            <div class="col-md-6">
                                <label>Email</label>
                                <p><?php echo $booking->worker_email; ?></p>
                            </div>
                        <?php } ?>

                        <div class="col-md-6">
                            <label>Phone</label>
                            <p><?php echo $booking->worker_phone; ?></p>
                        </div>

                        <?php if(!empty($booking->worker_whatsapp)) { ?>
                            <div class="col-md-6">
                                <label>WhatsApp</label>
                                <p><?php echo $booking->worker_whatsapp; ?></p>
                            </div>
                        <?php } ?>

                        <div class="col-md-12">
                            <label>Address</label>
                            <p>
                                <?php echo $booking->worker_address; ?><br>
                                <?php echo $booking->worker_landmark; ?><br>
                                <?php echo $booking->worker_pincode; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php } ?>

            </div>

        </div>
    </section>

</div>
<div class="modal fade" id="ajaxModal">
    <div class="modal-dialog">
        <div class="modal-content">
         
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.star-rating').each(function() {
        var rating = parseFloat($(this).data('rating')) || 0;
        var percentage = (rating / 5) * 100;
        $(this).css('--rating-percent', percentage + '%');
        $(this).find('::after');
        $(this)[0].style.setProperty('--width', percentage + '%');
        $(this).css('position', 'relative');
        $(this).find('::after');
        $(this).append('<style>.star-rating::after{width:' + percentage + '%;}</style>');
    });
});

function add() {
    var url = '<?php echo base_url($curr_controller . 'load_ajax_page?booking_id=' . $booking->booking_id); ?>';
    load_ajax_modal(url);
}

function cancelBooking(booking_id) {
    if (confirm('Are you sure you want to cancel this booking?')) {

        $.ajax({
            url: "<?php echo base_url('booking/cancel_booking'); ?>",
            type: "POST",
            data: { booking_id: booking_id },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Something went wrong!');
            }
        });

    }
}
</script>