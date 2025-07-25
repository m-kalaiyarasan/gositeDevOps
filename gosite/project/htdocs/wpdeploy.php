<?php

include 'libs/load.php';

echo "<pre>";
print_r($_POST);
echo "</pre>";
$domain = $_POST['domain'].".gosite.in";

try {
    if (UserSession::authorize($_SESSION['session_token'])) {




        $username = $_SESSION['session_user'];

        $wp = new Wordpress();
        $user_id = $username."_".rand(100, 200);

        // $port = $wp->findAvailablePort();
        $lastPort = $wp->findLastPort();
        $port = $lastPort + 1;
        $user_id = $username."_".$port;


        $create = $wp->setupWordPress($user_id, $port);
        $wp->updatePortCount($port);

        // print($port);
        echo"<pre>";
        print_r($create);
        echo"<pre>";
        // print($port+1);

        $wp->ApacheConfig($domain, $port);

        $plan_id = $_POST['plan_id'];
        $plan_name = $_POST['plan_name'];
        $index_path = $create['base_dir'];
        $File    = $user_id;

        Database::getConnection();
        if (Purchase::setdetails($domain, $plan_id, $plan_name, $index_path, $File)) {
            echo "Domain details set successfully!\n<br>";
        } else {
            throw new Exception("Failed to set domain details!");
            // echo "Failed to set domain details!\n<br>";
        }
    }

    Conf::reloadApache();
} catch (Exception $e) {
    echo $e->getMessage();
    $errorMessage = urlencode($e->getMessage());
    header("Location: /dashboard.php?error=$errorMessage");
    exit();
}

header("Location: dashboard.php?manage");
$_SESSION['message'] = "Your site is successfully hosted on " . $domain . ".gosite.in";
exit;
