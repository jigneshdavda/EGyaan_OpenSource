<?php
include("../../../Resources/sessions.php");

require_once("../../../classes/DBConnect.php");
require_once("../../../classes/Constants.php");
require_once("../classes/User.php");
require_once("../../manage_role/classes/Role.php");

$dbConnect = new DBConnect(Constants::SERVER_NAME,
    Constants::DB_USERNAME,
    Constants::DB_PASSWORD,
    Constants::DB_NAME);

$role=new Role($dbConnect->getInstance());
$user=new User($dbConnect->getInstance());
?>

<html>
<head>
    <?php
    include "../../../Resources/Dashboard/header.php"
    ?>
<title>Manage Users | EGyaan</title>

<script src="../../../Resources/jquery.min.js"></script>
<script>
		
		$(document).ready(function(){
			$("#submit").click(function(){
				var name=$("#name").val();
				var email=$("#email").val();
				var mobile=$("#mobile").val();
				var role_id=$("#role_id").val();

				if(name=="" || email=="" || mobile=="" || role_id==-1)
				{
                    var alert_icon = document.createElement('i');
                    alert_icon.setAttribute('class', 'fa fa-exclamation-triangle');
                    $("#user_err").html(alert_icon).append("&nbsp;Please input all the fields!");

					return false;
				}
				else
				{
					$("#user_err").text("");
				}
			});
		});
</script>

</head>
<body>
    
    
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <br>
            <ol class="breadcrumb">
                <li><a href="../../login/functions/Dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active"><b>Manage Users</b></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Manage User</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="role" action=add_user.php method=post>
                                        <div class="form-group">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Full Name">
                                        </div>
                                        <div class="form-group">
                                        <label>Gender :</label><br>
                                            <label><input type="radio" class="flat" name="gender" id="gender_M" value="M" checked="checked">&nbsp;Male</label>
                                            <label><input type="radio" class="flat" name="gender" id="gender_F" value="F">&nbsp;Female</label>
                                        </div>
                                        <div class="form-group">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email ID">
                                        </div>
                                        <div class="form-group">
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile Number">
                                        </div>
                                        <div class="form-group">
                                        <select name="role_id" class="form-control select2" id="role_id">
                                        <option value="-1" selected disabled>Select Role</option>
                                        <?php
                                            $getRoles=$role->getRole();

                                            if($getRoles!=null)
                                            {
                                                while($row=$getRoles->fetch_assoc())
                                                {
                                                    $id=$row['id'];
                                                    $role_name=$row['name'];

                                                    echo "<option value='$id'>$role_name</option>";
                                                }
                                            }
                                        ?>
                                        </select>
                                        </div>
                                        <button type="submit" class="btn btn-success" value="Add Student" id="submit"><i class="fa fa-plus"></i>&nbsp;Add</button>
                                        <div class="alert-message" id="user_err"></div>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                        $getUsers=$user->getUser(0);
                                        $i=0;

                                        if($getUsers!=null)
                                        {
                                            echo "<div class='box-header'>
                                                        <h3 class='box-title'>Users:</h3>
                                                    </div>";
                                            echo "<div class='table-container1'><table class='table table-bordered table-hover example2'>
                                <thead>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Role</th>
                                    <th>Assign Courses</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </thead>
                                <tbody>";
                                            while($row=$getUsers->fetch_assoc())
                                            {
                                                $i++;
                                                $user_id=$row['id'];
                                                $name=$row['name'];
                                                $gender=$row['gender'];
                                                if($gender=="M")
                                                {	
                                                    $gender="Male";
                                                }
                                                else
                                                {
                                                    $gender="Female";
                                                }
                                                $email=$row['email'];
                                                $mobile=$row['mobile'];
                                                $role_id=$row['role_id'];

                                                $getRole=$role->getRole();
                                                if($getRole!=null)
                                                {
                                                    while($roleRow=$getRole->fetch_assoc())
                                                    {
                                                        $id=$roleRow['id'];
                                                        if($id==$role_id)
                                                        {
                                                            $role_name=$roleRow['name'];
                                                            break;
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    $role_name="No role";
                                                }

                                                echo "<tr>";

                                                echo "<td>";
                                                echo $i;
                                                echo "</td>";

                                                echo "<td>";
                                                echo $name;
                                                echo "</td>";

                                                echo "<td>";
                                                echo $gender;
                                                echo "</td>";

                                                echo "<td>";
                                                echo $email;
                                                echo "</td>";

                                                echo "<td>";
                                                echo $mobile;
                                                echo "</td>";

                                                echo "<td>";
                                                echo $role_name;
                                                echo "</td>";

                                                echo "<td>";
                                                if($role_id==Constants::ROLE_TEACHER_ID)
                                                {
                                                    echo "<form role='form' action=../../manage_teacher_course/functions/assign_course.php method=get><button class='btn btn-default btn-sm' type=submit name=user_id value='$user_id'>&nbsp;Assign</button></form>";
                                                }
                                                else
                                                {
                                                    echo "We can assign a course only to a Teacher";
                                                }
                                                echo "</td>";

                                                echo "<td>";
                                                echo "<form role='form' action=edit_user.php method=post><button class='btn btn-primary btn-sm' type=submit name=edit value='$user_id'><i class='fa fa-pencil'></i>&nbsp;Edit</button></form>";
                                                echo "</td>";

                                                echo "<td>";
                                                echo "<form action=delete_user.php method=post><button class='btn btn-danger btn-sm' type=submit name=delete value='$user_id'><i class='fa fa-trash'></i>&nbsp;Delete</button></form>";
                                                echo "</td>";

                                                echo "</tr>";
                                            }

                                            echo "</tbody>
                                </table></div>";
                                        }
                                        else
                                        {
                                            echo "No users added yet";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php
include "../../../Resources/Dashboard/footer.php"
?>
</body>
</html>