<?php

if (!isset($_COOKIE["name"])) {
    header("Location: error.html");
    return;
}

// get the name from cookie
$name = $_COOKIE["name"];

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Add Message Page</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <script type="text/javascript">
        //<![CDATA[
        function load() {
            var name = "<?php print $name; ?>";

            //delete this line 
            //window.parent.frames["message"].document.getElementById("username").setAttribute("value", name)

            setTimeout("document.getElementById('msg').focus()", 100);
        }

        function kuksuger() {
            alert("Logged out!");
        }

        function vinduet() {
            window.open('onlineUsers.php')
        }

        // function select(color) {
        //     document.getElementById("color").value=color;
        //   console.log(color)
        // document.getElementById("color").form.submit;
        //}
        function select(color) {
            document.getElementById("color").value = color;
            //document.getElementById("meinkuk").submit();
            //console.log("sug meg")

        }
        //]]>
    </script>
</head>

<body style="text-align: left" onload="load()">
<form action="add_message.php" method="post">
    <table border="0" cellspacing="5" cellpadding="0">
        <tr>
            <td>What is your message?</td>
        </tr>
        <tr>
            <td><input class="text" type="text" name="message" id="msg" style="width: 780px"/>
            </td>
        </tr>

        <tr>
            <td><input class="button" type="submit" value="Send Your Message" style="width: 200px"/>
                Choose a color:

                <button type="button" style="background-color:black;" onclick="select('red')">B</button>
                <button type="button" style="background-color:red;" onclick="select('red')">R</button>
                <button type="button" style="background-color:yellow;" onclick="select('yellow')">Y</button>
                <button type="button" style="background-color:green;" onclick="select('green')">G</button>
                <button type="button" style="background-color:cyan;" onclick="select('cyan')">C</button>
                <button type="button" style="background-color:blue;" onclick="select('blue')">B</button>
                <button type="button" style="background-color:magenta;" onclick="select('magenta')">M</button>
                <input type="hidden" name="color" id="color" value=""/></td>

        </tr>
    </table>
</form>

<!--Online users button-->

<table border="0" cellspacing="5" cellpadding="0">
    <tr>
        <button type="button" onclick="vinduet()">Show Online Users</button>
    </tr>
</table>

<!--logout button-->
<form action="login.html" method="post">
    <table border="0" cellspacing="5" cellpadding="0">
        <tr>
            <td><input class="button" type="submit" value="Logout" style="wid" color="red"
                       onSubmit="javascript: kuksuger()"/></td>
        </tr>
    </table>
</form>
</body>
</html>
