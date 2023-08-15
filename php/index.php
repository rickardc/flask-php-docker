<?php
    $rooms = $_POST['rooms'];
    $distance = $_POST['distance'];
    $type_h = 0;
    $type_t = 0;

    if ($_POST['type'] == "house") {
        $type_h = 1;
    } elseif ($_POST['type'] == "townhouse") {
        $type_t = 1;
    }

    // data to json
    $data = array('Rooms' => $rooms, 'Distance' => $distance, 'Type_h' => $type_h, 'Type_t' => $type_t);
    $data_json = json_encode($data);

    // connect to flask python container
    $url = "python:8000/predict";

    // curl request
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_json)
    ));

    // curl response
    $resp = curl_exec($curl);
    curl_close($curl);

?>

<DOCTYPE html>
<html>
<head>
    <title>Melbourne House Price Prediction</title>
</head>
<body>
    <h1>Melbourne House Price Prediction</h1>

    <form action="index.php" method="post">
        <label for="rooms">Rooms: </label>
        <input type="text" name="rooms" placeholder="Number of rooms">
        <br>
        <label for="distance">Distance: </label>
        <input type="text" name="distance" placeholder="Distance from CBD (km)">
        <br>
        <label for="type">Type: </label>
        <input type="radio" name="type" value="house">House
        <input type="radio" name="type" value="townhouse">Townhouse
        <input type="radio" name="type" value="unit">Unit
        <br>
        <br>
        <input type="submit" name="submit" value="Get Price">
    </form>
    <br>

    <?php
        echo "Input: ".$data_json."<br>";
        $result = json_decode($resp, true);
        $price = $result["Predicted Price of House"];
        echo 'Output: ';
        print_r($result);
        echo "<br>";
        setlocale(LC_MONETARY, "en_US.UTF-8");
        echo '<p>Predicted Price: $'.number_format($price, 2,".",",").'</p>';
        
    ?>

</body>
</html>
