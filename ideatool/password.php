<?php

session_start();

if(!$_SESSION['loggedInUser']) {
    
    header("Location: index.php");
}

include('includes/header.php');
include('includes/connection.php');
include('includes/functions.php');


$loggedInUser=$_SESSION['loggedInUser'];

$alertMessage[0]="<div class='alert alert-success' id='alert'>Дані успішно внесено в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[1]="<div class='alert alert-danger' id='alert'> Невірно введений поточний пароль! <a class='close' data-dismiss='alert'>&times;</a></div>";
$alertMessage[2]="<div class='alert alert-danger' id='alert'> Поля із новим паролем не співпадають! <a class='close' data-dismiss='alert'>&times;</a></div>";

    $query = "SELECT password AS password FROM users WHERE login='$loggedInUser'";
    $result = mysqli_query( $conn, $query );
    $fetch_result = mysqli_fetch_assoc($result);
    $result = $fetch_result['password'];

if(isset($_POST['submit'])){

    $password_old=password_verify($_POST["password"],$result);
    $password_new=($_POST["password_new"]);
    $password_new_confirm=($_POST["password_new_confirm"]);

    $password_input = password_hash($password_new,PASSWORD_DEFAULT);

    
    if($password_old==$result&&$password_new==$password_new_confirm) {
        
        $query="UPDATE users SET password='$password_input' WHERE login='$loggedInUser'";
        $result=mysqli_query($conn,$query);
            
            echo $alertMessage[0];
        } 
        elseif  ($password_old!=$result){
            
            echo $alertMessage[1];
        } elseif ($password_new!=$password_new_confirm&&$password_old=$result) {
            echo $alertMessage[2];
        }      
}



?>

<h1>Змінити пароль</h1>
<br><br><br>

<form class="form-inline text-center" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post">
    <div class="form-group">
        <input type="password" class="form-control" id="password" placeholder="Пароль" name="password">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" id="password_new" placeholder="Новий пароль" name="password_new">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" id="password_new_confirm" placeholder="Новий пароль" name="password_new_confirm">
    </div>
    <button type="submit" class="btn btn-success" name="submit">Змінити пароль</button>
</form>

<br><br><br><a href="ideas.php" type="button" class="btn btn-lg btn-info pull-right">Повернутися</a>

<?php

include('includes/footer.php');

?>