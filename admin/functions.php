<?php

function decryptPassword($password) {
    $decpass = md5($password);
    return $decpass;
}

function redirect($location, $delay = 0) {
    echo "<meta http-equiv='REFRESH' content='" . $delay . "; url=" . $location . "'>";
    exit;
}

function display_error($error) {
    $response = '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close"  data-dismiss="alert" aria-hidden="true"></button>                    
                    <strong> ' . $error . ' </strong>.
                  </div>';
    return $response;
}

function display_success($success) {
    $response = '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close"  data-dismiss="alert" aria-hidden="true"></button>                    
                    <strong> ' . $success . ' </strong>.
                  </div>';
    return $response;
}

function copy_image($base_path, $old_id, $image_name, $new_path, $new_id) {
    $b_path = "$base_path/$old_id/$image_name";

    $str_path = "$new_path/$new_id/$image_name";
    if (!is_dir($new_path)) {
        if (!mkdir($new_path)) {
            $error = (" * failed to create folder $new_path");
        }
    }

    //die($name);

    if (!is_dir("$new_path/$new_id")) {
        if (!mkdir("$new_path/$new_id")) {
            $error = (" * failed to create folder $new_path/$new_id");
        }
    }


    @copy($b_path, $str_path);
}

function deleteDir($dirPath) {
    if (!is_dir($dirPath)) {
        $response['message'] = " Successfully Deleted";
        $response['type'] = 1;
    }

    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }

    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
          //  self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function getAllUserList() {
    $user_id = $_SESSION["user_id"];
    global $mysqli;
    $query = "SELECT * FROM `users` WHERE id != $user_id ORDER BY id DESC";
    $user_list = '';
    $i = 1;
    $res = $mysqli->query($query);
    while ($row = $res->fetch_assoc()) {
       
        $user_list .= "<tr>";
        $user_list .= "<td>" . $i++ . "</td>"
                . "<td>" . $row['username'] . "</td>"
                . "<td>" . $row['email'] . "</td>"
                . "<td>" . $row['signup_date'] . "</td>"
                . "<td> <a class='btn btn-sm btn-danger delete' data='" . $row['id'] . "'><i class='fa fa-trash'></i> Delete</a></td>";
        $user_list .= "</tr>";
    }


    return $user_list;
}


function upload_image($soruce_path, $profile_image, $dir) {
    //die($name);
    $str_path = "$dir/$name/$profile_image";
    if (!is_dir("$dir")) {
        if (!mkdir("$dir")) {
            $error = (" * failed to create folder $dir");
        }
    }

    //die($name);



    $result = @move_uploaded_file($soruce_path, $str_path);

    if (!$result) {
        $error = (" * Faield to uplaod file");
    } else {
        $error = "";
    }
    echo $error;
}









function getAllBlogsList() {
    global $mysqli;
    
    $query = "SELECT * FROM lgu_ideas_2018 ORDER BY id DESC";
    $res = $mysqli->query($query);
    $list = "";
    $i = 1;

    while ($row = $res->fetch_assoc()) {
        $list .= "<tr>";
        $list .= "<td>" . $i++ . "</td>"
                . "<td>" . $row['member'] . "</td>"
                . "<td>" . $row['project'] . "</td>"
                . "<td>" . $row['project_detail'] . "</td>"
                 . "<td>" . $row['contact'] . "</td>"
                . "<td><div class='dropdown'>
    <button class='btn btn-primary dropdown-toggle' type='button' data-toggle='dropdown'>
    <span class='caret'></span></button>
    <ul class='dropdown-menu' style='left:-125px !important'>
        <li><a href='javascript:;' class='edit-inner-btn' data='" . $row['id'] . "'><i class='fa fa-pencil'></i>  View/Edit</a></li>
     
      <li><a href='javascript:;' class='del-inner-btn' data='" . $row['id'] . "'><i class='fa fa-trash'></i> Delete</a></li>
    </ul>
  </div></td>";
        $list .= "</tr>";
    }

    return $list;
}





function getAllComments() {
    global $mysqli;
    
    
        
        
    
    
    $query = "SELECT * FROM comments ORDER BY comment_id DESC";
   
    $res = $mysqli->query($query);
    $list = "";
    $i = 1;
 //   $row = $res->fetch_assoc();
 
    function getBlog($id) {
        global $mysqli;
        
        $query1 = "SELECT * FROM blogs WHERE blog_code = '$id'";
        $result = $mysqli->query($query1);
        $res1 = $result->fetch_assoc();
        return $res1['title'];
    }
  //  die();

    while ($row = $res->fetch_assoc()) {
        $approve = "";
        $cancel = "";
        $status = "";
        if ($row['status'] === "Approved") {
            $approve = "<a class='dis' data='" . $row['comment_id'] . "' disabled='disabled'><i class='fa fa-check'></i> Approved</a>";
            $status = "<span class='label label-success'> " . $row['status'] . " </span>";
            $cancel = "<a href='javascript:;' class='cancel_btn' data='" . $row['comment_id'] . "'><i class='fa fa-close'></i>  Unapproved </a>";
        } elseif ($row['status'] === "Unapproved") {
            $cancel = "<a class='dis ' data='" . $row['comment_id'] . "' disabled='disabled'><i class='fa fa-close'></i>  Unapproved </a>";
            $status = "<span class='label label-danger'> " . $row['status'] . " </span>";
            $approve = "<a href='javascript:;' class='approve_btn' data='" . $row['comment_id'] . "'><i class='fa fa-check'></i>  Approved </a>";
        } else {
            $approve = "<a href='javascript:;' class='approve_btn' data='" . $row['comment_id'] . "'><i class='fa fa-check'></i>  Approved </a>";
            $cancel = "<a href='javascript:;' class='cancel_btn' data='" . $row['comment_id'] . "'><i class='fa fa-close'></i>  Unapproved  </a>";
            $status = "<span class='label label-primary'> Pending </span>";
        }



        $list .= "<tr>";
        $list .= "<td>" . $i++ . "</td>"
                . "<td><div class='dropdown'>
    <button class='btn btn-primary dropdown-toggle' type='button' data-toggle='dropdown'>
    <span class='caret'></span></button>
    <ul class='dropdown-menu'>
      <li>$cancel</li>
          <li>$approve</li>
      <li><a href='javascript:;' class='del_btn' data='" . $row['comment_id'] . "'><i class='fa fa-trash'></i> Delete Comment</a></li>
    </ul>
  </div></td>"
                . "<td>" . $row['name'] . "</td>"
                . "<td>" . $row['email'] . "</td>"
                . "<td>" . $row['comment'] . "</td>"
                . "<td>" . getBlog($row['blog_code']) . "</td>"
                
                . "<td> $status </td>"
                . "<td>" . $row['date'] . "</td>";
        $list .= "</tr>";
       
    }

    return $list;
}

function success_alert($success) {
    $li = "<div class='alert alert-success alert-dismissible' role='alert'>
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
  <strong>Congratulation!</strong> $success.
</div>";
    return $li;
}

function error_alert($error) {
    $li = "<div class='alert alert-danger alert-dismissible' role='alert'>
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
  <strong>Error!</strong> $error.
</div>";
    return $li;
}

function upload_inner_image($soruce_path, $name, $profile_image, $dir) {
    //die($name);
    $str_path = "$dir/$name/$profile_image";
    if (!is_dir("$dir")) {
        if (!mkdir("$dir")) {
            $error = (" * failed to create folder $dir");
        }
    }

    //die($name);

    if (!is_dir("$dir/$name")) {
        if (!mkdir("$dir/$name")) {
            $error = (" * failed to create folder $dir/$name");
        }
    }

    $result = @move_uploaded_file($soruce_path, $str_path);

    if (!$result) {
        $error = (" * Faield to uplaod file");
    } else {
        $error = "";
    }
    echo $error;
}
?> 
