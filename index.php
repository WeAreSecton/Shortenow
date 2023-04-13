<?php
// Check if the form has been submitted
if (isset($_POST['url'])) {
    // Load the URLs from the JSON file
    $urls = [];
    $filename = 'urls.json';

    if (file_exists($filename)) {
        $urls = json_decode(file_get_contents($filename), true);
    }

    // Generate a random string for the short URL
    $random_string = generate_random_string();

    // Add the URL to the list of URLs
    $urls[$random_string] = $_POST['url'];

    // Save the URLs to the JSON file
    file_put_contents($filename, json_encode($urls));

    // Display the short URL to the user
    $short_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?link=$random_string";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>URL Shortener</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/css/bootstrap.min.css">
</head>

<body>
    <style>
        body {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            animation-name: zoom-out;
            animation-duration: 0.5s;
        }



        @keyframes zoom-out {
            0% {
                transform: scale(2);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Hero section -->
    <div class="bg-light p-5 text-center">
        <h1 class="display-4">URL Shortener</h1>
        <p class="lead">Shorten your long URLs instantly and easily.</p>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Shorten URL form -->
                <form action="index.php" method="post" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="url" class="form-control" placeholder="Enter a URL">
                        <button type="submit" class="btn btn-primary">Shorten</button>
                    </div>
                </form>

                <?php
                // Display the short URL if it exists
                if (isset($short_url)) {
                    echo "<div class='alert alert-success'>Short URL: <a href='$short_url'>$short_url</a></div>";
                }

                // Check if the link parameter is set
                if (isset($_GET['link'])) {
                    // Load the URLs from the JSON file
                    $urls = [];
                    $filename = 'urls.json';

                    if (file_exists($filename)) {
                        $urls = json_decode(file_get_contents($filename), true);
                    }

                    // Check if the link parameter exists in the list of URLs
                    if (array_key_exists($_GET['link'], $urls)) {
                        // Redirect the user to the original URL
                        header("Location: " . $urls[$_GET['link']]);
                        exit();
                    } else {
                        // Show an error message if the link parameter is invalid
                        echo "<div class='alert alert-danger'>Invalid short URL</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<?php
function generate_random_string($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_string = '';

    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $random_string;
}
?>
