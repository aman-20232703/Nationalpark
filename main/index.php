<?php
session_start();

if (isset($_SESSION['message'])) {
    echo "<script>alert('".$_SESSION['message']."');</script>";
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExploreScape</title>

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <nav>
            <a class="nav-items active">Home</a>
            <a href="../home/home.php">Explore</a>
            <a class="nav-items" href="../gallery/gallery.php">Gallery</a>
            <a class="nav-items" href="../review/review.php">Reviews</a>
            <a class="nav-items" href="../contact/contact.php">Contacts</a>
        </nav>
    </header>

    <div class="content">
        <img src="../main/bac 3.png" alt="background" class="back-3">

        <div class="title">
            <h3>The Land Of Scerene Beauty</h3>
            <h1>INDIA</h1>
        </div>

        <img src="../main/bac 2.2.png" alt="background" class="back-2">
        <img src="../main/bac 6.png" alt="background" class="back-1">

        <div class="info-wrap">
            <p>
                India is home to one of the richest networks of national parks in the world, protecting diverse
                landscapes that range from the Himalayan peaks to the coastal mangroves. These parks are sanctuaries for
                tigers, elephants, rhinos, lions, and countless species of birds, reptiles, and plants. Visiting them
                offers not just a chance to witness incredible wildlife, but also to experience the cultural and natural
                heritage of the country.
            </p>
        </div>


        <div class="cta">
            <button>
                <a href="../sinup/sinup.php">Register</a>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

</body>

</html>