<?php
include("db.php");
include 'navbar.php';
$query="select * from gallery";
$result=mysqli_query($con,$query);


?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #gallery {
            display: flex;
            justify-content: center;
            margin: 40px 0;
        }

        .gallery {
            max-width: 1200px;
            width: 100%;
        }

        .gallery_container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Adjust width */
            grid-auto-rows: 250px; /* Set fixed height */
            grid-gap: 20px; /* Adjust gap between images */
            justify-items: flex-start; /* Align items to the start of each grid cell */
            align-items: flex-start;
        }

        .images {
        width: 100%;
        height: 250px;
            display: flex;
            align-items: flex-start;        
            justify-content: flex-start;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
            cursor: pointer;
        }

        .images:hover {
            transform: scale(1.02);
        }

        .images img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        div.imgshow{
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
div.imgshow div.imgx{
    background-color: white;
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    position: fixed;
    width: 70%;
    width: 70%;
    border-radius: 5px;
    padding: 20px 25px 30px 25px;
    z-index: 1600;
}
        /* .imgshow{
    position: fixed;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    display: none;
        }
        .imgx {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: fixed;
    border-radius: 5px;
        } */
        .imgx span{
            width: 100%;
        }
        .imgx button{
            border: none;
            right: 0;
            float: right;
            background-color: transparent;
            outline: none;
            font-size: 18px;
            font-weight: 550;
            color:black;
        }
        .imgshow img{
            width: 100%;
            height: 70vh;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <section id="gallery">
        <div class="gallery">
            <div class="gallery_container">
                <?php
                while($row = mysqli_fetch_array($result)) {
                ?>
                    <div class="images">
                        <img onclick="img('imgshow<?php echo $row['id']; ?>')" src="admin/<?php echo $row['gallery_path'] ?>" alt="<?php echo $row['information'] ?>">
                    </div>
                    <div class="imgshow" id="imgshow<?php echo $row['id']; ?>">
                    <div class="imgx">
                        <span>
                        <button type="reset" onclick="img('imgshow<?php echo $row['id']; ?>')">X</button> 
                        </span>
                        <img src="admin/<?php echo $row['gallery_path'] ?>" alt="<?php echo $row['information'] ?>">
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
    <script>
                function img(imgshow) {
                var get_packagesbook = document.getElementById(imgshow);
                if (get_packagesbook.style.display === "flex") {
                    get_packagesbook.style.display = "none";
                } else {
                    get_packagesbook.style.display = "flex";
                }

            }
    </script>

    <?php include 'footer.php'; ?>

</body>
</html>
