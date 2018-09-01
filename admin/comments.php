<?php
include("header_php.php");
global $mysqli;
$response = array();


if (isset($_POST['delete_book'])) {
    $id = $_POST['delete_book_id'];


    $query = $mysqli->prepare("DELETE FROM `comments` WHERE comment_id = ?");
    $query->bind_param("i", $id);
    $res = $query->execute();
    // die($res);
    if ($res == false) {
        $response['message'] = "Selected Comment Is Not Deleted";
        $response['type'] = -1;
    } else {
        $response['message'] = "Selected Comment Is Successfully Deleted";
        $response['type'] = 1;
    }
    echo json_encode($response);
    die;
}

if (isset($_POST['approve'])) {
    $id = $_POST['approve'];
    $status = "Approved";

    $query = $mysqli->prepare("UPDATE `comments` SET status = ? WHERE comment_id = ?");
    $query->bind_param("si", $status, $id);
    $res = $query->execute();

    if ($res == false) {
        $response['message'] = error_alert("Status Is Not Updated");
        $response['type'] = -1;
    } else {
        $response['message'] = success_alert("Status Is Successfully Updated");
        $response['type'] = 1;
    }
    echo json_encode($response);
    die;
}
if (isset($_POST['reason_id'])) {
    $id = $_POST['reason_id'];
    $status = "Unapproved";

    $query = $mysqli->prepare("UPDATE `comments` SET status = ? WHERE comment_id = ?");
    $query->bind_param("si", $status, $id);
    $res = $query->execute();

    if ($res == false) {
        $response['message'] = error_alert("Status Is Not Updated");
        $response['type'] = -1;
    } else {
        $response['message'] = success_alert("Status Is Successfully Updated");
        $response['type'] = 1;
    }
    echo json_encode($response);
    die;
}



$page_title = "Comments";
include("header.php");
include("left_sidebar.php");
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <h1>

            <?php echo $page_title; ?> &nbsp;&nbsp;

        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo ADMIN_BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> <?php echo $page_title; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <a href="javascript:;" style="display: inline-block;" class="btn btn-warning print_p"><i class="fa fa-print"></i>&nbsp;&nbsp; Print</a>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="branches_tbl" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Comments</th>
                                    <th>Blog Title</th>
                                    <th>Status</th>
                                    <th>Date</th>


                                </tr>
                            </thead>
                            <span id="status-msg"></span>
                            <tbody>
                                <?php echo getAllComments(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Comments</th>
                                    <th>Blog Title</th>
                                    <th>Status</th>
                                    <th>Date</th>

                                </tr>
                            </tfoot>
                        </table>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->


        <div class="modal modal-danger delete_book" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Delete Comment</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the <strong id="book_name">Comment</strong> ?</p>
                        <input type="hidden" id="delete_book_id" value="" />
                    </div>
                    <div class="modal-footer">
                        <strong id="msg"></strong>
                        <i style="display:none" class="fa fa-refresh fa-spin fa-spin-book"></i>&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-outline btn-disable-book delete_book_btn_yes" >Yes</button>
                        <button type="button" class="btn btn-outline btn-disable-book" data-dismiss="modal">No</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->  


        
        
        
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->




<?php include("footer_wrapper.php"); ?>
<?php include("footer_js.php"); ?>

<script>





    $(document).ready(function () {
        $("#branches_tbl").DataTable({
            "autoWidth": true,
            
        });

        $('.comment_li').addClass('active');


        $('.del_btn').click(function () {
            var br_id = $(this).attr('data');
            $('#msg').html("");
            $('#delete_book_id').val(br_id);
            $(".delete_book").show();
        });

        $('.delete_book_btn_yes').click(function () {
            $('.fa-spin-book').show();
            $(".btn-disable-book").attr("disabled", "disabled");
            $.ajax({
                type: "POST",
                url: "comments.php",
                data: {delete_book: true, delete_book_id: $("#delete_book_id").val()},
                success: function (response) {
                    //      alert(response);
                    var data = $.parseJSON(response);
                    if (data.type == -1) {
                        $('.fa-spin-book').hide();
                        $('#msg').show();
                        $('#msg').html(data.message);
                        $(".btn-disable-book").removeAttr("disabled");
                        $(".delete_book").modal('show');
                        //	 loadModelRates();
                        // location.reload();
                    } else {
                        $('.fa-spin-book').hide();
                        $('#msg').show();
                        $('#msg').html(data.message);
                        $(".btn-disable-book").removeAttr("disabled");
                        $(".delete_book").modal('hide');
                        location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert("error");
                }
            });

        });
        $(".status-report").click(function(){
            $(".image_modal").show();
            $(".fa-spin-edit-top-logo").show();
            var id = $(this).attr("data");
            $.ajax({
                type: "POST",
                url: "bookings.php",
                data: {report: id},
                success: function(response){
                    var data = $.parseJSON(response);
                    $(".report").html(data.b_cancelled_reason);
                    $(".fa-spin-edit-top-logo").hide();
                }
            });
        });



        $(".approve_btn").click(function () {
            var id = $(this).attr("data");
            var btn = $(this);
            $.ajax({
                type: "POST",
                url: "comments.php",
                data: {approve: id},
                success: function (response) {
                    //   alert(response);
                    var data = $.parseJSON(response);
                    if (data.type === -1) {
                        $('#status-msg').show();
                        $('#status-msg').html(data.message);

                    } else {
                        $('#status-msg').show();
                        $('#status-msg').html(data.message);
                        location.reload();
                    }
                }
            });
        });

        $(".cancel_btn").click(function () {
            $(".reason_modal").show();

            var id = $(this).attr("data");
            $("#reason_id").val(id);
            $.ajax({
                type: "POST",
                url: "comments.php",
                data: {reason_id: id},
                success: function (response) {
                    //   alert(response);
                    var data = $.parseJSON(response);
                    if (data.type === -1) {
                        $(".btn-disable-reas").removeAttr("disabled");
                        $(".fa-spin-add-reas").hide();
                        $('#msg_change_password').show();
                        $('#msg_change_password').html(data.message);

                    } else {
                        $('#msg_change_password').show();
                        $('#msg_change_password').html(data.message);
                        location.reload();
                    }
                }
            });

        });

        $("#reason_form").submit(function () {
            $(".btn-disable-reas").attr("disabled", true);
            $(".fa-spin-add-reas").show();
            $.ajax({
                type: "POST",
                url: "bookings.php",
                data: $("#reason_form").serialize(),
                success: function (response) {
                    //   alert(response);
                    var data = $.parseJSON(response);
                    if (data.type === -1) {
                        $(".btn-disable-reas").removeAttr("disabled");
                        $(".fa-spin-add-reas").hide();
                        $('#msg_change_password').show();
                        $('#msg_change_password').html(data.message);

                    } else {
                        $('#msg_change_password').show();
                        $('#msg_change_password').html(data.message);
                        location.reload();
                    }
                }
            });
        });


        $("button[data-dismiss=modal]").click(function () {
            $('.fa-spin-book').hide();
            $('#msg').hide();
            $(".btn-disable-book").removeAttr("disabled");
            $(".delete_book").hide();
            $(".reason_modal").hide();
            $(".btn-disable-reas").removeAttr("disabled");
            $(".fa-spin-add-reas").hide();
            $('#msg_change_password').hide();
            $(".image_modal").hide();
        });





    });
</script>

<?php include("footer.php"); ?>


