<?php

if ($status < 1) {
    echo '<br><div class="text-left" id="accord">
      
      <h4>Відобразити дані по місяцях</h4>
      
      <div>
      <form action="';
    
}

?> 

      <?php

if ($status < 1) {
    echo htmlspecialchars($_SERVER['PHP_SELF']);
}
?>

      <?php

if ($status < 1) {
    echo '?id=';
}
?>
        
        <?php

if ($status < 1) {
    echo $ideaID;
}
?>

      <?php

if ($status < 1) {
    echo '" method="post" class="row">
    <div class="form-group col-sm-12 text-center">
        <button class="submit btn btn-sm" name="january">Січень</button>
        <button class="submit btn btn-sm" name="february">Лютий</button>
        <button class="submit btn btn-sm" name="march">Березень</button>
        <button class="submit btn btn-sm" name="april">Квітень</button>
        <button class="submit btn btn-sm" name="may">Травень</button>
        <button class="submit btn btn-sm" name="june">Червень</button>
        <button class="submit btn btn-sm" name="july">Липень</button>
        <button class="submit btn btn-sm" name="august">Серпень</button>
        <button class="submit btn btn-sm" name="september">Вересень</button>
        <button class="submit btn btn-sm" name="october">Жовтень</button>
        <button class="submit btn btn-sm" name="november">Листопад</button>
        <button class="submit btn btn-sm" name="december">Грудень</button>
    </div>
    </div>
</form>';
    
}

echo "</div>
    
    <br>";

?>