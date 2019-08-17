<?php

session_start();

if(!$_SESSION['loggedInUser']) {
    
    header("Location: index.php");
}

include('includes/connection.php');
include('includes/functions.php');

$loggedInUser=$_SESSION['loggedInUser'];

$query="SELECT status FROM users WHERE login='$loggedInUser'";
$result=mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);

$status=$row['status'];

if ($status<1) {
    
    header("Location: index.php");
}

if($_GET['alert']=='success'){
            
            $alertMessage="<div class='alert alert-success'>Користувача успішно додано в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
        }
elseif($_GET['alert']=='delete'){
            
            $alertMessage="<div class='alert alert-danger'>Користувача успішно видалено з бази! <a class='close' data-dismiss='alert'>&times;</a></div>";
        }
        elseif($_GET['alert']=='updatesuccess'){
            
            $alertMessage="<div class='alert alert-success'>Пароль користувача змінено на стандартний <strong>lpsplus</strong>! <a class='close' data-dismiss='alert'>&times;</a></div>";
        }

if(isset($_POST['add'])){

    $segment=($_POST["segment"]);
    $login=($_POST["login"]);
    $password=password_hash(($_POST["password"]),PASSWORD_DEFAULT);
    $email=($_POST["email"]);
    
    if ($_POST["status"]>0) {
        $statuss=1;}

    else {$statuss=0;

    }
    
    
    /*if($segment&&$login&&$password&&$email>"") {
        
        $queryForUsers="INSERT INTO users (id, segment, login, password, email, status) VALUES (NULL, '$segment', '$login', '$password', '$email','$statuss')";
        $resultForUsers=mysqli_query($conn,$queryForUsers);
        
        if($resultForUsers) {
            
            header( "Location: new.php?alert=success" );
        } 
        else {
            
            echo "Помилка: ". $queryForUsers ."<br>" . mysqli_error($conn);
        }       
    }*/
	
	if($segment&&$login>"") {
        
        $queryForUsers="INSERT INTO users (id, segment, login, password, email, status) VALUES (NULL, '$segment', '$login', '', '','$statuss')";
        $resultForUsers=mysqli_query($conn,$queryForUsers);
        
        if($resultForUsers) {
            
            header( "Location: new.php?alert=success" );
        } 
        else {
            
            echo "Помилка: ". $queryForUsers ."<br>" . mysqli_error($conn);
        }       
    }
    
        
}

include('includes/header.php');

?>

<?php echo $alertMessage;?>

<?php   $query = "SELECT segment FROM segments";
        $result = mysqli_query($conn,$query);?>

<h1>Додати користувача <a href="segment.php" type="button" class="btn btn-lg btn-primary pull-right">Редактор сегментів</a></h1>
<br><br>

<form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post" class="row">
    <div class="form-group col-sm-6">
        <label for="sel1">Оберіть сегмент</label>
      <select class="form-control" id="sel1" name="segment">
        <?php while ($row=mysqli_fetch_array($result)) {
            echo "<option name'segment'>".$row['segment']."</option>";}?>
      </select>
        <input type="text" class="form-control input-sm" id="login" name="login" value="" placeholder="Логін *">
        <!--<input type="text" class="form-control input-sm" id="password" name="password" value="" placeholder="Пароль *">
        <input type="text" class="form-control input-sm" id="email" name="email" value="" placeholder="Електронна пошта *">-->
        <br>
        <p class="lead text-right"><input type="radio" value="1" name="status" class="pull-center">    адміністратор</p>
    </div><!--.checkbox-->
    <div class="col-sm-12">
            <a href="ideas.php" type="button" class="btn btn-lg btn-danger">Повернутися</a>
            <button type="submit" class="btn btn-lg btn-success pull-right" name="add">Додати користувача</button>
    </div>
</form>
<br><br><br>

<?php
$query = "SELECT segment, login, password FROM users ORDER BY id DESC";
$result = mysqli_query($conn,$query);

echo '<table class="table table-striped table-bordered text-center table-hover">
            <tr class="danger">
                <th class="text-center">Сегмент</th>
                <th class="text-center">Логін</th>
                <th class="text-center">Видалити</th>';
                //<th class="text-center">Скинути пароль</th>

    while ($row=mysqli_fetch_assoc($result)) {
          
            echo "<tr><td>".$row['segment']."</td><td>".$row['login']."</td></td>";

            echo '<td><a title="Видалити користувача" href="includes/delete_user.php?login=' . $row['login'] . '" type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-minus"></span>
                    </a></td>';
            /*echo '<td><a title="Змінити пароль користувача на стандартний" href="includes/password_default.php?login=' . $row['login'] . '" type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-minus"></span>
                    </a></td>';*/
             echo "</tr>";

        }

?>

<?php

echo '<script src="../libs/jquery/jquery-2.1.4.min.js"></script>
        <script src="../libs/bootstrap/bootstrap.min_v3.3.7.js"></script>
        <script src="../libs/jquery-ui/jquery-ui.min_v1.12.1.js"></script>
        <script src="js/script.js"></script>';

?>