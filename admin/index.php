<?php
$page_title = "Dashboard";
include("header_php.php");

redirect("my_account.php");

include("header.php");
include("left_sidebar.php");
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo ADMIN_BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" style="min-height:490px;">
        <!-- Small boxes (Stat box) -->
        <style>
            .btn-dash{
                height: 70px;
                font-size: 25px;
                padding-top: 17px;
            }
        </style>
        <div class="row">
            
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box btn-danger">
                    <div class="inner">
                        <h3><?php echo getTotalSalaries(); ?></h3>
                        <p>Total Salaries</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="transaction_salary.php" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box btn-danger">
                    <div class="inner">
                        <h3><?php echo getTotalNormalOutgoing(); ?></h3>
                        <p>Total Normal Outgoing</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="transaction_normal.php" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
             <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box btn-primary">
                    <div class="inner">
                        <h3><?php echo getTotalLoanFromDevpel(); ?></h3>
                        <p>Total Loan From Devpel</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="transaction_loan.php" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box btn-primary">
                    <div class="inner">
                        <h3><?php echo getTotalReturnLoanToDevpel(); ?></h3>
                        <p>Total Return Loan To Devpel</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="transaction_loan.php" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box btn-warning">
                    <div class="inner">
                        <h3><?php echo getTotalLoanToDevpel(); ?></h3>
                        <p>Total Loan To Devpel</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="transaction_loan.php" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box btn-warning">
                    <div class="inner">
                        <h3><?php echo getTotalReturnLoanFromDevpel(); ?></h3>
                        <p>Total Return Loan From Devpel</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="transaction_loan.php" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            
            
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box btn-info">
                    <div class="inner">
                        <h3><?php echo getTotalUsers() ?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                    <a href="users.php" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box btn-info">
                    <div class="inner">
                        <h3><?php echo getTotalemployees() ?></h3>
                        <p>Total Employees</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                    <a href="employees.php" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            

        </div><!-- /.row -->
        
        
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<?php include("footer_wrapper.php"); ?>
<?php include("footer_js.php"); ?>
<script>
    $('.dashboard_li').addClass("active");
</script>

<?php include("footer.php"); ?>