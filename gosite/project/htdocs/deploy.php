<?php
include 'libs/load.php';

if (UserSession::authorize($_SESSION['session_token'])) {


    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    $domain= "none";


    $baseDir = __DIR__ . "/../site/";
    // $File = $baseDir . basename($_FILES['file']['name']);
    $File = $File = $_POST['git'];
    $upload = new Upload($baseDir,$File);


    try {
        
// check for the custom domain 
        if (($_POST['domainType'] == 'custom')) {
            // $serverIp = "192.168.1.100";
            $domain = $_POST['domain'];
            if ($upload->isValidDomain($domain)) {
                if (!$upload->isDomainPointedToServer($domain, $serverIp)) {
                    throw new Exception("The domain is not pointed to your server.");
                }
            } else {
                throw new Exception("Invalid custom domain");
            }
        }

// Check user fill the upload or empty
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $File = $baseDir . basename($_FILES['file']['name']);
        } elseif (isset($_POST['git']) && !empty($_POST['git'])) {
            if ($upload->isValidGitLink($_POST['git'])) {
                $File = $_POST['git'];
                echo "Valid Git link!";
            } else {
                $errorMessage = "Invalid Git link!";
                header("Location: /dashboard.php?error=$errorMessage");
                exit();
            }
        } else {
            throw new Exception("enter git link or upload files");
        }


    } catch (Exception $e) {
        echo $e->getMessage();
        $errorMessage = urlencode($e->getMessage());
        header("Location: /dashboard.php?error=$errorMessage");
        exit();
    }

    // $upload = new Upload($baseDir, $File);



// Check for the index file after cloning of repo or unzip of file,
    function scripts($workdone, $domain, $newDirName)
    {

        //run python script to find path to index file
        $index_path = shell_exec("python3 scripts/script.py ../site/" . $domain);

        // execute this if the index file is not in the user's project 
        if ($index_path == 0) {
            if (file_exists($newDirName)) {
                conf::deleteFolder($domain);
                $errorMessage = "fail to find index in your project";
                header("Location: /dashboard.php?error=$errorMessage");
                exit();
            }
        }

        //create a conf file in sites-available
        // $name = 'test';

        print ("<br>index path print : " . $index_path);
        Conf::changeapacheConfig($domain, $index_path);
        //enable the site
        Conf::enableSite($domain);
        Conf::reloadApache();
        // -----------------------------------------------------------------------------------------------
        $plan_id = $_POST['plan_id'];
        $plan_name = $_POST['plan_name'];
        $File = $_POST['git'];
        print ($workdone);
        if ($workdone >= 3) {

            echo "Work done successfully!\n<br>";
            Database::getConnection();
            if (Purchase::setdetails($domain, $plan_id, $plan_name, $index_path, $File)) {
                echo "Domain details set successfully!\n<br>";
            } else {
                throw new Exception("Failed to set domain details!");
                // echo "Failed to set domain details!\n<br>";
            }

        } elseif ($workdone <= 2) {
            throw new Exception("Workdone partially success");
            // echo "Work done partially!\n<br>";
        } else {
            throw new Exception("Workdone faild");
            // echo "Work done failed!\n<br>";
        }
    }


    try {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($domain == "none") {
                $domain = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['domain']);
                $domain = $domain . ".gosite.in";
                print ("from line 159");
            }
            if (empty($domain)) {
                throw new Exception("Invalid domain name !");
                // die("Invalid domain name.<br>");
            }
            $newDirName = $baseDir . $domain;
            // print($newDirName);

            // echo " <br  > wwwwwww <br>";
            // Check if the directory already exists
            if (file_exists($newDirName)) {
                throw new Exception("Error: Subdomain already exists");
                // die("Error: Subdomain already exists.<br>");
            } else {
                echo " <br>  valid domain <br>";
            }

            $newDirName = $baseDir . $domain;

            if ($upload->isFileUploaded($_FILES['file'])) {

                $upload->handleFileUpload($File);
                $workdone = $upload->extractZipFile($File, $baseDir, $newDirName);
                scripts($workdone, $domain, $newDirName);
            } elseif (isset($_POST['git'])) {


                $gitt = $upload->gitclone($domain);
                $workdone = 3;
                scripts($workdone, $domain, $newDirName);
                if ($_POST['domainType'] == 'custom') {
                    Conf::confssl($domain);
                }

            } else {
                // $gitt = $upload->gitclone($domain);
                throw new Exception("File not uploaded!");
                // die("File not uploaded!");
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        $errorMessage = urlencode($e->getMessage());
        header("Location: /dashboard.php?error=$errorMessage");
        exit();
    }



    Conf::reloadApache();


    header("Location: dashboard.php?manage");
    $_SESSION['message'] = "Your site is successfully hosted on " . $domain . ".gosite.in";
    exit;

}