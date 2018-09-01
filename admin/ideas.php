<?php
include("header_php.php");
$page_title = "Ideas";
$response = array();
global $mysqli;




if (isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    // die("Hello");
    $query = "SELECT * FROM lgu_ideas_2018 WHERE id = $id";
    $res = $mysqli->query($query);
    $result = $res->fetch_assoc();


    echo json_encode($result);
    die;
}

// Adding new Ideas
if (isset($_POST['inner_key'])) {
    $member = $_POST['member'];
    $project = $_POST['project'];
    $detail = $_POST['detail'];
    $contact = $_POST['contact'];
    $rand = uniqid();
    $user_id = $_SESSION['user_id'];




    $query1 = $mysqli->prepare("INSERT INTO lgu_ideas_2018 ( `member`, `project`, `project_detail`, `contact`) VALUES (?, ?, ?, ?)");



    $query1->bind_param("ssss", $member, $project, $detail, $contact);
    $res1 = $query1->execute();

    $id = $mysqli->insert_id;

    if ($res1 == FALSE) {
        $response['message'] = display_error("Idea Is Not Added");
        $response['type'] = -1;
    } else {
        $response['message'] = display_success("Idea Is Successfully Added");
        $response['type'] = 1;
    }
 


    echo json_encode($response);
    die;
}

if (isset($_POST['image_key'])) {
    $id = $_POST['image_key'];

    $query = "SELECT * FROM blogs WHERE blog_id = $id";
    $res = $mysqli->query($query);
    $result = $res->fetch_assoc();

    $response['image'] = "<img src='../images/blogs/$id/" . $result['image'] . "' class='img-thumbnail' alt='Image' style='height:200px;'/> ";

    echo json_encode($response);
    die();
}






if (isset($_POST['del_id'])) {
    $id = $_POST['del_id'];

    $query = $mysqli->prepare("DELETE FROM `lgu_ideas_2018` WHERE id = ?");
    $query->bind_param("i", $id);





    $res = $query->execute();
    // die($res);
    if ($res == false) {
        $response['message'] = "Selected Blog Is Not Deleted";
        $response['type'] = -1;
    } else {
        deleteDir("../images/blogs/".$id);
        $response['message'] = "Selected Blog Is Successfully Deleted";
        $response['type'] = 1;
    }
    echo json_encode($response);
    die;
}




if (isset($_POST['image_id'])) {
    $image = $_FILES['change_image'];
    $id = $_POST['image_id'];


    $images = getimagesize($image['tmp_name']);

    if (!$images) {
        $response['message'] = display_error("Blog Image IS Missing");
        $response['type'] = -1;
    } else {

        if ($images['mime'] != "image/jpeg" && $images['mime'] != "image/png" && $images['mime'] != "image/jpg") {
            $response['message'] = display_error("Blog Image Format Is Invalid");
            $response['type'] = -1;
        } else {

            if ($image['size'] < 5000000) {

                $query = $mysqli->prepare("UPDATE `lgu_ideas_2018` SET member = ? WHERE blog_id = ?");
                $query->bind_param("si", $image['name'], $id);
                $res = $query->execute();



                if ($res == false) {
                    $response['message'] = display_error("Blog Image Cannot Updated");
                    $response['type'] = -1;
                } else {


                    upload_inner_image($image['tmp_name'], "$id", $image['name'], "../images/blogs");
                    $response['message'] = display_success("Blog Image Success Fully Updated");
                    $response['type'] = 1;
                }
            } else {
                $response['message'] = display_error("Blog Image Is To Large");
                $response['type'] = -1;
            }
        }
    }


    echo json_encode($response);

    die;
}




if (isset($_POST['edit_key']) && !empty($_POST['edit_key'])) {
    $id = $_POST['edit_key'];
    $member = $_POST['member'];
    $project = $_POST['project'];
    $detail = $_POST['detail'];
    $contact = $_POST['contact'];


    $update = $mysqli->prepare("UPDATE `lgu_ideas_2018` SET member = ?, project = ?, project_detail = ?, contact = ? WHERE id = ?");
    $update->bind_param("sssss", $member, $project, $detail, $contact, $id);
    $res = $update->execute();

    if ($res == false) {
        $response['message'] = display_error("Idea Is Not Edit");
        $response['type'] = -1;
    } else {
        $response['message'] = display_success("Idea Page Is Edit Successfully");
        $response['type'] = 1;
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
<a href="javascript:;" class="btn btn-inner btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp; Add Idea</a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
            <div class="box-header">
                <h3 class="box-title">Ideas List </h3>
                <div class="box-tools pull-right">
                    
                    &nbsp;&nbsp;
                    <button class="btn btn-box-tool" data-widget="collapse" style="font-size: 16px;"><i class="fa fa-minus"></i></button>
                </div>
            </div>

            <div class="box-body with-border table-responsive">
                <table id="inner_ser_tbl" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th> Member</th>
                            <th> Project</th>
                            <th> Description </th>
                            <th> Supervisor</th>
                            <th> Action </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php echo getAllBlogsList(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th> Member</th>
                            <th> Project</th>
                            <th> Description </th>
                            <th> Supervisor</th>
                            <th> Action </th>
                        </tr>
                    </tfoot>
                </table>

            </div><!-- /.box-body -->
        </div><!-- /.box-body -->

            </div><!-- /.box-header -->
        </div><!-- /.box-body -->


       




       



        




        <div class="modal modal-default add_inner_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add Idea </h4>
                    </div>
                    <form method="post" action="javascript:;" id="add_inner_form">
                        <div class="modal-body ">
                            <span id="msg_add_inner"></span>

                            <div class="modal-body-inner-branch">

                                <div class="form-group">
                                    <label>Member <span style="color:red">*</span></label>
                                    <input id="inner_title" name="member"  class="form-control" type="text" required/>
                                </div>
                                <div class="form-group">
                                    <label>Project <span style="color:red">*</span></label>
                                    <input id="inner_title" name="project"  class="form-control" type="text" required/>
                                </div>

                                <input type="hidden" name="inner_key" id="inner_key" value="sfrdssd3424dffshgh"/>
                            </div>
                            <div class="modal-body-inner-branch">

                                <div class="form-group">
                                    <label>Project Detail <span style="color:red">*</span></label>
                                    <textarea id="detail" name="detail" rows="8" cols="6"  class="form-control" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Supervisor<span style="color:red">*</span></label>
                                    <input id="inner_title" name="contact"  class="form-control" type="text" required/>
                                </div>
                            </div>

                            <div class="modal-footer">

                                <i style="display:none" class="fa fa-refresh fa-spin fa-spin-add-inner"></i>&nbsp;&nbsp;&nbsp;
                                <button class="btn  btn-success btn-disable-inner  add_new_model_inner"> Save </button>
                                <button type="button" class="btn btn-disable-inner dismissle_1" data-dismiss="modal">Close</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </form>
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>

        <div class="modal modal-danger delete_inner" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Delete Idea</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the Idea <strong id="br_name"></strong> ?</p>
                        <input type="hidden" id="del_inner_id" value="" />
                    </div>
                    <div class="modal-footer">
                        <strong id="msg_inner"></strong>
                        <i style="display:none" class="fa fa-refresh fa-spin fa-spin-inner"></i>&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-outline btn-disable-inner delete_inner_btn_yes" >Yes</button>
                        <button type="button" class="btn btn-outline btn-disable-inner" data-dismiss="modal">No</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal modal-default change_image_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Change Idea Image</h4>
                    </div>
                    <form method="post" action="javascript:;" id="change_image_form">
                        <div class="modal-body ">
                            <span id="msg_change_image"></span>

                            <center>
                                <i style="display:none; font-size: 25px; color: #367fa9;" class="fa fa-refresh fa-spin fa-spin-edit-top-image"></i>
                            </center>


                            <div class="modal-body-add-branch">

                                <center>
                                    <span id="image6"></span>
                                </center>

                            </div>
                            <div class="modal-body-add-branch">

                                <div class="form-group"><label> Change Image <span style="color:red">*</span></label><input id="change_image" name="change_image" class="form-control" type="file" required/> </div>
                                <input type="hidden" name="image_id" id="image_id" value="" />
                            </div>

                        </div>
                        <div class="modal-footer">

                            <i style="display:none" class="fa fa-refresh fa-spin fa-spin-add-image"></i>&nbsp;&nbsp;&nbsp;
                            <button class="btn  btn-success btn-disable-image  change_image_model_btn" type="submit">Save</button>
                            <button type="button" class="btn btn-disable-image dismissle_2" data-dismiss="modal">Close</button>
                        </div>
                </div> 
                </form>
            </div> 
        </div> 

        <div class="modal modal-default edit_inner_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit Ideas </h4>
                    </div>
                    <form method="post" action="javascript:;" id="edit_inner_form">
                        <div class="modal-body ">
                            <span id="msg_edit_inner"></span>
                            <center>
                                <i style="display:none; font-size: 25px;" class="fa fa-refresh fa-spin fa-spin-edit-top-set"></i>
                            </center>
                            <div class="modal-body-edit-branch">

                                <div class="form-group">
                                    <label>Member <span style="color:red">*</span></label>
                                    <input id="edit_member" name="member"  class="form-control" type="text" required/>
                                    <input type="hidden" name="edit_key" id="edit_key" value="sfrdssd3424dffshgh"/>
                                </div>
                                <div class="form-group">
                                    <label>Project <span style="color:red">*</span></label>
                                    <input id="edit_project" name="project"  class="form-control" type="text" required/>
                                </div>

                               
                            </div>
                            <div class="modal-body-edit-branch">

                                <div class="form-group">
                                    <label>Project Detail <span style="color:red">*</span></label>
                                    <textarea id="edit_detail" name="detail" rows="8" cols="6"  class="form-control" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Supervisor<span style="color:red">*</span></label>
                                    <input id="edit_contact" name="contact"  class="form-control" type="text" required/>
                                </div>
                            </div>
                          
                            <div class="modal-footer">

                                <i style="display:none" class="fa fa-refresh fa-spin fa-spin-edit-inner"></i>&nbsp;&nbsp;&nbsp;
                                <button class="btn  btn-success btn-disable-inner  edit_new_model_inner"> Save </button>
                                <button type="button" class="btn btn-disable-inner dismissle_1" data-dismiss="modal">Close</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </form>
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>




        <!-- /.example-modal -->
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->




<?php include("footer_wrapper.php"); ?>
<?php include("footer_js.php"); ?>

<script>


    $(document).ready(function () {

        $("#inner_ser_tbl").DataTable({
            "autoWidth": true,
            "order": [[0, "asc"]]
        });


        $(".edit-inner-btn").click(function () {

            $(".edit_inner_modal").show();
            // alert("response");
            var edit_id = $(this).attr("data");
            $("#edit_key").val(edit_id);
            $(".fa-spin-edit-top-set").show();
            // alert($("#edit_key").val());

            $.ajax({
                type: "POST",
                url: "ideas.php",
                data: {edit_id: edit_id},
                success: function (response) {
                    //alert(response);
                    var data = $.parseJSON(response);

                    $("#edit_member").val(data.member);
                    $("#edit_project").val(data.project);
                    $("#edit_detail").val(data.project_detail);
                    $("#edit_contact").val(data.contact);


                    $(".fa-spin-edit-top-set").hide();
                }
            });

        });




        $(".img-inner-btn").click(function () {
            $(".change_image_modal").show();
            $(".fa-spin-edit-top-image").show();
            var id = $(this).attr("data");
            $("#image_id").val(id);
            $.ajax({
                url: "ideas.php",
                type: "POST",
                data: {image_key: id},
                success: function (response) {
                    var data = $.parseJSON(response);
                    $("#image6").html(data.image);
                    $(".fa-spin-edit-top-image").hide();
                }
            });
        });

        $("#change_image_form").submit(function () {
            $(".btn-disable-image").attr("disabled", true);
            $(".fa-spin-add-image").show();

            $.ajax({
                type: "POST",
                url: "ideas.php",
                contentType: false,
                processData: false,
                cache: false,
                data: new FormData(this),
                success: function (response) {
                    //    alert(response);
                    var data = $.parseJSON(response);
                    if (data.type === -1) {
                        $(".btn-disable-image").removeAttr("disabled");
                        $(".fa-spin-add-image").hide();
                        $("#msg_change_image").show();
                        $("#msg_change_image").html(data.message);
                    } else {
                        $("#msg_change_image").show();
                        $("#msg_change_image").html(data.message);
                        location.reload();
                    }
                }
            });


        });


        $("#edit_inner_form").submit(function () {
            $(".btn-disable-inner").attr("disabled", true);
            $(".fa-spin-edit-inner").show();

            $.ajax({
                type: "POST",
                url: "ideas.php",
                data: $("#edit_inner_form").serialize(),
                success: function (response) {
                    //    alert(response);
                    var data = $.parseJSON(response);
                    if (data.type === -1) {
                        $(".btn-disable-inner").removeAttr("disabled");
                        $(".fa-spin-edit-inner").hide();
                        $("#msg_edit_inner").show();
                        $("#msg_edit_inner").html(data.message);
                    } else {
                        $("#msg_edit_inner").show();
                        $("#msg_edit_inner").html(data.message);
                        location.reload();
                    }
                }
            });




        });





        $(".service_li").addClass("active");
        $(".btn-inner").click(function () {
            $(".add_inner_modal").show();
        });
        $(".del-inner-btn").click(function () {
            $(".delete_inner").show();
            var id = $(this).attr("data");
            $("#del_inner_id").val(id);
        });
        $(".delete_inner_btn_yes").click(function () {

            $(".btn-disable-inner").attr("disabled", true);
            $(".fa-spin-inner").show();
            var del_key = $("#del_inner_id").val();
            $.ajax({
                type: "POST",
                url: "ideas.php",
                data: {del_id: del_key},
                success: function (response) {
                    // alert(response);
                    var data = $.parseJSON(response);
                    if (data.type === -1) {
                        $(".btn-disable-inner").removeAttr("disabled");
                        $(".fa-spin-add-inner").hide();
                        $("#msg_inner").show();
                        $("#msg_inner").html(data.message);
                    } else {
                        $("#msg_inner").show();
                        $("#msg_inner").html(data.message);
                        location.reload();
                    }
                }

            });
        });
        $("#add_inner_form").submit(function () {
            $(".btn-disable-inner").attr("disabled", true);
            $(".fa-spin-add-inner").show();
            $.ajax({
                type: "POST",
                url: "ideas.php",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                success: function (response) {
                    // alert(response);
                    var data = $.parseJSON(response);
                    if (data.type === -1) {
                        $(".btn-disable-inner").removeAttr("disabled");
                        $(".fa-spin-inner").hide();
                        $("#msg_add_inner").show();
                        $("#msg_add_inner").html(data.message);
                    } else {
                        $("#msg_add_inner").show();
                        $("#msg_add_inner").html(data.message);
                        location.reload();
                    }
                }
            });
        });



























        $(".view_btn").click(function () {
            $(".image_modal").show();
            var img_key = $(".view_btn").attr("data");
            $(".fa-spin-edit-top-logo").show();
            $.ajax({
                url: "ideas.php",
                type: "POST",
                data: {img_key: img_key},
                success: function (response) {
                    var data = $.parseJSON(response);
                    $("#image4").html(data.image);
                    $(".fa-spin-edit-top-logo").hide();
                }
            });
        });
        var edit_id = "da34ssf";
        $.ajax({
            type: "POST",
            url: "ideas.php",
            data: {edit: edit_id},
            success: function (response) {
                //alert(response);
                var data = $.parseJSON(response);
                $("#page_title").val(data.ser_title);
                $("#page_desc").val(data.ser_description);
                $("#setting").val(data.ser_id);
                if (data.ser_image === "") {

                    $("#image").hide();
                    $(".image2").show();
                } else {
                    $(".img-div").css({
                        marginBottom: "45px"
                    });
                    $(".image2").hide();
                    $("#image").show();
                    $("#image3").attr("src", "../images/service/" + data.ser_image);
                    $(".btn-live").css("line-height", "0.8");
                    $(".btn-live").attr("data", data.ser_id);
                    $(".btn-live").css({
                        marginTop: "10px"
                    });
                }
                $(".fa-spin-edit-top-service").hide();
            }
        });
        $(".del_btn").click(function () {
            $("#delete_service").show();
            var del_id = $(this).attr("data");
            $("#delete_service_id").val(del_id);
            // alert($("#delete_service_id").val());
        });
        $(".delete_service_btn_yes").click(function () {
            $(".btn-disable-br").attr("disabled", true);
            $(".fa-spin-service_del").show();
            var del_id = $("#delete_service_id").val();
            $.ajax({
                type: "POST",
                url: "ideas.php",
                data: {del_key: del_id},
                success: function (response) {
                    var data = $.parseJSON(response);
                    if (data.type == -1) {
                        $(".btn-disable-br").removeAttr("disabled");
                        $(".fa-spin-service_del").hide();
                        $("#msg").show();
                        $("#msg").html(data.message);
                    } else {
                        $("#msg").show();
                        $("#msg").html(data.message);
                        location.reload();
                    }
                }
            });
        });
        $("#edit_service_form").submit(function () {
            $(".edit_btn").attr("disabled", true);
            $(".fa-spin-edit-service").show();
            $.ajax({
                type: "POST",
                url: "ideas.php",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                success: function (response) {
                    //    alert(response);
                    var data = $.parseJSON(response);
                    if (data.type === -1) {
                        $(".edit_btn").removeAttr("disabled");
                        $(".fa-spin-edit-service").hide();
                        $("#msg_edit_service").show();
                        $("#msg_edit_service").html(data.message);
                    } else {
                        $("#msg_edit_service").show();
                        $("#msg_edit_service").html(data.message);
                        location.reload();
                    }
                }
            });
        });
        $("button[data-dismiss=modal]").click(function () {
            $(".add_service_modal").hide();
            $(".edit_btn").removeAttr("disabled");
            $(".fa-spin-add-service").hide();
            $("#msg_add_service").hide();
            $("#delete_service").hide();
            $("#delete_service").hide();
            $(".btn-disable-br").removeAttr("disabled", true);
            $(".fa-spin-service_del").hide();
            $("#msg").hide();
            $(".image_modal").hide();
            $(".add_service_modal").hide();
            $(".edit_service_modal").hide();
            $(".btn-disable-service").removeAttr("disabled");
            $(".fa-spin-edit-service").hide();
            $("#msg_edit_service").hide();
            $(".change_logo_modal").hide();
            $(".edit_inner_modal").hide();
            $(".btn-disable-logo").removeAttr("disabled");
            $(".fa-spin-add-logo").hide();
            $(".btn-disable-inner").removeAttr("disabled");
            $(".fa-spin-add-inner").hide();
            $("#msg_inner").hide();
            $(".btn-disable-inner").removeAttr("disabled");
            $(".fa-spin-inner").hide();
            $("#msg_add_inner").hide();
            $("#msg_change_logo").hide();
            $(".add_inner_modal").hide();
            $(".change_image_modal").hide();
            $(".btn-disable-image").removeAttr("disabled");
            $(".fa-spin-add-image").hide();
            $(".delete_inner").hide();
            $(".btn-disable-inner").removeAttr("disabled");
            $(".fa-spin-edit-inner").hide();
            $("#msg_edit_inner").hide();
        });
    });




</script>

<?php include("footer.php"); ?>


