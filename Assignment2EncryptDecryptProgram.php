<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
	
</head>

<body>
<?php 

	$ENCRYPTION_KEY = 'Password';              //Enctryption Key
	$ENCRYPTION_ALGORITHM = 'AES-256-CBC';     // Alorightm used.

	
//This fucntion encrypts the plain text. 
function EncryptThis($ClearTextData) {
    global $ENCRYPTION_KEY;
    global $ENCRYPTION_ALGORITHM;
    $EncryptionKey = base64_decode($ENCRYPTION_KEY);
    $InitializationVector  = openssl_random_pseudo_bytes(openssl_cipher_iv_length($ENCRYPTION_ALGORITHM));
    $EncryptedText = openssl_encrypt($ClearTextData, $ENCRYPTION_ALGORITHM, $EncryptionKey, 0, $InitializationVector);
    return base64_encode($EncryptedText . '::' . $InitializationVector);
	
}

// This function decrypts the cipher data. 
function DecryptThis($CipherData) {

    global $ENCRYPTION_KEY;
    global $ENCRYPTION_ALGORITHM;
    $EncryptionKey = base64_decode($ENCRYPTION_KEY);
    list($Encrypted_Data, $InitializationVector ) = array_pad(explode('::', base64_decode($CipherData), 2), 2, null);
    return openssl_decrypt($Encrypted_Data, $ENCRYPTION_ALGORITHM, $EncryptionKey, 0, $InitializationVector);
}
	
//Checks to see if POST Array is intinalized if so...
//POST is then copied and the target file is opened
//and then decrypted. 
if (!empty($_POST["ClearTextData"])) {

   $My_E_File = file_get_contents($_POST["ClearTextData"]) or die("Unable to open file!");
   $My_E_File = EncryptThis($My_E_File);
   file_put_contents("NewCipherText.txt", $My_E_File);
     
}
//Checks to see if POST Array is intinalized if so...
//POST is then copied and the target file is opened
//and then decrypted. 
if (!empty($_POST["CipherData"])) {

   $My_D_File = file_get_contents($_POST['CipherData']) or die("Unable to open file!");
   $My_D_File = DecryptThis($My_D_File);
   file_put_contents("NewPlainText.txt", $My_D_File);
   
}
?>

<!-------------Forms to select files to Encrypt and Decrypt. --------------------------->	
<center></center><form method="post" name="ClearTextData">
    Select file to encrypt:<br>
    <input method="post" type="file" name="ClearTextData" id="ClearTextData">
    <button type="submit" name="submit" id="Submit">Encrypt This</button>
	</form></center>

<br><hr>

<center></center><form method="post" name="CipherData">
    Select file to decrypt:<br>
    <input method="post" type="file" name="CipherData" id="CipherData">
    <button type="submit" name="submit" id="Submit">Decrypt This</button>
</form></center>
  
	
</body>
</html>
	
