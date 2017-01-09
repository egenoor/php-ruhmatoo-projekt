<?php
    require("/home/egenoor/config.php");
    require("../functions.php");

    require("../class/User.class.php");
    $User = new User($mysqli);

//kui kasutaja on juba sisse logitud siis
//suunan data lehele

if (isset($_SESSION["userId"])) {

    //suunan sisselogimise lehele
    header("Location: data.php");
    exit();
}

//MUUTUJAD
$loginUsername = "";
$loginUsernameError = "";
$signupUsernameError = "";
$signupEmailError = "";
$signupPasswordError = "";
$signupPassword = "";
$signupUsername = "";
$signupEmail = "";
$signupAge = "";


if(isset($_POST['submit']))
{
$signupUsername=mysql_real_escape_string($_POST['signupUsername']);
$signupPassword=mysql_real_escape_string($_POST['signupPassword']);
$signupEmail=mysql_real_escape_string($_POST['signupEmail']);
$signupAge=mysql_real_escape_string($_POST['signupAge']);
$rand=rand(100000,100000000);
$query2=mysql_query("insert into user values('','$signupUsername','$signupPassword','$signupEmail','$signupAge','$rand','0')");
if($query2)
{
student_confirmation($id,$signupUsername,$signupPassword,$signupEmail,$signupAge);
}
}


if(isset($_POST["signupUsername"])){

    if(empty($_POST["signupUsername"])){

        $signupUsernameError = "Enter username!";
    } else {
        $signupUsername = $_POST["signupUsername"];
    }
}

// kas e-post oli olemas
if ( isset ( $_POST["signupEmail"] ) ) {

    if ( empty ( $_POST["signupEmail"] ) ) {

        // oli email, kuid see oli t�hi
        $signupEmailError = "This field is mandatory!";

    } else {

        //email olemas
        $signupEmail = $_POST["signupEmail"];

    }

}


if ( isset ( $_POST["signupPassword"] ) ) {

    if ( empty ( $_POST["signupPassword"] ) ) {

        // oli password, kuid see oli t�hi
        $signupPasswordError = "This field is mandatory!";

    } else {

        // tean et parool on ja see ei olnud t�hi
        // V�HEMALT 8

        if ( strlen($_POST["signupPassword"]) < 8 ) {

            $signupPasswordError = "Password has to be atleast 8 characters!";

        }

    }


}

if ( isset ( $_POST["signupAge"] ) &&
    !empty ( $_POST["signupAge"] )) {

    $signupAge = $_POST["signupAge"];
}


    if ( isset($_POST["signupEmail"]) &&
        isset($_POST["signupPassword"]) &&
        isset($_POST["signupAge"]) &&
        $signupEmailError == "" &&
        empty ($signupPasswordError)) {

        echo "Saving...<br>";

        $password = hash("sha512", $_POST["signupPassword"]);

        $User->signUp($Helper->cleanInput($_POST['signupUsername']), $Helper->cleanInput($_POST['signupEmail']),
        $Helper->cleanInput ($password),$Helper->cleanInput($_POST['signupAge']));

    }

    $error ="";
    if (isset($_POST["loginUsername"]) &&
        isset($_POST["loginPassword"]) &&
        !empty($_POST["loginUsername"]) &&
        !empty($_POST["loginPassword"])) {

        $error = $User->login($Helper->cleanInput($_POST["loginUsername"]),
        $Helper->cleanInput($_POST["loginPassword"]));

    }

?>

<?php require("../header.php"); ?>

<title>Login/signup page</title>

<h1>Log in</h1>

<form method="POST">

    <p style="color:red;"><?=$error;?></p>

    <label>Username:</label><br>
    <input name="loginUsername" type="text" value="<?=$loginUsername;?>">
    <?php echo $loginUsernameError; ?>
    <br><br>

    <input name="loginPassword" type="password" placeholder="Password">
    <br><br>
    <input type="submit" value="Log in">

</form>

<h1>Create account</h1>

<form method="POST">

    <label>Username:</label><br>
    <input name="signupUsername" type="text" value="<?=$signupUsername;?>">
    <?php echo $signupUsernameError; ?>

    <br><br>

    <label>Email:</label><br>
    <input name="signupEmail" type="email" value="<?=$signupEmail;?>">
    <?php echo $signupEmailError; ?>

    <br><br>

    <input name="signupPassword" type="password" placeholder="Password">
    <?php echo $signupPasswordError; ?>

    <br><br>

    <label>Age:</label><br>
    <input name="signupAge" type="number" value="<?=$signupAge;?>">

    <br><br>

    <input type="submit" value="Create account">


</form>

<br>
<?php require("../footer.php"); ?>