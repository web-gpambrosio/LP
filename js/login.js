/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function chkSubmit()
{
           if(document.frmLogin.txtUsername.value == ""){
            alert("Please enter your username");
            document.frmLogin.txtUsername.focus();
            document.frmLogin.txtUsername.select();
            return false;
            }

            if(document.frmLogin.txtPassword.value == ""){
            alert("Please enter your password");
            document.frmLogin.txtPassword.focus();
            document.frmLogin.txtPassword.select();
            return false;
            }

}

function clearText(){
    document.frmLogin.txtUsername.clear();
    document.frmLogin.txtPassword.clear();
    document.frmLogin.reset();
	document.frmLogin.txtUsername.focus();
}


function chkUsername(){
if(document.frmLogin.txtUsername.value != ""){
    var iChars = "!@#$%^&*()-[]\\\';,`~.{}|\":<>?";
		for (var i = 0; i < document.frmLogin.txtUsername.value.length; i++)
			{
	  			if (iChars.indexOf(document.frmLogin.txtUsername.value.charAt(i)) != -1)
					{
  						alert ("Your username has invalid special characters. \nThese are not allowed.\n Please remove them and try again.");
						document.frmLogin.txtUsername.focus();
                                                document.frmLogin.txtUsername.select();
						document.frmLogin.txtUsername.value="";
  						return false;
                                        }
  			}
}

}

function chkPassword(){
if(document.frmLogin.txtPassword.value != ""){
     var iChars = "!@#$%^&*()-[]\\\';,`~.{}|\":<>?";
     for (var i = 0; i < document.frmLogin.txtPassword.value.length; i++)
     {
        if (iChars.indexOf(document.frmLogin.txtPassword.value.charAt(i)) != -1)
        {
            alert ("Your password has invalid characters. \nThese are not allowed. \nPleaase removve them and try again.");
            document.frmLogin.txtPassword.focus();
            document.frmLogin.txtPassword.select();
            document.frmLogin.txtPassword.value="";
            return false;
        }
     }

}

}