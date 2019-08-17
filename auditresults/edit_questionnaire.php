<?php
    include('includes/header.php');
    include('includes/connection.php');

    session_start();
    if($_SESSION['loggedInUser'])
    {
      $userLogin = $_SESSION['loggedInUser'];

      $query        = "SELECT status AS status FROM users WHERE login = '$userLogin'";
      $request      = mysqli_query($conn, $query);
      $row          = mysqli_fetch_assoc($request);
      $permissions  = $row['status'];
      if ($permissions < 1) {
        header("Location: dashboard.php");
      }
    }

    $sql = "SELECT * FROM 5s_level";
    $res = mysqli_query($conn, $sql);
    $arr = mysqli_fetch_array($res);
    $substracted = substr($arr['5s_level'], -2, 1);
?>
<script type="text/javascript" src="../libs/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="assets/js/questionnaire.js"></script>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Редагування опитувальника</h1>
        <div class="float-right">
            <p style="font-size: 14px;">Рівень впровадження 5S</p>
            <input type="range" min="1" max="5" value="<?php echo $substracted; ?>" id="s_slider">
            <span id="s_slider_data"><?php echo $arr['5s_level']; ?></span>
        </div>
    </div>
        <div class="form-inline questionnaire">
            <button class="btn" val="Стандарт" id="standard" style="margin:0 5px;"><i>Стандарт</i></button>
            <button class="btn" val="Офіс" id="office" style="margin:0 2px;">Офіс</button>
            <button class="btn" val="Продукція" id="production" style="margin:0 2px;">Продукція</button>
            <button class="btn" val="Автопарк" id="auto" style="margin:0 2px;">Автопарк</button>
            <button class="btn" val="Склад" id="warehouse" style="margin:0 2px;">Склад</button>
            <button class="btn" val="Технічна дільниця" id="tech" style="margin:0 2px;">Технічна дільниця</button>
            <button class="btn" val="Нарадчі кімнати" id="rooms" style="margin:0 2px;">Нарадчі кімнати</button>
            <button class="btn" val="Кухня" id="kitchen" style="margin:0 2px;">Кухня</button>
        </div><br>
    <div id="s_questionnaire" style="display: none;">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#one_s" id="first" style="text-decoration: none; color: #000;">Сортування</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#second_s" id="second" style="text-decoration: none; color: #000;">Раціональне розташування</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#third_s" id="third" style="text-decoration: none; color: #000;">Прибирання</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#fourth_s" id="fourth" style="text-decoration: none; color: #000;">Стандартизація</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#fifth_s" id="fifth" style="text-decoration: none; color: #000;">Вдосконалення</a>
        </li>
      </ul><br>
      <div class="tab-content"></div>
    </div>
</main>
<?php
    include('includes/footer.php');
	