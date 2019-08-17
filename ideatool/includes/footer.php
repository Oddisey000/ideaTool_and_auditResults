        </div><!--container-->
        <footer class="text-center">
            <hr><br>
            <small>Coded by &copy; <a href="https://www.facebook.com/oddisey000">Vitalii Pertsovych</a></small>
        </footer>
        <script src="../libs/jquery/jquery-2.1.4.min.js"></script>
        <script src="../libs/bootstrap/bootstrap.min_v3.3.7.js"></script>
        <script src="../libs/jquery-ui/jquery-ui.min_v1.12.1.js"></script>
        <script src="js/script.js"></script>

        </div>
        <form class="modal fade" id="inputPrice" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body" id="result">
                        <div class="form-group">
                            <label>Вкажіть суму</label>
                            <input type="text" for="fname" name="fname" placeholder="Сума віртуальних заощаджень" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Повернутися</button>
                        <button type="submit" class="btn btn-primary" name="submit">Внести зміни</button>

                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php

        include('includes/connection.php');

            $ThisYear = date('Y');

        if (isset($_POST['fname']) && $_POST['fname']) {

            $priceNew = $_POST['fname'];

            $query = "SELECT year FROM service_table WHERE year='$ThisYear' LIMIT 1";
            $result = mysqli_query( $conn, $query );

            if (mysqli_num_rows($result) < 1) {
               $query = "INSERT INTO service_table (year, price) VALUES ('$ThisYear', '$priceNew')";
                $result = mysqli_query( $conn, $query );
            }

            else {
            $query="UPDATE service_table SET price='$priceNew' WHERE year='$ThisYear'";
            $result=mysqli_query($conn,$query);}

    }?>
    </body>
</html>