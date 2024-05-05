<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search</title>
</head>
<body>
    <form id="search-form">
        
        <input type="text" name="search" id="search" autocomplete="off" placeholder="search">
    </form>
    <div id="search-results">
        
    </div>

    <script>
        
        // attach event listener to search form
        document.getElementById('search-form').addEventListener('submit', function(event) {
            event.preventDefault();

            // get search query
            var search = document.getElementById('search').value;

            // send AJAX request to server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'search.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // update search results
                    document.getElementById('search-results').innerHTML = xhr.responseText;
                }
            };
            xhr.send('search=' + search);
        });

        // attach event listener to search input field
        document.getElementById('search').addEventListener('input', function() {
            // get search query
            var search = document.getElementById('search').value;

            // send AJAX request to server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'search.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // update search results
                    document.getElementById('search-results').innerHTML = xhr.responseText;
                }
            };
            xhr.send('search=' + search);
        });
    </script>
    <?php
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $products = array(
        array(
            'name' => 'doglapan', 'price' => 4999,
            "category" =>  "book",
            "image" => "imagesq.jpg"
        ),
        array(
            'name' => 'samsung', 'price' => 39999,
            "category" =>  "mobile",
            "image" => "images.jpg"
        ),
        array(
            'name' => 'iqoo', 'price' => 29999,
            "category" =>  "mobile",
            "image" => "iqoo.jpg"
        ),
        array(
            'name' => 'reebok', 'price' =>  1999,
            "category" =>  "cloth",
            "image" => "rebok.jpg"
        ),

    );

    echo "<h2>Search Results:</h2>
            <table border='1'>                                                                          
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Image</th>

                </tr>";
    foreach ($products as $product) {
        if (stripos($product['name'], $search)!== false || stripos($product['category'], $search)!== false || stripos($product['price'], $search)!== false || stripos($product['image'], $search)!== false) {
            echo "<tr>
                                <td>". $product['name']. "</td>
                                <td>". $product['price']. "</td>
                                <td>". $product['category']. "</td>
                                <td><img src='". $product['image']. "' alt='Product Image' width='100'></td>

                             </tr>";
        }
    }

    echo "</table>";

    $servername = "localhost";
$username = "root";
$password = "";
$dbname = "product";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
}

require_once 'search.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT name, price, category, image FROM products WHERE name LIKE ? OR category LIKE ?");
    $stmt->bind_param("ssss", "$search", "search","$search","$search", $_POST['name'],
    $_POST['price'],
    $_POST['category'],
    $_POST['image'],);
    $stmt->execute();
    $result = $stmt->get_result();

    // Output the search results
    echo "<h2>Search Results:</h2>
            <table border='1'>                                                                          
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Image</th>

                </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                                <td>" . htmlspecialchars($row['name']). "</td>
                                <td>" . htmlspecialchars($row['price']). "</td>
                                <td>" . htmlspecialchars($row['category']). "</td>
                                <td><img src='" . htmlspecialchars($row['image']). "' alt='Product Image' width='100'></td>

                             </tr>";
    }
    echo "</table>";

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

?>
</body>
</html>