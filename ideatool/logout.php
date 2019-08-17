<?php

session_start();

if(isset($_COOKIE[ session_name()])) {
    
    setcookie(session_name(),'',time()-86400,'/');
	session_unset();
	session_destroy();
}

include('includes/header.php');

?>

<h1>Користувач вийшов із системи</h1>

<p class="lead">
Дякую за те що скористалися сервісом. Приємного робочого дня!
</p><br><p class="lead"><a href="index.php">На Головну</a></p>

<?php

include('includes/footer.php');

?>