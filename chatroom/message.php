<?php

// get the name from cookie
$name = "";
if (isset($_COOKIE["name"])) {
    $name = $_COOKIE["name"];
}

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Message Page</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <script language="javascript" type="text/javascript">
        //<![CDATA[
        var loadTimer = null;
        var request;
        var datasize;
        var lastMsgID;

        function load() {
            var username = document.getElementById("username");
            if (username.value == "") {
                loadTimer = setTimeout("load()", 100);
                return;
            }

            loadTimer = null;
            datasize = 0;
            lastMsgID = 0;


            var node = document.getElementById("chatroom");
            node.style.setProperty("visibility", "visible", null);

            getUpdate();
        }

        function unload() {
            var username = document.getElementById("username");
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
            if (request.readyState == 4 && request.status == 200 && request.responseText) {
                var xmlDoc;
                try {
                    xmlDoc = new XMLHttpRequest();
                    xmlDoc.loadXML(request.responseText);
                } catch (e) {
                    var parser = new DOMParser();
                    xmlDoc = parser.parseFromString(request.responseText, "text/xml");
                }
                datasize = request.responseText.length;
                updateChat(xmlDoc);
                getUpdate();
            }
        }

        var messagesLength = 0;

        function updateChat(xmlDoc) {

            //point to the message nodes
            var messages = xmlDoc.getElementsByTagName("message");

            //console.log(messages.item(1))
            for (var i = messagesLength; i < messages.length; i++) {
                //   console.log(messages.item(i).getAttribute("message"));
                showMessage(messages.item(i).getAttribute("name") + ":", messages.item(i).textContent, messages.item(i).getAttribute("color"));
            }
            messagesLength = messages.length;

        }

        function stringStartsWith(string, prefix) {
            return string.slice(0, prefix.length) == prefix;
        }


        function showMessage(nameStr, contentStr, color) {

            var node = document.getElementById("chattext");
            // Create the name text span
            var nameNode = document.createElementNS("http://www.w3.org/2000/svg", "tspan");

            // Set the attributes and create the text
            nameNode.setAttribute("x", 100);
            nameNode.setAttribute("dy", 20);
            nameNode.appendChild(document.createTextNode(nameStr));

            // Add the name to the text node
            node.appendChild(nameNode);

            // Create the score text span
            var conetentNode = document.createElementNS("http://www.w3.org/2000/svg", "tspan");

            // Set the attributes and create the text

            conetentNode.setAttribute("x", 200);

            if (color.length > 0) {

                conetentNode.setAttribute("style", "fill:" + color);
            }

            var words = contentStr.split(" ");

            for (var i = 0; i < words.length; i++) {
                // console.log(words[i])
                if (stringStartsWith(words[i], "http://")) {

                    conetentNode.setAttribute("text-decoration", "underline");
                    conetentNode.setAttribute("onclick", "javascript:window.open('" + words[i] + "','newWin','toolbar=no, scrollbars=yes, width=600')");
                    conetentNode.setAttribute("onmouseover", "this.style.cursor='pointer'")

                }
            }


            conetentNode.appendChild(document.createTextNode(contentStr));
            // console.log(conetentNode.textContent)

            // Add the name to the text node
            node.appendChild(conetentNode);
        }

        //]]>
    </script>
</head>

<body style="text-align: left" onload="load()" onunload="unload()">
<svg width="800px" height="2000px"
     xmlns="http://www.w3.org/2000/svg"
     xmlns:xhtml="http://www.w3.org/1999/xhtml"
     xmlns:xlink="http://www.w3.org/1999/xlink"
     xmlns:a="http://www.adobe.com/svg10-extensions" a:timeline="independent"
>

    <g id="chatroom" style="visibility:hidden">
        <rect width="520" height="2000" style="fill:white;stroke:red;stroke-width:2"/>
        <text x="260" y="40" style="fill:red;font-size:30px;font-weight:bold;text-anchor:middle">Chat Window</text>
        <text id="chattext" y="45" style="font-size: 20px;font-weight:bold"/>
    </g>
</svg>

<form action="">
    <input type="hidden" value="<?php print $name; ?>" id="username"/>
</form>

</body>
</html>
