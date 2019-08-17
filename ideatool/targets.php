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
            
            $alertMessage="<div class='alert alert-success'>Цілі успішно додано в базу! <a class='close' data-dismiss='alert'>&times;</a></div>";
        }

if(isset($_POST['add'])){

    $segment=($_POST["segment"]);
    $quantity=($_POST["quantity"]);
    $ideas=($_POST["ideas"]);
    $cost=($_POST["cost"]);
    $month=($_POST["month"]);
    
    if($segment&&$quantity&&$ideas&&$cost&&$month>"") {
        
        $queryForUsers="INSERT INTO target (id, segment, quantity, ideas, cost, month, date) VALUES (NULL, '$segment', '$quantity', '$ideas', '$cost','$month', CURRENT_TIMESTAMP)";
        $resultForUsers=mysqli_query($conn,$queryForUsers);
        
        if($resultForUsers) {
            
            header( "Location: targets.php?alert=success" );
        } 
        else {
            
            echo "Помилка: ". $queryForUsers ."<br>" . mysqli_error($conn);
        }       
    }
    
    mysqli_close($conn);  
}

include('includes/header.php');

?>

<?php echo $alertMessage;?>

<h1>Додати користувача</h1>

<form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post" class="row">
    <div class="form-group col-sm-6">
        <input type="text" class="form-control input-lg" id="segment" name="segment" value="" placeholder="Сегмент">
    </div>
    <div class="form-group col-sm-6">
        <input type="text" class="form-control input-lg" id="quantity" name="quantity" value="" placeholder="Кількість працівників">
    </div>
    <div class="form-group col-sm-6">
        <input type="text" class="form-control input-lg" id="ideas" name="ideas" value="" placeholder="Кількість ідей на працівника">
    </div>
    <div class="form-group col-sm-6">
        <input type="text" class="form-control input-lg" id="cost" name="cost" value="" placeholder="Сума коштів на одного працівника">
    </div>
    <div class="form-group col-sm-6">
        <input type="text" class="form-control input-lg" id="month" name="month" value="" placeholder="Місяць у форматі 1-12">
    </div>
    <div class="col-sm-12">
            <a href="ideas.php" type="button" class="btn btn-lg btn-danger">Повернутися</a>
            <button type="submit" class="btn btn-lg btn-success pull-right" name="add">Додати користувача</button>
    </div>
</form>

<?php

include('includes/footer.php');

?>