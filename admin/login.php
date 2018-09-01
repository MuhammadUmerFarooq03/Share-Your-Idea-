<?php 
session_start();
include("constants.php");
if(isset($_SESSION["username"]) && !empty($_SESSION['username'])){
	header("Location: " . ADMIN_BASE_URL);
	die;
}

include("connection.php");
include("functions.php");
if(isset($_POST['login_s'])){
	global $mysqli;
	$password = $_POST['password'];
	$email = $_POST['email'];
	$password = decryptPassword($password);
	$where_cl = "email='".$email."' AND password='".$password."'";

	$res = $mysqli->query("SELECT * FROM users WHERE ".$where_cl);
	$re_fetch = mysqli_fetch_assoc($res);
	if($res->num_rows <= 0){
		$response_array['message'] = '<div class="alert alert-danger alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>										
										<strong>Error: </strong>Incorrect Email/Password.
									  </div>';
		$response_array['type'] = -1;	
		echo json_encode($response_array);				
		die;				
	}
	
	
	$_SESSION["username"] = $re_fetch['username'];
	
	$_SESSION["user_id"] = $re_fetch['user_id'];
     //   $_SESSION["user_role"] = $re_fetch['role'];
	
	$response_array['type'] = 1;	
	$response_array['message'] = ' <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>                    
                    <strong>Success!</strong> You have successfully logged-in.
                  </div>';	
	echo json_encode($response_array);
	die;
}


if(isset($_POST['forgot'])){
	global $mysqli;
	$email = $_POST['email'];
        $code = uniqid();
	$where_cl = "email='".$email."'";

	$res = $mysqli->query("SELECT * FROM users WHERE ".$where_cl);
        $re_fetch = mysqli_fetch_assoc($res);
	if($res->num_rows <= 0){
		$response_array['message'] = '<div class="alert alert-danger alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>										
										<strong>Error: </strong>Incorrect Email.
									  </div>';
		$response_array['type'] = -1;	
		echo json_encode($response_array);				
		die;				
	}
	
	$res1 = $mysqli->query("UPDATE `users` SET resetcode='$code' WHERE ".$where_cl);
        
	
	$_SESSION['code'] = $code;
        $_SESSION['id'] = $re_fetch['user_id'];
	$response_array['type'] = 1;	
	$response_array['message'] = ' <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>                    
                    <strong>Success!</strong> Check your email.
                  </div>';	
	echo json_encode($response_array);
	die;
}



if(isset($_POST['cur_pass'])){
    global $mysqli;
    $new_pass = $_POST['new_pass'];
    $conf_pass = $_POST['conf_pass'];
    $id = $_POST['user_id'];
    
    
    
    
        if($new_pass === $conf_pass){
            $new_pass =md5($new_pass);
            $res  = $mysqli->query("UPDATE `users` SET password = '$new_pass',resetcode = NULL WHERE user_id = $id");
            
            
            
            if($res == false){
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
    
    echo json_encode($response);
    die;
    
    
}

?>

