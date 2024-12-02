<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_GET['country']) && !empty($_GET['country'])) {
    $country = htmlspecialchars($_GET['country']); 

    // Check if the 'lookup' parameter is set to 'cities'
    if (isset($_GET['lookup']) && $_GET['lookup'] == 'cities') {
        $stmt = $conn->prepare("
            SELECT cities.name AS city_name, cities.district, cities.population 
            FROM cities 
            JOIN countries ON cities.country_code = countries.code
            WHERE countries.name LIKE :country
        ");
        $stmt->bindValue(':country', '%' . $country . '%', PDO::PARAM_STR);
        try {
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Make results output in a table
            if ($results) {
                echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>
                        <thead>
                            <tr>
                                <th>City Name</th>
                                <th>District</th>
                                <th>Population</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($results as $row) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['city_name']) . "</td>
                            <td>" . htmlspecialchars($row['district']) . "</td>
                            <td>" . htmlspecialchars($row['population']) . "</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No cities found for the country: " . htmlspecialchars($country) . ".</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error retrieving cities: " . $e->getMessage() . "</p>";
        }
    } else {
        // The default query to fetch the country information
        $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
        $stmt->bindValue(':country', '%' . $country . '%', PDO::PARAM_STR);
        try {
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>
                        <thead>
                            <tr>
                                <th>Country Name</th>
                                <th>Continent</th>
                                <th>Independence Year</th>
                                <th>Head of State</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($results as $row) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['continent']) . "</td>
                            <td>" . htmlspecialchars($row['independence_year']) . "</td>
                            <td>" . htmlspecialchars($row['head_of_state']) . "</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No results found for the country: " . htmlspecialchars($country) . ".</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error retrieving data: " . $e->getMessage() . "</p>";
        }
    }
} else {
    echo "<p>Please provide a country name in the query parameter (e.g., ?country=Jamaica).</p>";
}
?>



