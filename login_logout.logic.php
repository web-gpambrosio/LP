<?php

class login_logout {
    
    public function validateLogin(){
        include ("connectionstring.php");

			$username = $_POST['txtUsername'];
			$password = $_POST['txtPassword'];

			$username = stripslashes($username);
			$password = stripslashes($password);
			$hashedPassword = sha1($password);
			$transDetails = "Login";

        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
        $dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");
        $query = "Call sp_Login('".$username."', '".$hashedPassword."', '".$transDetails."', '".$ipAddress."');";
		$sql_result = mysql_query($query,$dbConnection) or die(mysql_error($dbConnection));
        $row = mysql_fetch_array($sql_result);
        $res_StatusNo = $row['StatusNo'];
        $res_StatusDesc = $row['StatusDesc'];
        $res_SessionID = $row['SessionID'];
        $res_SessionType = $row['SessionType'];
        $res_UserID = $row['UserID'];
        $res_Username = $row['Username'];

//        return array($resultID, $sessionID, $statusDec,$hashedPassword);
        return array($res_StatusNo, $res_StatusDesc, $res_SessionID, $res_SessionType, $res_UserID, $res_Username);
        mysql_close($dbConnection);
    }

    public function logout($logout_sid){
     include ("connectionstring.php");
     $dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
     $dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");
     $query = "Call sp_Logout('".$logout_sid."')";
     $sql_result = mysql_query($query);
     $row = mysql_fetch_array($sql_result);
     $res_StatusNo = $row['StatusNo'];
     $res_StatusDesc = $row['StatusMsg'];
     return array($res_StatusNo, $res_StatusDesc);
     mysql_close($dbConnection);
    }

}
?>