<?php
include("header_php.php");
$page_title = "All Branches";
include("header.php");
include("left_sidebar.php");
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <h1>

            Branches &nbsp;&nbsp;
            <a class="btn add_branch btn-success"><i class="fa fa-plus"></i>&nbsp; Add New Branch</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo ADMIN_BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> Branches</li>
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
                    <div class="box-body with-border">
                        

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->



        <!-- /.example-modal -->
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->




<?php include("footer_wrapper.php"); ?>
<?php include("footer_js.php"); ?>



<?php include("footer.php"); ?>


