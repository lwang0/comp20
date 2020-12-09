<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Jade Delight Order Summary</title>
</head>
<body>
    <h1>Thank you for your order!</h1>
    <?php
    $order = "<p>You got:<br> $_REQUEST[quan0] Chicken Chop Suey for $$_REQUEST[cost0]<br>
    $_REQUEST[quan1] Sweet and Sour Pork for $$_REQUEST[cost1]<br>
    $_REQUEST[quan2] Shrimp Lo Mein for $$_REQUEST[cost2]<br>
    $_REQUEST[quan3] Moo Shi Chicken for $$_REQUEST[cost3]<br>
    $_REQUEST[quan4] Fried Rice for $$_REQUEST[cost4]</p>".
    "<p>Your subtotal was: $".number_format((float)$_REQUEST['subtotal'], 2, '.', '')
    ."<br>Your tax was: $".number_format((float)$_REQUEST['tax'], 2, '.', '')
    ."<br>Your total was: $".number_format((float)$_REQUEST['total'], 2, '.', '')."</p>";
    echo $order;
    $date = new DateTime();
    $datestring = "";
    if ($_REQUEST['p_or_d'] == 'delivery') {
        $date->modify("+30 minutes");
        $datestring = "<p>Delivery time: ".$date->format('Y-m-d H:i:s')."</p>";
        echo $datestring;
    }
    else {
        $date->modify("+15 minutes");
        $datestring = "<p>Pickup time: ".$date->format('Y-m-d H:i:s')."</p>";
        echo $datestring;
    }
     $header = "MIME-Version: 1.0\r\n";
     $header .= "Content-type: text/html\r\n; charset=UTF-8\r\n";
    mail($_REQUEST['email'], "Thank you for your order!", "<html><body>".$order.$datestring."</body></html>", $header);
    echo "You have been sent an email at $_REQUEST[email] with your order details";
    ?>
</body>
