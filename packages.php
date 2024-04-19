<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");
include("navbar.php");

// Unset search criteria session variables when leaving the page
if (!isset($_POST['submit']) && !isset($_GET['sortpack'])) {
    unset($_SESSION['searchplaced']);
}

// Check if the form is submitted
if(isset($_POST['search_packages'])){
    $places = mysqli_real_escape_string($con, $_POST['search_place']);
    $duration = mysqli_real_escape_string($con, $_POST['time']);

    $_SESSION['searchplaced'] =$places;
    $_SESSION['duration'] = $duration;
}

// Handle sortpacking
$sortpack_order = isset($_GET['sortpack']) ? $_GET['sortpack'] : ''; // Get sortpacking order from URL parameter

// Store sortpacking order in session variable
if($sortpack_order === 'asc' || $sortpack_order === 'desc') {
    $_SESSION['sortpack_order'] = $sortpack_order;
}

// Fetch hotels based on the selected criteria
if(isset($_SESSION['searchplaced'])) {
    $place = $_SESSION['searchplaced'];
    $duration = $_SESSION['duration'];
    $query = "SELECT * FROM package  WHERE location='$place' AND packstatus='on' AND  duration='$duration'";
    if(isset($_SESSION['sortpack_order'])) {
        $sortpack_order = $_SESSION['sortpack_order'];
        $query .= " ORDER BY price $sortpack_order";
    }
} else {
    // Fetch all packagess
    $query = "SELECT * FROM package WHERE packstatus='on' ";
    if(isset($_SESSION['sortpack_order'])) {
        $sortpack_order = $_SESSION['sortpack_order'];
        $query .= " ORDER BY price $sortpack_order";
    }
}
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = 6;
$offset = ($page - 1) * $itemsPerPage;
$query .=" LIMIT $itemsPerPage OFFSET $offset";
$result = mysqli_query($con, $query);
?>


<head>
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
      <!-- Font Awesome CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

:root {
  --primary-color: #f13033;
  --primary-color-dark: #c3282b;
  --secondary-color: #f9f9f9;
  --text-dark: #0f172a;
  --text-light: #64748b;
  --btn:#30475e;
  --white: #ffffff;
  --hover:#00aa6c;
  --max-width: 1300px;
}
* {
margin: 0;
padding: 0;
box-sizing: border-box;
text-decoration: none;
list-style: none;
scroll-behavior: smooth;
}

/*book*/
section .packageslist{
  display: flex;
  flex-direction:column;
  max-width: 1200px;
  width: 95%;
  margin: 30px auto;
  flex-wrap: wrap;
    align-items: center;
  justify-content: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.first {
    display: flex;
    justify-content:space-between;
    align-items: center;
    margin-bottom: 20px;
    margin-top: 20px;
}

.search {
    display: flex;
    justify-content: center;
    width: 85%;
    flex-direction: row;
    align-items: center;
    margin-right:-80px;
    justify-items: center;
}

.search form {
    display: flex;
    flex-direction: row;
    gap: 3px;
    justify-items: center;
    align-items: center;
}

.search input,
.search select {
    margin: 0;
    border-radius: 18px;
    outline: none;
    background-color: lightgray;
    border: none;
    height: 50px;
    width: 210px;
    padding: 10px; /* Adjust padding as needed */
}

.search button {
    margin-left: 5px;
    border-radius: 18px;
    font-size: large;
    background-color: blue;
    color: white;
    outline: none;
    border: none;
    height: 50px;
    width: 100px;
    box-shadow: 0 3rem rgba(121,121,121,0.65rem);
    padding: 10px; /* Adjust padding as needed */
}

.search button:hover {
    filter: brightness(110%);
    background-color: var(--btn);
}
.search button:active{
    transform: translate(0,0.3rem);
}


.sortpack{
  display:flex;
  width:100%;
  max-width: 18%;
  height:40px;
  justify-content:flex-end;
}
.sortpack a {
  display: inline-block;
  text-decoration: none;
  background-color: none;
  border-radius: 8px;
  margin-left: 10px;
  margin-right:20px;
  color:black;
  padding: 3px 3px;
  line-height: 35px;
  transition: background-color 0.3s ease;
}

.sortpack a:hover {
  background-color: var(--primary-color);
  color: white;
}

main {
  max-width: 1300px;
  width: 95%;
  margin: 30px auto;
  display: flex;
  flex-wrap: wrap;
  flex-direction: row;
  justify-content: flex-start;
  align-items: flex-start;
}

.packages-list {
  width: 380px;
  text-align: center;
  height: 530px;
  margin: 20px;
  box-shadow: 2px 4px 2px 3px rgba(0, 0, 0, 0.1);
}

.packages-list .packages-img {
  height: 50%;
  margin-bottom: 10px;
}

.packages-list .packages-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.packages-list .rate {
  color: orange;
  display: flex;
  flex-direction: row;
  margin-top: 2px;
  justify-content:space-between;
  margin-right: 15px;
}
.rate h6{
    color: red;
    margin-left: 8px;
}
.rate strong {
  float: right;
  font-size: 18px;
}

.packages-list .packages-detail {
  padding-left: 1em;
  text-align: left;
  line-height: 3em;
  height: 25%;
  overflow: hidden;
}

.packages-list .packages-detail h6,
.packages-list .packages-detail p {
  font-size: 14px;
  line-height: 1.55;
  margin:0;
  padding:0;
} 
.packages-list .packages-detail button {
            margin-right: 5px;
            background-color: var(--hover);
            border: none;
            vertical-align: top;
            border-radius: 5px;
            color: var(--white);
            padding: 5px 10px;
            font-weight: bold;
            cursor: pointer;
        }

.packages-list form button {
  border: none;
  border-radius: 5px;
  padding: 1em;
  width: 80%;
  cursor: pointer;
  margin-top: 1em;
  color: white;
  background-color: blueviolet;
  font-weight: bold;
  position: relative;
}

.packages-list form button:hover {
  background-color: blue;
  color: white;
}
        
div.packagesbook_container{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row;
    background-color: rgb(0, 0,0,0.2);
    z-index: 1500;
    display: none;
}
div.packagesbook_container div.packagesbook{
    background-color: white;
    display: flex;
    flex-direction: column;
    position: fixed;
    width: 1300px;
    height: 90vh;
    border-radius: 5px;
    padding: 20px 25px 30px 25px;
    z-index: 1600;
}
div.packagesbook_container div.firstline{
    display:flex ;
    width: 100%;
    padding: 1rem 2rem 0 3rem;
    z-index: 1600;
    flex-direction: row;
}
div.packagesbook_container div.firstline h2 {
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 100%;
    justify-content: space-between;
    margin-bottom: 10px;
    color: #30475e;
}
div.packagesbook_container div.firstline h2 span{
    font-size: 28px;
    font-weight: 550;
}

div.packagesbook_container div.firstline h2 button {
    border: none;
    background-color: transparent;
    outline: none;
    font-size: 18px;
    font-weight: 550;
    color:black;
}


div.packagesbook_container div.secondline{
    display: flex;
    flex-direction: row;
    width: 100$;
}
.secondhotel_detail .imghot{
    display: flex;
    margin: 5px;
    flex-direction: row;
    flex-wrap: wrap;
    width: 100%;
}

.secondhotel_detail .imghot img{
    width: 240px;
    margin: 2px 5px;
}
.secondhotel_detail{
    width: 800px;
    display: flex;
    flex-direction: column;
}
.second_detal{
    width: 100%;
    padding: 5px 5px ;
    margin-right: 5px;
}

.second_detal p{
    font-size: 15px;
    font-family: italic ,sans-serif;
}

div.packagesbook form{
    display: flex;
    flex-direction: column;
    width: 400px;
    background-color: white;
    align-items: center;
    justify-content: center;
    margin: 15px 25px;
    border-radius: 10px;
    height: 500px;
    box-shadow:0 1rem 2rem rgba(0,0,0,0.2);
}

div.packagesbook_container div.packagesbook label{
    width: 300px;
    border: none;
    color:black;
    font-family: italic;
    border-radius: 0;
    padding: 5px 0;
    font-weight: 550;
    font-size: 14px;
    outline: none;
}

div.packagesbook_container div.packagesbook input,
div.packagesbook_container div.packagesbook select{
    width: 300px;
    margin-bottom: 20px;
    background-color: transparent;
    border: none;
    color:black;
    border-bottom: 2px solid black;
    border-radius: 0;
    padding: 5px 0;
    font-weight: 550;
    font-size: 14px;
    outline: none;
}


div.secondline button.bookedpackages_btn {
    font-weight: 550;
    width: 300px;
    padding: 14px 10px;
    font-size: 15px;
    background-color: #30475e;
    border-radius:5px;
    box-shadow: 0 3rem rgba(121,121,121,0.7rem);
    color: white;
    border: none;
    outline: none;
    margin-top: 5px;
}
div.secondline button.bookedpackages_btn:hover{
    background-color:#198754;
    color:white;
    filter: brightness(110%);
}
 div.secondline button.bookedpackages_btn:active{
    transform: translate(0, 0.3rem);
}
#prevNext button {
    margin: 10px;
    outline:none;
    height:40px;
    width:60px;
    border: 1px solid green;
    padding: 5px;
    border-radius:5px;
    cursor: pointer;
    box-shadow: 0 0.3rem rgba(121, 121, 121,0.65);
}
#prevNext button:hover{
    background-color:black;
    border:none;
    color:white;
    filter: brightness(110%);
}
#prevNext {
    margin-top: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#prevNext button:active{
    transform: translate(0,0.2rem);
    
}

@media screen and (max-width:1350px) {
    
div.packagesbook_container div.packagesbook{
    width: 90%;
}

.secondhotel_detail{
    width: 60%;
}
.secondhotel_detail .imghot img{
    width: 30%
}

div.packagesbook form{
    width: 30%;
}

div.packagesbook_container div.packagesbook label,
div.packagesbook_container div.packagesbook input,
div.packagesbook_container div.packagesbook select,
div.secondline button.bookedpackages_btn {
    width: 70%;
}
    
}

    </style>
</head>

<section class='packageslist' id="packageslist">
         <div class="first">
            <div class="search">
                <form action="" method="post">
                    <select name="search_place" id="search_place" placeholder="Select place..." required>
                        <option value="">Select place</option>
                        <option value="sauraha">Sauraha</option>
                        <option value="cg">CG Temple</option>
                        <option value="meghauli">Meghauli</option>
                        <option value="patihani">Patihani</option>
                        <option value="madi">Madi</option>
                        <option value="shivaghat">shivaghat</option>
                        <option value="narayangarh">Narayangarh</option>
                    </select>
                    <input type="text" name="time" id="time" placeholder="Enter days..." required>
                    <button type="submit" name="search_packages" class="search_packages">Search</button>
                </form>
            </div>
            <div class="sortpack">
                <a href="packages.php?sortpack=desc#packageslist">High price <i class="fas fa-arrow-up"></i></a>
                <a href="packages.php?sortpack=asc#packageslist">Low price <i class="fas fa-arrow-down"></i></a>
            </div>
        </div>
        <!-- Display packagess -->

        <main>
        <?php
        
        if(mysqli_num_rows($result) < 1) {
          echo '<div style="width:100%;font-size: 3rem; font-weight: bold; font-family: monospace; height:50vh; text-align:center;">packages not found</div>';
      } else {
          while ($row = mysqli_fetch_array($result)) {
        ?>
                <div class="packages-list">
                    <div class="packages-img">
                    <?php
                        $img = explode(",", $row['image']);
                        foreach ($img as $path) {
                        ?>                            
                          <img src="hotelowner/<?php echo $path; break; }?>" alt="">  
    
                    </div>
                    <div class="rate">
                        <?php
                        $emaill=$row['email'];
                        $qemail=mysqli_query($con,"SELECT * FROM `hotelowner` WHERE hotelowner_email='$emaill'");
                        $femail=mysqli_fetch_array($qemail);
                        ?>
                        <h6>Contact: <?php echo $femail['phone_no'] ?></h6>
                        <strong> RS <?php echo $row['price'] ?> / Person</strong>
                    </div>
                    <div class="packages-detail">
                        <h6><?php echo $row['name'] ?> <?php echo $row['duration'] ?> Days</h6>
                        <p>Location: <?php echo $row['location'] ?>, NEPAL <a href="<?php echo $row['map'] ?>">Click For Map</a></p>
                        <p>Facilities :</p>
                        <p class="facilities">
                            <?php echo $row['description']; ?>
                        </p>
                    </div>
                    <form>
                        <button type="button" name="packagesbooking" class="book_packages"onclick="packagesbook('bookpackages<?php echo $row['packages_id']; ?>')">View</button>
                    </form>
                </div>
        <div class="packagesbook_container" id="bookpackages<?php echo $row['packages_id']; ?>">
            <div class="packagesbook">
            <div class="firstline">
        <h2>
        <span><?php echo $row['name'];?></span>
            <button type="reset" onclick="packagesbook('bookpackages<?php echo $row['packages_id']; ?>')">X</button>                   
        </h2>
        </div>
        <div class="secondline">
            <div class="secondhotel_detail">
                <div class="imghot">
            <?php
                        $img = explode(",", $row['image']);
                        $count=0;
                        foreach ($img as $path) {
                            if($count >=6){
                                break;
                            }
                        ?>                            
                          <img src="hotelowner/<?php echo $path; ?>" alt="">  
                        <?php 
                        $count ++;
                    } ?>
            </div>
            <div class="second_detal">
                <p><?php echo $row['description'] ?></p>
            </div>
            </div>
                <form action="packed.php" method="POST" onsubmit="return bookpackvalidate(<?php echo $row['packages_id']; ?>)">
                    <label for="incheck">Check IN Date</label>
                <input type="date" name="incheck" id="incheck_<?php echo $row['packages_id']; ?>" required onchange="updatepackfilled(<?php echo $row['price']; ?>, <?php echo $row['packages_id']; ?>)">
                <input type="number" name="packguest" id="packguest_<?php echo $row['packages_id']; ?>" placeholder="Number of guest" required onchange="updatepackfilled(<?php echo $row['price']; ?>, <?php echo $row['packages_id']; ?>)">
                    <?php if (!isset($_SESSION['searchplaced'])) {?>
                <!-- Display input fields for search information -->
                <input type="hidden" name="packlocation" value="<?php echo $row['location']; ?>"> 
                <input type="number" name="tppackages" id="tppackages_<?php echo $row['packages_id']; ?>" placeholder="Total Price" readonly>
        
            <?php } else { ?>
                <!-- Display input fields for total price -->
                <input type="text" name="tolprice" id="tolprice_<?php echo $row['packages_id']; ?>" placeholder="Total Price" readonly>
                <?php } ?>
                    <input type="text" name="room" placeholder="Number Of Rooms" id="room_<?php echo $row['packages_id']; ?>" readonly>
                    <select name="paymentpackages" id="paymentpackages_<?php echo $row['packages_id']; ?>"  aria-placeholder="select payment" required onchange="updatepackfilled(<?php echo $row['price']; ?>, <?php echo $row['packages_id']; ?>)">
                        <option value="">Select payment</option>
                        <option value="cash">cash</option>
                        <option value="esewa">esewa</option>
                        <option value="card">card</option>
                    </select> 
                    <input type="hidden" name="packname" value="<?php echo $row['packages_id']; ?>">
                    <input type="hidden" name="hotelid" value="<?php echo $row['hotel_id']; ?>">
                    <input type="hidden" name="packdu" value="<?php echo $row['duration']; ?>">   
                    <button type="submit" name="bookedpackages" class="bookedpackages_btn">BOOK</button>
                   
                </form>
            </div>
        </div>
        </div>
        <script>
            function updatepackfilled(price, packagesId) {
                var guests = document.getElementById('packguest_' + packagesId).value;
                var pay =document.getElementById('paymentpackages_' + packagesId).value;

                // Check if all input fields are filled
                if (guests && pay) {
                    updateprice(price, packagesId);
                }
            }

            function bookpackvalidate(packagesid) {
                var incheckAD = document.getElementById('incheck_'+ packagesid).value;
                var guests = parseInt(document.getElementById('packguest_'+ packagesid).value);
                var pay =document.getElementById('paymentpackages_'+ packagesid).value;
                var today = new Date();
                console.log('guest:', guests);

                if (isNaN(guests) || guests <= 0) {
                    alert("Please enter a valid number of guests .");
                    return false;
                } else if (new Date(incheckAD) < today) {
                    alert('Check-in date must be equal to or later than today.');
                    return false;
                }
                else if(pay == "")
                {
                    alert('Please select payment method.');
                    return false;
                }
                return true; // Allow form submission
            }

        </script>
                <?php
            }
          }
        
        ?>
    </main>
    <div id="prevNext">
        <button id="prevBtn">Prev</button>
        <button id="nextBtn">Next</button>
    </div>
</section>
<script>
    $(document).ready(function(){
            var page = <?php echo $page; ?>;
            
            var totalPages = <?php echo ceil(mysqli_num_rows(mysqli_query($con,"SELECT * FROM package ")) / $itemsPerPage); ?>;

            $('#prevBtn').on('click', function(){
                if (page > 1) {
                    window.location.href = '?page=' + (page - 1);
                }
            });

            $('#nextBtn').on('click', function(){
                if (page < totalPages) {
                    window.location.href = '?page=' + (page + 1);
                }
            });
         }); 
        function packagesbook(packagesbook_name) {
                var get_packagesbook = document.getElementById(packagesbook_name);
                if (get_packagesbook.style.display === "flex") {
                    get_packagesbook.style.display = "none";
                } else {
                    get_packagesbook.style.display = "flex";
                }

            }
    
    function updateprice(price, packagesId) {
        var guestp = parseInt(document.getElementById('packguest_' + packagesId).value);
        var room =Math.ceil(guestp/2);
            document.getElementById('room_' + packagesId).value =room;
        console.log('price:', price);

        if (!isNaN(guestp) && guestp >= 0) {
            if (document.getElementById('tppackages_' + packagesId)) {
            document.getElementById('tppackages_' + packagesId).value = guestp * price;
            }else{
            document.getElementById('tolprice_' + packagesId).value = guestp * price;
            }
        }
         else {
            alert("Please enter a valid number of guest.");
        }
    }


</script>

<?php
include("footer.php");

?>