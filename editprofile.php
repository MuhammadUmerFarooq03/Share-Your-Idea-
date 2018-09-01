<?php 
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);


	if(isset($_POST['update-profile'])){
      // get file
      if (!empty($_FILES["file"]["name"])){
        // die();
        $file_name = basename( $_FILES["file"]["name"]);
        
        $target_dir =  "images/";
        $target_file = $target_dir.basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        if($target_file == "images/" ){
          $target_file ="";
        }
        $imagetype =  pathinfo($target_file , PATHINFO_EXTENSION);
        
        $check = @getimagesize($_FILES["file"]["tmp_name"]);
        if($check !== false) {
          $uploadOk = 1;
        } else {
          $uploadOk = 0;
        }
        
        if($imagetype != "jpg" && $imagetype != "png" && $imagetype != "jpeg" && $imagetype != "gif" ) {
          $uploadOk = 0;
        }
        
        // got file
                            
        if($uploadOk == 1){
          move_uploaded_file($_FILES["file"]["tmp_name"], $target_file) ;
          $q =  "update `users`  set  `profile_pic` = '" .$target_file."'  where `username` = '".$userLoggedIn."';";
          $qok = mysqli_query($con, $q);
          if(!$qok){
            echo "Noope";
            die();
          }
        }
      }
      if(!empty($_POST['first_name'])&&!empty($_POST['last_name'])){
          
        $fname =  $_POST['first_name'];
        $lname =  $_POST['last_name'];
            
        $q =  "update `users`  set `first_name` = '".$fname."'  , `last_name` = '".$lname. "'  where `username` = '".$userLoggedIn."';";
        $qok = mysqli_query($con, $q);
        if(!$qok){
        echo "Nae Bhai";
        }
        header("Location: editprofile.php?profile_username=".$userLoggedIn."");
            
        } else{
            // $e = "*Empty Fields";
        }               
        
    }




if(isset($_GET['profile_username'])) {
	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
	$user_array = mysqli_fetch_array($user_details_query);

	$num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}



if(isset($_POST['remove_friend'])) {
	$user = new User($con, $userLoggedIn);
	$user->removeFriend($username);
}

if(isset($_POST['add_friend'])) {
	$user = new User($con, $userLoggedIn);
	$user->sendRequest($username);
}
if(isset($_POST['respond_request'])) {
	header("Location: requests.php");
}

if(isset($_POST['post_message'])) {
  if(isset($_POST['message_body'])) {
    $body = mysqli_real_escape_string($con, $_POST['message_body']);
    $date = date("Y-m-d H:i:s");
    $message_obj->sendMessage($username, $body, $date);
  }

  $link = '#profileTabs a[href="#messages_div"]';
  echo "<script> 
          $(function() {
              $('" . $link ."').tab('show');
          });
        </script>";


}

 ?>

<style type="text/css">
  .wrapper {
    margin-left: 0px;
    padding-left: 0px;
  }

  .side-profile-link-color {
    color: #fff !important;
  }

  .side-profile-link-color:hover {
    color: #fff !important;
    text-decoration: underline;
  }
</style>

<div class="profile_left">
  <img src="<?php if($user_array['profile_pic']){echo $user_array['profile_pic'];}else{echo "assets\images\profile_pics\defaults\default_profile.jpeg"; }?>">

  <div class="profile_info">
    <p>
      <?php echo "Ideas: " . $user_array['num_posts']; ?>
    </p>
    <p>
      <?php echo "Likes: " . $user_array['num_likes']; ?>
    </p>

    <?php
        $profile_user_obj = new User($con, $username);
        if($profile_user_obj->isAdmin($userLoggedIn)) {
          ?>
    <p>
      <?php echo '<a href="http://localhost:1234/background_Video_Slider/Demo/admin/index.php" name="remove_friend" class="side-profile-link-color" >Go to Admin</a>' ?>
    </p>
    <?php
        }

      ?>
    <p>
      <?php echo '<a href="http://localhost:1234/background_Video_Slider/Demo/editprofile.php?profile_username='. $userLoggedIn.'" name="remove_friend" class="side-profile-link-color" >Edit Profile</a>'; ?>
    </p>

  </div>

  <form action="<?php echo $username; ?>" method="POST">
    <?php 
 			$profile_user_obj = new User($con, $username); 
 			if($profile_user_obj->isClosed()) {
 				header("Location: user_closed.php");
 			}

 			$logged_in_user_obj = new User($con, $userLoggedIn); 

 			if($userLoggedIn != $username) {

 				if($logged_in_user_obj->isFriend($username)) {
 					echo '<input type="submit" name="remove_friend" class="danger" value="Remove Following"><br>';
 				}
 				else if ($logged_in_user_obj->didReceiveRequest($username)) {
 					echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
 				}
 				else if ($logged_in_user_obj->didSendRequest($username)) {
 					echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
 				}
 				else 
 					echo '<input type="submit" name="add_friend" class="success" value="Follow"><br>';

 			}

 			?>
  </form>
  <!-- 	<input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something"> -->


</div>


<div class="profile_main_column column">

  <ul class="nav nav-tabs" role="tablist" id="profileTabs">
    <li role="presentation" class="active"><a href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Profile</a></li>

  </ul>

  <div class="tab-content">

    <div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
      <form action="#" method="POST" enctype="multipart/form-data">

        <div class="form-group">
          <label for="name">First Name</label>
          <input type="text" id="first_name" value="<?= $user['first_name']; ?>" name="first_name" class="form-control">
        </div>


        <div class="form-group">
          <label for="last_name">Last Name</label>
          <input type="text" id="last_name" name="last_name" value="<?= $user['last_name']; ?>" class="form-control">
        </div>

        <div class="form-group">
          <label for="image">Profile Image</label>
          <input type="file" id="image" name="file" class="form-control">
        </div>

        <div class="form-group">
          <input type="submit" name="update-profile" class="btn btn-primary">
        </div>

      </form>
      <img id="loading" src="assets/images/icons/loading.gif">
    </div>



  </div>


</div>


</div>

<!-- Modal -->
<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="postModalLabel">Post something!</h4>
      </div>

      <div class="modal-body">
        <p>This will appear on the user's profile page and also their newsfeed for your friends to see!</p>

        <form class="profile_post" action="" method="POST">
          <div class="form-group">
            <textarea class="form-control" name="post_body"></textarea>
            <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
            <input type="hidden" name="user_to" value="<?php echo $username; ?>">
          </div>
        </form>
      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
      </div>
    </div>
  </div>
</div>


<script>
  var userLoggedIn = '<?php echo $userLoggedIn; ?>';
  var profileUsername = '<?php echo $username; ?>';

  $(document).ready(function () {

    $('#loading').show();

    //Original ajax request for loading first posts 
    $.ajax({
      url: "includes/handlers/ajax_load_profile_posts.php",
      type: "POST",
      data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
      cache: false,

      success: function (data) {
        $('#loading').hide();
        $('.posts_area').html(data);
      }
    });

    $(window).scroll(function () {
      var height = $('.posts_area').height(); //Div containing posts
      var scroll_top = $(this).scrollTop();
      var page = $('.posts_area').find('.nextPage').val();
      var noMorePosts = $('.posts_area').find('.noMorePosts').val();

      if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts ==
        'false') {
        $('#loading').show();

        var ajaxReq = $.ajax({
          url: "includes/handlers/ajax_load_profile_posts.php",
          type: "POST",
          data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
          cache: false,

          success: function (response) {
            $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
            $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

            $('#loading').hide();
            $('.posts_area').append(response);
          }
        });

      } //End if 

      return false;

    }); //End (window).scroll(function())


  });
</script>





</div>
</body>

</html>