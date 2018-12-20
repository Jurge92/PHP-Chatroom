<?php

// get the name from cookie
$name = "";
if (isset($_COOKIE["name"])) {
    $name = $_COOKIE["name"];
}

$page = $_SERVER['PHP_SELF'];
$sec = "3";
header("Refresh: $sec; url=$page");

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Online Users</title>
    <script language="javascript" type="text/javascript">
        //<![CDATA[

        var loadTimer = null;
        var request;
        var datasize;
        var lastMsgID;

        function load() {
            var username = document.getElementById("users");

            if (username == "") {
                loadTimer = setTimeout("load()", 100);
                return;
            }

            loadTimer = null;
            datasize = 0;
            lastMsgID = 0;


            getUpdate();
        }

        function unload() {
            var username = document.getElementById("users");
            if (username.value != "") {
                //request = new ActiveXObject("Microsoft.XMLHTTP");
                request = new XMLHttpRequest();
                request.open("POST", "logout.php", true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send(null);
                username.value = "";
            }
            if (loadTimer != null) {
                loadTimer = null;
                clearTimeout("load()", 100);
            }
        }

        function getUpdate() {
            //    console.log("state")
            //request = new ActiveXObject("Microsoft.XMLHTTP");

            // Create HTTP request
            request = new XMLHttpRequest();

            //Set the callback function for this request
            request.onreadystatechange = stateChange;

            // Set the URL of the file using the POST method
            request.open("POST", "server.php", true);

            // Set the request header
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            // Send the request with the POST data 'datasize=0'
            request.send("datasize=" + datasize);
        }

        function stateChange() {

            var xmlDoc;
            try {
                xmlDoc = new XMLHttpRequest();
                xmlDoc.loadXML(request.responseText);
            } catch (e) {
                var parser = new DOMParser();
                xmlDoc = parser.parseFromString(request.responseText, "text/xml");
            }
            datasize = request.responseText.length;

            getOnlineUsers(xmlDoc);


        }

        var count = 0;
        function getOnlineUsers(xmlDoc) {
            count++;
            // console.log("Teller = " +count)
            if (count != 4) {
                return;
            }
            //point to the message nodes
            var messages = xmlDoc.getElementsByTagName("user");
            var table = document.getElementById("onlineUserns");
            //console.log(messages.item(1))
            // console.log(messages)

            // var row = table.insertRow(0);
            //var cell1 = row.insertCell(0);
            //var cell2 = row.insertCell(1);


            for (var i = 0; i < messages.length; i++) {
                // console.log(i)
                //row = table.insertRow(0);
                //cell1 = row.insertCell(0); 
                //cell2 = row.insertCell(1);

                var parenttbl = document.getElementById("userListTable");
                var photo = document.createElement('td');
                var user = document.createElement('td');
                var newLine = document.createElement('tr');
                var elementid = document.getElementsByTagName("td").length
                photo.setAttribute('id', elementid);


                //if (messages.item(i).getAttribute("picture").indexOf(".jpg") > -1) {
                var bilde = show_image("images/" + messages.item(i).getAttribute("picture"), 50, 50)
                photo.appendChild(bilde)

                // show_image("images/"+messages.item(i).getAttribute("picture"), 50, 50)
                // }else{
                //photo.innerHTML = "No photo"
                // }
                user.innerHTML = messages.item(i).getAttribute("name");
                parenttbl.appendChild(photo);
                parenttbl.appendChild(user);
                parenttbl.appendChild(newLine);


                //      show_image("images/"+messages.item(i).getAttribute("picture"), 50, 50)
                //document.getElementById("userliss").textContent= messages.item(i).getAttribute("name");
                //cell2.innerHTML = show_image("images/"+messages.item(i).getAttribute("picture"), 50, 50);
            }

        }
        function show_image(src, width, height, alt) {
            var img = document.createElement("img");
            img.src = src;
            // console.log(src)
            img.width = width;
            img.height = height;
            img.alt = alt;
            //document.getElementById("pics").appendChild(img);
            return img;

        }


        //]]>
    </script>
</head>

<body style="text-align: left" onload="load()" onunload="unload()">
<html>
<center>
    <h1>Online Users</h1>
</center>

<table id="userListTable" border="2" bordercolor="orange" align="center">
    <tr>
        <th>Photo</th>
        <th>Username</th>
    </tr>


</table>

</body>
</html>
