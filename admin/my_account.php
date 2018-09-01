<?php
include("header_php.php");
$page_title = "My Account";
$response= array();
global $mysqli;

if (isset($_POST['id'])) {
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];

        $query = "SELECT * FROM `users` WHERE id = '$id' ";
        $res = $mysqli->query($query);

        // die($id);

        if ($res == true) {
            while ($rows = $res->fetch_assoc()) {
                $response['name'] = $rows['username'];
                $response['email'] = $rows['email'];
            }
        } else {
            $response['message'] = "Information Cannot Display";
        }
        echo json_encode($response);
        die;
    }
}

if (isset($_POST['username'])) {
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $name = $_POST['username'];
        $email = $_POST['email'];


        $query = $mysqli->prepare("UPDATE `users` SET username = ?, email = ? WHERE id = ?");
        $query->bind_param("ssi", $name, $email, $id);
        $res = $query->execute();

        if ($res == false) {
            $response['message'] = "Information Cannot Updated";
            $response['type'] = -1;
        } else {
            $response['message'] = "Information Successfully Updated";
            $response['type'] = 1;
            $_SESSION["tim_username"] = $name;
        }
    }

    echo json_encode($response);
    die;
}
if(isset($_POST['cur_pass'])){
    $cur_pass = md5($_POST['cur_pass']);
    $new_pass = $_POST['new_pass'];
    $conf_pass = $_POST['conf_pass'];
    $id = $_SESSION['user_id'];
    
    
    $query1 = "SELECT * FROM `users` WHERE password = '$cur_pass' AND id = $id";
    $res = $mysqli->query($query1);
    if ($res->num_rows <= 0){
        $response['message'] = display_error("Your Current Password Cannot Match");
        $response['type'] = -1;
    } else {
    
        if($new_pass === $conf_pass){
            $new_pass =md5($new_pass);
            $query2 = $mysqli->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $query2->bind_param("si", $new_pass, $id);
            $result = $query2->execute();
            
            if($result == false){
                $response['message'] = display_error("Password Cannot Updated");
                $response['type'] = -1;
            } else {
                 $response['message'] = display_success("Password Successfully Updated");
                $response['type'] = 1;
            }
            
        } else {
            $response['message'] = display_error("Password Cannot Match");
                $response['type'] = -1;
        }
    }
    
    echo json_encode($response);
    die;
    
    
}

include("header.php");
include("left_sidebar.php");
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <h1>

<?php echo $page_title ?> &nbsp;&nbsp;

        </h1>
        
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">My Account Details </h3>
                        <div class="box-tools pull-right">
                            <a class="btn change_password btn-info btn-sm btn-success">&nbsp; Change Password</a>&nbsp;&nbsp;
                            <button class="btn btn-box-tool" data-widget="collapse" style="font-size: 16px;"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body with-border">
                        <form action="javascript:;" method="post" enctype="multipart/form-data" id="my_account_form">
                            <div class="box-body" style="display: block;">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email <span style="color:red">*</span></label><small> (Email should be unique)</small>
                                            <input type="email" placeholder="" id="email" name="email" value="" class="form-control" required="">
                                        </div><!-- /.form-group -->
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Username <span style="color:red">*</span></label><small> (Username should be unique)</small>
                                            <input type="text" placeholder="" id="username" name="username" value="" class="form-control" required="">
                                        </div><!-- /.form-group -->
                                    </div> 
                                </div>

                            </div><!-- /.box-body -->
                            <div class="box-footer" style="display: block;">
                                <div class="box-tools ">
                                    &nbsp;&nbsp;&nbsp;<button style="padding-left: 25px;  padding-right: 25px;" class="btn btn-success update_account" type="submit">Save Changes</button>
                                    &nbsp;&nbsp;
                                    <span id="msg_add_account"></span>
                                    <i style="display:none;" class="fa fa-refresh fa-spin fa-spin-add-account"></i>


                                </div>
                            </div>
                        </form>
                    </div><!-- /.box-header -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
</div><!-- /.row -->


<div class="modal modal-default change_pass_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Change Your Password</h4>
                    </div>
                    <form method="post" action="javascript:;" id="change_pass_form">
                        <div class="modal-body ">
                            <span id="msg_change_pass"></span>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>Current Password <span style="color:red">*</span></label><input id="cur_pass" name="cur_pass"  class="form-control" type="password" required/></div>

                            </div>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>New Password <span style="color:red">*</span></label><input id="new_pass" name="new_pass"   class="form-control" type="password" required/></div>

                            </div>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label>Confirm Password<span style="color:red">*</span></label><input id="conf_pass" name="conf_pass"  class="form-control" type="password" required/></div>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <i style="display:none" class="fa fa-refresh fa-spin fa-spin-change-pass"></i>&nbsp;&nbsp;&nbsp;
                            <button class="btn  btn-success btn-disable-user  change_pass_model_user"> Save </button>
                            <button type="button" class="btn btn-disable-user dismissle_1" data-dismiss="modal">Close</button>
                        </div>
                </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


<!-- /.example-modal -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->




<?php include("footer_wrapper.php"); ?>
<?php include("footer_js.php"); ?>

<script>

    $(document).ready(function () {

        $(".my_account_li").addClass("active");

        $.ajax({
            url: "my_account.php",
            type: "POST",
            data: {id: true},
            success: function (response) {
                var data = $.parseJSON(response);
                $("#username").val(data.name);
                $("#email").val(data.email);
              
                $("#msg_add_account").html(data.message);


            }
        });


        $("#my_account_form").submit(function () {
            $(".update_account").attr("disabled", "disabled");
            $(".fa-spin-add-account").show();

            $.ajax({
                type: "POST",
                url: "my_account.php",
                data: $("#my_account_form").serialize(),
                success: function (response) {
                    var data = $.parseJSON(response);

                    if (data.type === -1) {
                        $("#msg_add_account").show();
                        $("#msg_add_account").html(data.message);
                        $(".update_account").removeAttr("disabled", "disabled");
                        $(".fa-spin-add-account").hide();
                    }else{
                        $("#msg_add_account").show();
                         $("#msg_add_account").html(data.message);
                         location.reload();
                    }
                }
            });

        });
        
        
        $(".change_password").click(function(){
            $(".change_pass_modal").show();
        });
        $("button[data-dismiss=modal]").click(function(){
            $(".change_pass_modal").hide();
             $(".btn-disable-user").removeAttr("disabled");
            $(".fa-spin-change-pass").hide();
        });
        
        $("#change_pass_form").submit(function(){
            $(".btn-disable-user").attr("disabled","disabled");
            $(".fa-spin-change-pass").show();
            
            $.ajax({
                type: "POST",
                url: "my_account.php",
                data: $("#change_pass_form").serialize(),
                success: function(response){
                    var data = $.parseJSON(response);
                    if(data.type === -1){
                        $("#msg_change_pass").show();
                         $("#msg_change_pass").html(data.message);
                         $(".btn-disable-user").removeAttr("disabled");
                         $(".fa-spin-change-pass").hide(); 
                    }else{
                        $("#msg_change_pass").show();
                         $("#msg_change_pass").html(data.message);
                         location.reload();
                    }
                }
            });
            
        });


    });


</script>

<?php include("footer.php"); ?>


