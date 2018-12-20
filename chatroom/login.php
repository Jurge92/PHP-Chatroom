<?php

// if name is not in the post data, exit
if (!isset($_POST["name"])) {
    header("Location: error.html");
    exit;
}

require_once('xmlHandler.php');

// create the chatroom xml file handler
$xmlh = new xmlHandler("chatroom.xml");
if (!$xmlh->fileExist()) {
    header("Location: error.html");
    exit;
}


 define("UPLOAD_DIR", "images/");

if (!empty($_FILES["userfile"])) {
    $myFile = $_FILES["userfile"];


    // ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

    // don't overwrite an existing file
    $i = 0;
    $parts = pathinfo($name);
    while (file_exists(UPLOAD_DIR . $name)) {
        $i++;
        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
    }

    // preserve file from temporary directory
   // $success = move_uploaded_file($myFile["tmp_name"],
    //    UPLOAD_DIR . $name);
   // if (!$success) { 
     //   echo "<p>Unable to save file.</p>";
      //  exit;
   // }

    $temp = explode(".", $_FILES["userfile"]["name"]);
$newfilename = $_POST["name"] . '.' . end($temp);
move_uploaded_file($_FILES["userfile"]["tmp_name"], "images/" . $newfilename);

    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 0644);


}

// open the existing XML file
$xmlh->openFile();

// get the 'users' element
$users_element = $xmlh->getElement("users");

// create a 'user' element
$user_element = $xmlh->addElement($users_element, "user");

// add the user name
$xmlh->setAttribute($user_element, "name", $_POST["name"]);

$xmlh->setAttribute($user_element, "picture", $newfilename);

// save the XML file
$xmlh->saveFile();

// set the name to the cookie
setcookie("name", $_POST["name"]);

// Cookie done, redirect to client.php (to avoid reloading of page from the client)
header("Location: client.php");

?>
