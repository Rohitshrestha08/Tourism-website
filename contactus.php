
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");
if(isset($_POST["contact"])){
    if(isset($_SESSION['username']) && $_SESSION['logged_in'] == true){
        
        $sessionEmail = $_SESSION['email'];
        $sessionPhone = $_SESSION['phone'];
        $contactEmail = $_POST['contact_email'];
        $contactPhone = $_POST['contact_phone'];
        
        $info = mysqli_query($con, "SELECT * FROM users WHERE email='$contactEmail' ");        
            $row = mysqli_fetch_array($info);
            $name = $row["fullname"];
            $fname = strtok($name, " ");
            $lname = substr($name, strlen($fname)+1);    
            
        
        $message = mysqli_real_escape_string($con, $_POST['message_contact']);
        if ($sessionEmail == $contactEmail && $sessionPhone == $contactPhone) {
            $query = "INSERT INTO `contactus` (`user_id`,`firstname`, `lastname`, `email`, `phone`, `message`) 
                      VALUES ('$_SESSION[user_id]','$fname','$lname', '$_SESSION[email]', '$_SESSION[phone]', '$message')";
            if (mysqli_query($con, $query)) {
                echo "
                    <script>
                        alert('Message sent successfully.');
                        window.location.href='main.php';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('please try later ..');
                        window.location.href='main.php';
                    </script>
                ";
            }
        } else {
            echo "
                <script>
                    alert('Invalid email address or phone number.');
                    window.location.href='main.php';
                </script>
            ";
        }
    } else {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email=$_POST['contact_email'];
        $phone=$_POST['contact_phone'];
        $message = $_POST['message_contact'];

        $info=mysqli_query($con,"select * from users where email='$email'");
        
        $info1=mysqli_query($con,"select * from users where phone='$phone'");
        if (mysqli_num_rows($info) > 0) {
            $r2 = mysqli_fetch_array($info);
            $name = $r2["fullname"];

            $fname = strtok($r2["fullname"], " ");
            $lname = substr($r2["fullname"], strlen($fname) + 1);
            $phone1 = $r2['phone'];
            $user_id=$r2['id'];        
            
        $query="INSERT INTO `contactus`( `user_id`,`firstname`, `lastname`, `email`, `phone`, `message`) 
        VALUES ('$user_id','$fname','$lname','$email','$phone1','$message')";
        if(mysqli_query($con,$query))
        {
            echo"
            <script>alert('Message send successful.');
            window.location.href='main.php';
            </script>
            ";
        }
        else{
            echo"
            <script>alert('Message couldnot send.');
            window.location.href='main.php';
            </script>
            ";
        }
    }elseif(mysqli_num_rows($info1) > 0) {
            $r1 = mysqli_fetch_array($info1);
            $name = $r1["fullname"];
            $ne = $r1["email"];
            $user_id=$r2['id'];   

            $fname = strtok($r1["fullname"], " ");
            $lname = substr($r1["fullname"], strlen($fname) + 1);
            $user_id=$r1['id'];   
        $quey="INSERT INTO `contactus`( `firstname`,`user_id`, `lastname`, `email`, `phone`, `message`) 
        VALUES ('$fname','$user_id','$lname','$ne','$phone','$message')";
        if(mysqli_query($con,$quey))
        {
            echo"
            <script>alert('Message send successful.');
            window.location.href='main.php';
            </script>
            ";
        }
        else{
            echo"
            <script>alert('Message couldnot send.');
            window.location.href='main.php';
            </script>
            ";
        }
    }else{
        $qury="INSERT INTO `contactus`( `firstname`, `lastname`, `email`, `phone`, `message`) 
        VALUES ('$firstname','$lastname','$email','$phone','$message')";
        if(mysqli_query($con,$qury))
        {
            echo"
            <script>alert('Message send successful.');
            window.location.href='main.php';
            </script>
            ";
        }
        else{
            echo"
            <script>alert('Message couldnot send.');
            window.location.href='main.php';
            </script>
            ";
        }
    }
    }
    }
  


?>



<link rel="stylesheet" href="contactus.css">
<section class="contactus" id="contactus">
    <h1>Contact us</h1>
 <div class="container_contact">
    <div class="form_contact">
        <form action="" method="post">
            <h1>Send message</h1>
            <div class="input-contact">
                <input type="text" class="input_box_contact" id="firstname" name="firstname" placeholder="First name" required>
                <input type="text" class="input_box_contact" id="lastname" name="lastname" placeholder="Last name" required>
            </div>
            <div class="input-contact">
                <input type="text" class="input_box_contact" id="contact_email" name="contact_email" placeholder="example@gmail.com" required>
                <input type="text" class="input_box_contact" id="contact_phone" name="contact_phone" placeholder="9801234567" maxlength="10" required>
            </div>
            <textarea id="message_contact" name="message_contact" class="message_contact" placeholder="Type your message"></textarea>
            <input type="submit" class="contactbtn" name="contact" value="Send">
        </form>
    </div>
    <div class="contactinfo">
        <div class="info">
           <img src="images/about.jpg">
        </div>
    </div>
</div>
</section>
