<?php
include("header_php.php");
global $mysqli;


if (isset($_POST['remove_admin'])){
    $user_id = $_POST['user_id'];
    $query = $mysqli->prepare("UPDATE `users` SET is_admin = 0 where id = ?");
    $query->bind_param("i", $user_id);
    $res = $query->execute();
    if ($res == false) {
        $response['message'] = "Failed removing user Admin Access!";
        $response['type'] = -1;
    } else {
        $response['message'] = "Admin Access Removed of user!";
        $response['type'] = 1;
    }
    echo json_encode($response);
}

if (isset($_POST['make_admin'])){
    $user_id = $_POST['user_id'];
    
    $query = $mysqli->prepare("UPDATE `users` SET is_admin = 1 where id = ?");
    $query->bind_param("i",$user_id);
    $res = $query->execute();
    if ($res == false) {
        $response['message'] = "Failed making user Admin!";
        $response['type'] = -1;
    } else {
        $response['message'] = "User Upgraded to Admin";
        $response['type'] = 1;
    }
    echo json_encode($response);
}

if (isset($_POST['delete_user'])) {
    $user_id = $_POST['delete_br_id'];

    $query = $mysqli->prepare("DELETE FROM `users` WHERE id = ?");
    $query->bind_param("i", $user_id);
    $res = $query->execute();
    // die($res);
    if ($res == false) {
        $response['message'] = "Selected User Is Not Deleted";
        $response['type'] = -1;
    } else {
        $response['message'] = "Selected User Is Successfully Deleted";
        $response['type'] = 1;
    }
    echo json_encode($response);
    die;
}



/* @var $_POST type */
if (isset($_POST['new_password'])) {
    $pass_id = $_POST['password_id'];
    $new_pass = md5($_POST['new_password']);
    //$password = $_POST['password'];
    if ($_POST['new_password'] == $_POST['conf_password']) {
        $update = $mysqli->prepare("UPDATE `users` SET password = ? WHERE id = ?");
        $update->bind_param("si", $new_pass, $pass_id);
        $res = $update->execute();

        if ($res == false) {
            $response['message'] = display_error("Password Is Not Change");
            $response['type'] = -1;
        } else {
            $response['message'] = display_success("Password Is Changed Successfully");
            $response['type'] = 1;
        }
    } else {
        $response['message'] = display_error("Password Is Not Change");
        $response['type'] = -1;
    }
    echo json_encode($response);
    die;
}


if (isset($_POST['username'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password1 = $_POST['password'];
    $password = md5($password1);
    $date = date("Y-m-d H:i:s");



    if (!isset($_POST['username']) && !isset($_POST['email']) && !isset($_POST['password'])) {
        $response['message'] = display_error("Value Is Missing");
        $response['type'] = -1;
        echo json_encode($response);
        die;
    }

    $username2 = "SELECT * FROM `users`"
            . " WHERE"
            . " username = '$username'";

    $email2 = "SELECT * FROM `users`"
            . " WHERE"
            . " email = '$email'";

    $req = $mysqli->query($username2);
    $reg = $mysqli->query($email2);

// die(print_r($reg));

    if ($req->num_rows > 0) {

        $response['message'] = display_error("This Username Is Already Exist");
        $response['type'] = -1;
    } elseif ($reg->num_rows > 0) {

        $response['message'] = display_error("This Email Is Already Exist");
        $response['type'] = -1;
    } else {
        $query1 = "INSERT INTO `users`"
                . "(`username`, `email`, `password`, `datetime`)"
                . "VALUES"
                . "(?,?,?,?)";

        $query = $mysqli->prepare($query1);
       // echo "$username, $email, $password, $role, $date";

        $query->bind_param("ssss", $username, $email, $password, $date);

        $qr = $query->execute();




        if ($qr == false) {

            $response['message'] = display_error("Failed To Insert");
            $response['type'] = -1;
        } elseif ($email == $email2) {
            
        } else {
            $response['message'] = display_success("You have successfully added a new User");
            $response['type'] = 1;
        }
    }






    //echo "$query";
    echo json_encode($response);
    die;
}

if (isset($_POST['re_username'])) {

    $re_username = $_POST['re_username'];
    // die($re_username);
    $re_email = $_POST['re_email'];
    $edit_id = $_POST['edit_id'];

    $query5 = $mysqli->prepare("UPDATE `users` SET username = ?, email = ? WHERE id = ?");
    $query5->bind_param("ssi", $re_username, $re_email, $edit_id);
    $select = $query5->execute();

    if ($select == false) {
        $response['message'] = display_error("Some Thing Is Wrong");
        $response['type'] = -1;
    } else {
        $response['message'] = display_success("Information Updated Successfully");
        $response['type'] = 1;
    }

    echo json_encode($response);
    die;
}

if (isset($_POST['edit_id'])) {

    $edit_id = $_POST['edit_id'];

    $query4 = "SELECT * FROM `users` WHERE id = $edit_id";

    $select = $mysqli->query($query4);

    //die(print_r($select));

    if ($select->num_rows == 0) {
        $response['message'] = display_error("Selected Information Cannot Find");
        $response['type'] = -1;
    } else {

        $response = array();

        while ($row = $select->fetch_assoc()) {
            $response['username'] = $row['username'];
            $response['email'] = $row['email'];
            $response['role'] = $row['role'];
            // $response['password'] = $row['user_password'];
        }
        // die(print_r($response));
        // return $response;
    }

    echo json_encode($response);

    die;
}


$page_title = "Users List";
include("header.php");
include("left_sidebar.php");
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <h1>

<?php echo $page_title; ?> &nbsp;&nbsp;
            
        </h1>
            </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="branches_tbl" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>SignUp Date</th>
                                    <th>Action</th>
                                    


                                </tr>
                            </thead>
                            <tbody>
<?php echo getAllUserList(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>SignUp Date</th>
                                    <th>Action</th>
                                    
                                </tr>
                            </tfoot>
                        </table>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="modal modal-default add_user_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add New User</h4>
                    </div>
                    <form method="post" action="javascript:;" id="add_user_form">
                        <div class="modal-body ">
                            <span id="msg_add_user"></span>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>Username <span style="color:red">*</span></label><input id="username" name="username"  class="form-control" type="text" required/></div>

                            </div>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>Email <span style="color:red">*</span></label><input id="email" name="email"   class="form-control" type="email" required/></div>

                            </div>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>Password<span style="color:red">*</span></label><input id="password" name="password"  class="form-control" type="password" required/></div>
                            </div>
                            
                        </div>
                        <div class="modal-footer">

                            <i style="display:none" class="fa fa-refresh fa-spin fa-spin-add-user"></i>&nbsp;&nbsp;&nbsp;
                            <button class="btn  btn-success btn-disable-user  add_new_model_user"> Save </button>
                            <button type="button" class="btn btn-disable-user dismissle_1" data-dismiss="modal">Close</button>
                        </div>
                </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal modal-danger delete_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Delete User</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the User <strong id="br_name"></strong> ?</p>
                        <input type="hidden" id="delete_br_id" value="" />
                    </div>
                    <div class="modal-footer">
                        <strong id="msg"></strong>
                        <i style="display:none" class="fa fa-refresh fa-spin fa-spin-br"></i>&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-outline btn-disable-br delete_br_btn_yes" >Yes</button>
                        <button type="button" class="btn btn-outline btn-disable-br" data-dismiss="modal">No</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->  

        <div class="modal modal-default change_password_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Change Your Password</h4>
                    </div>
                    <form method="post" action="javascript:;" id="change_password_form">
                        <div class="modal-body ">
                            <span id="msg_change_password"></span>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>New Password <span style="color:red">*</span></label><input id="new_password" name="new_password"  class="form-control" type="password" required/></div>

                            </div>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>Confirm Password <span style="color:red">*</span></label><input id="conf_password" name="conf_password"   class="form-control" type="password" required/> <input  id="change_password" name="password_id" type="hidden" value=""/></div>

                            </div>

                        </div>
                        <div class="modal-footer">

                            <i style="display:none" class="fa fa-refresh fa-spin fa-spin-add-pass"></i>&nbsp;&nbsp;&nbsp;
                            <button class="btn  btn-success btn-disable-pass  change_password_model_btn" >Save</button>
                            <button type="button" class="btn btn-disable-pass dismissle_2" data-dismiss="modal">Close</button>
                        </div>
                </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <div class="modal modal-default edit_user_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit  User</h4>
                    </div>
                    <form method="post" action="javascript:;" id="edit_user_form">
                        <div class="modal-body ">
                            <span id="msg_edit_user"></span>
                            <center>
                                <i style="display:none; font-size: 25px;" class="fa fa-refresh fa-spin fa-spin-edit-top-user"></i>
                            </center>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>Username </label><input id="username2" value="" name="re_username"  class="form-control" type="text" required/></div>

                            </div>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>Email </label><input id="email2" value="" name="re_email"   class="form-control" type="email" required/></div>

                            </div>
                           
                            <div class="modal-body-add-branch">


                                <input type="hidden" value="" id="edit_id" name="edit_id"/>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <i style="display:none" class="fa fa-refresh fa-spin fa-spin-edit-user"></i>&nbsp;&nbsp;&nbsp;
                            <button class="btn  btn-success btn-disable-edit  edit_this_model_user"> Edit </button>
                            <button type="button" class="btn btn-disable-edit dismissle_3" data-dismiss="modal">Close</button>
                        </div>
                </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <!-- /.example-modal -->
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->
<input type="text" value="" style="display: none" id="data_holder" />


<?php include("footer_wrapper.php"); ?>
<?php include("footer_js.php"); ?>

<script>

    function change_status(id){
        console.log(id);
         
        $("#data_holder").val(id);
        <?php
            // $useer_id = 
            echo 'alert("'.$useer_id.'");';
            $query = "SELECT is_admin FROM `users` WHERE id = '".$useer_id."' ;";
            $res = $mysqli->query($query);
     
             while ($row = $res->fetch_assoc()) {
              echo "alert('".$row['is_admin']."');";

            }
        ?>
    }


    function remove_admin(id){
        $.ajax({
            type: "POST",
            url: "users.php",
            data: {"remove_admin": true, "user_id": id},
            success: function (response) {
                location.reload();
            }
        });
    }

    function make_admin(id){
        $.ajax({
            type: "POST",
            url: "users.php",
            data: {"make_admin": true, "user_id": id},
            success: function (response) {
                location.reload();
            }
        });
    }

    $(document).ready(function () {
        $("#branches_tbl").DataTable({
            "autoWidth": true,
            "order": [[0, "asc"]]
        });

        $('.li_user_list').addClass('active');

        $('.add_user').click(function () {

            $(".add_user_modal").modal('show');

        });


        $('.edit').click(function () {
            var edit_key = $(this).attr('data');
            $("#edit_id").val(edit_key);
            // alert(edit_key);

            $(".fa-spin-edit-top-user").show();
            $(".edit_user_modal").modal('show');

            $.ajax({
                type: "POST",
                url: "users.php",
                data: {edit_id: $("#edit_id").val()},
                success: function (response) {
                    var data = $.parseJSON(response);
                    if (data.type == -1) {
                        $("#msg_edit_user").html(data.message);
                    } else {
                        $("#username2").val(data.username);
                        $("#email2").val(data.email);
                        $(".role2 option[value="+data.role+"]").attr('selected',true);
                    }

                    $(".fa-spin-edit-top-user").hide();

                }
            });


        });

        $('.password').click(function () {
            $(".change_password_modal").modal('show');

        });

        $("#add_user_form").submit(function () {

            $('.btn-disable-user').attr("disabled", "disabled");
            $('.fa-spin-add-user').show();

            // alert($("#add_user_form").serialize());

            $.ajax({

                url: 'users.php',
                data: $("#add_user_form").serialize(),
                type: 'POST',
                success: function (response) {

                    //alert(response);
                    var data = $.parseJSON(response);
                    if (data.type == -1) {
                        $('.btn-disable-user').removeAttr("disabled", "disabled");
                        $('.fa-spin-add-user').hide();
                        $('#msg_add_user').html(data.message);
                    } else {
                        $('#msg_add_user').html(data.message);
                        location.reload();
                    }


                }
            });

            $(".dismissle_1").click(function () {
                $('#msg_add_user').hide();
            });
        });

        $('.delete').click(function () {
            var br_id = $(this).attr('data');
            //  var branch_name = $(this).attr('data-box');
            $('#msg').html("");
            $('#delete_br_id').val(br_id);
            // $('#br_name').html(branch_name);
            $(".delete_user").modal('show');
        });

        $('.delete_br_btn_yes').click(function () {
            $('.fa-spin-br').show();
            $('.fa-spin-job-br').css("font-size", "22px");
            $(".btn-disable-br").attr("disabled", "disabled");
            $.ajax({
                type: "POST",
                url: "users.php",
                data: {delete_user: true, delete_br_id: $("#delete_br_id").val()},
                success: function (response) {
                    var data = $.parseJSON(response);
                    if (data.type == -1) {
                        $('.fa-spin-br').hide();
                        $('#msg').show();
                        $('#msg').html(data.message);
                        $(".btn-disable-br").removeAttr("disabled");
                        $(".delete_user").modal('show');
                        //	 loadModelRates();
                        // location.reload();
                    } else {
                        $('.fa-spin-br').hide();
                        $('#msg').show();
                        $('#msg').html(data.message);
                        $(".btn-disable-br").removeAttr("disabled");
                        $(".delete_user").modal('hide');
                        location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert("error");
                }
            });




        });

        $(".password").click(function () {
            var id_pass = $(this).attr('data');
            $("#change_password").val(id_pass);
        });

        $("#change_password_form").submit(function () {



            var new_pass = $("#new_password").val();
            var conf_pass = $("#conf_password").val();
            $('.btn-disable-pass').attr("disabled", "disabled");
            $('.fa-spin-add-pass').show();
            // alert($("#add_user_form").serialize());
            if (new_pass === conf_pass) {
                $.ajax({
                    type: 'POST',
                    url: 'users.php',
                    data: $("#change_password_form").serialize(),
                    success: function (response) {

                        //      alert(response);
                        var data = $.parseJSON(response);
                        if (data.type == -1) {
                            $('.btn-disable-pass').removeAttr("disabled", "disabled");
                            $('.fa-spin-add-pass').hide();
                            $('#msg_change_password').show();
                            $('#msg_change_password').html(data.message);
                        } else {
                            $('#msg_change_password').show();
                            $('#msg_change_password').html(data.message);
                            location.reload();
                        }


                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert("error");
                    }
                });
            } else {
                var error = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close"  data-dismiss="alert" aria-hidden="true"></button><strong>Password Is Not Matched</strong></div>';
                $('#msg_change_password').html(error);
                $('.btn-disable-pass').removeAttr("disabled");
                $('.fa-spin-add-pass').hide();
            }

        });



        $(".dismissle_2").click(function () {


            $('#msg_change_password').hide();
        });

        $("#edit_user_form").submit(function () {
            $(".btn-disable-edit").attr("disabled", "disabled");
            $(".fa-spin-edit-user").show();

            $.ajax({
                type: "POST",
                url: "users.php",
                data: $("#edit_user_form").serialize(),
                success: function (response) {
                    var data = $.parseJSON(response);
                    if (data.type === -1) {
                        $("#msg_edit_user").html(data.message);
                        $("#msg_edit_user").show();
                        $(".btn-disable-edit").removeAttr("disabled");
                        $(".fa-spin-edit-user").hide();
                    } else {
                        $("#msg_edit_user").show();
                        $("#msg_edit_user").html(data.message);
                        location.reload();
                    }
                }
            });



        });

        





    });
</script>

<?php include("footer.php"); ?>


