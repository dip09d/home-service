<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-sm-6 col-12">
                <h1>
                    <?php echo $main_title ? $main_title : ''; ?>
                    <small><?php echo $second_title ? $second_title : ''; ?></small>
                </h1>
            </div>
            <div class="col-sm-6 col-12">
                <?php echo $breadcrumb ? $breadcrumb : ''; ?>
            </div>
        </div>
    </section>
	<?php $this->layout->load_filter(); ?>

    <section class="content">
        <!-- Default box -->
        <div class="card">

            <div class="card-header border-bottom-0">
                <h3 class="card-title">
                    <?php echo $title ? $title : ''; ?>
                </h3>
				<!-- <div class="card-tools">
				<a href="<?php echo base_url('booking/export_csv?'. http_build_query(array_merge($_GET, ['export' => 1])))?>"
				class="btn btn-success btn-sm mr-2">
					<i class="icon-feather-download"></i>
					Export CSV
				</a>

				</div> -->
            </div>


            <div class="card-body table-responsive p-0" id="main_table">
    <table class="table table-hover">
        <tbody>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:20%">Customer Name</th>
                <th style="width:20%">Provider Name</th>
                <!-- <th style="width:20%">Category Name</th> -->
                <th style="width:20%">Service</th>
                <th style="width:20%">Preference</th>
                <th style="width:10%">Booking Date</th>
                <th style="width:10%">Status</th>
                <th style="width:15%; text-align: center;">Cancelled By</th>
                <th style="width:10%">Action</th>
            </tr>

            <?php if (!empty($bookings)) { ?>
                <?php foreach ($bookings as $row) { ?>
                    <tr>
                        <td><?php echo $row['booking_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['worker_name']); ?></td>
                        <!-- <td><?php //echo htmlspecialchars($row['category_name']); ?></td> -->
                        <td><?php echo htmlspecialchars($row['subcategory_name']); ?></td>
                        <?php  
                            if($row['provider_gender'] == 'any' || $row['provider_gender'] == ''){
                                $pro_gender = 'Any';
                            }else{
                                if($row['provider_gender'] == 'F'){
                                    $pro_gender = 'Female';
                                }else{
                                    $pro_gender = 'Male';
                                }
                            }
                            
                        ?>
                        <td>
                            <p>Religion - <?php echo (!empty($row['provider_religion']) && $row['provider_religion'] != 0) ? htmlspecialchars($row['provider_religion_name']) : 'Any'; ?></p>
                            <p>Gender - <?php echo $pro_gender; ?></p>
                        </td>
                        <td><?php echo $row['booking_date'].'  '.date("h:i A", strtotime($row['booking_time'])); ?></td>
                        <td>
                            <?php
                                switch ($row['status']) {
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
                        </td>
                        <td style="text-align: center;">
                            <?php if($row['status'] == 5){
                                    if($row['cancelled_by'] == 'P'){
                                        echo 'Cancelled By Provider';
                                    }else if($row['cancelled_by'] == 'A'){
                                        echo 'Cancelled By Admin';
                                    }else{
                                        echo 'Cancelled By Customer';
                                    }
                            } else{
                                echo '--';
                            } ?>
                        </td>
                        <td>
                            <a href="<?php echo base_url('booking/getDetails/'.$row['booking_id']); ?>">
                                <i class="icon-feather-eye text-primary fa-lg"></i>
                            </a>

                            <i class="icon-feather-trash text-danger fa-lg delete-btn" title="Delete" data-id="<?php echo $row['booking_id']; ?>"></i>
                        
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="8" align="center">No Records Found</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
        </div>
		<nav>
		<ul class="pagination justify-content-center">
<?php echo $links;?>
</ul>
</nav>
    </section>

</div>
<script src="<?php echo ADMIN_PLUGINS;?>moment/moment.js"></script>
<script src="<?php echo ADMIN_PLUGINS;?>daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_PLUGINS;?>daterangepicker/daterangepicker.css">
<script>
<?php
    $daterange = $this->input->get('daterange');

    if (!empty($daterange)) {
        $dates = explode(' - ', $daterange);

        $start_date = $dates[0];
        $end_date   = $dates[1];
    } else {
        $start_date = date('Y-01-01');
        $end_date   = date('Y-m-d');
    }
?>
$('.datepicker').daterangepicker({
    "startDate": "<?php echo $start_date;?>",
	"endDate": "<?php echo $end_date;?>",
	locale: {
		format: 'YYYY-MM-DD'
	}
	
});
</script>
<script>
$(document).on('click', '.delete-btn', function () {

    var id = $(this).data('id');
    var row = $(this).closest('tr');

    if (confirm('Are you sure you want to delete this record?')) {

        $.ajax({
            url: "<?php echo base_url('booking/destroy'); ?>",
            type: "POST",
            data: { id: id },
            dataType: "json",
            success: function (response) {
                if (response.success) { // optional
                    row.fadeOut(); // remove row from table
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Something went wrong!');
            }
        });

    }
});
</script>

