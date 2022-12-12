<!-- docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' phase3_mariadb_1 -->
<?php
    try {
                            #Change the address 
                            #to the one from the
                            #docker inspect command
        $con = mysqli_connect("172.20.0.2", "root", "rootpwd", "GOLF");
    }
    catch (Exception $e) {
        echo $e->getMessage();
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
?>