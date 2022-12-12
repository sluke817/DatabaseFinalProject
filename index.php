<?php
//Luke Schaefer 18186970
// main program file. This is what is served on port 3000

    session_start();
    require('connection.php');
    $sqlQuery = "";
    $showTT = [];
    $showMS = [];
    $showRS = [];

    $ttCourse = "";
    $msCourse = "";
    $rsMember = "";

    //Handles when adding a new player
    if(isset($_POST['nmSubmit'])) {
        if(!empty($_POST['nmName'])) {
            $sqlQuery = "INSERT INTO MEMBERS (Name, Handicap) VALUES ('" . $_POST['nmName'] . "', '" . $_POST['nmHandi'] . "')";
        }
        else {
            echo "Empty entry. Please fill all fields.";
        }
    }

    //Handles when adding a new course
    if(isset($_POST['ncSubmit'])) {
        if(!empty($_POST['ncName']) && !empty($_POST['ncDiff'])) {
            $sqlQuery = "INSERT INTO COURSES (Name, Difficulty) VALUES ('" . $_POST['ncName'] . "', '" . $_POST['ncDiff'] . "')";
        }
        else {
            echo "Empty entry. Please fill all fields.";
        }
    }

    //Handles when adding a new pro
    if(isset($_POST['npSubmit'])) {
        if(!empty($_POST['npCourseSelect']) && !empty($_POST['npName'])) {
            $sqlQuery = "INSERT INTO PROS (Name, Course_id) VALUES ('" . $_POST['npName'] . "', '" . $_POST['npCourseSelect'] . "')";
        }
        else {
            echo "Empty entry. Please fill all fields.";
        }   
    }

    //Handles when adding a new tee time
    if(isset($_POST['ntSubmit'])) {
        if(!empty($_POST['ntTime']) && !empty($_POST['ntCourseSelect']) && !empty($_POST['ntMemberSelect'])) {
            
            try {
                $testQuery = mysqli_query($con, "SELECT * FROM MEMBERSHIPS WHERE {$_POST['ntCourseSelect']} = Course_id AND {$_POST['ntMemberSelect']} = Member_id");
                if($testQuery->num_rows > 0) {
                    $sqlQuery = "INSERT INTO TEE_TIMES (Teetime, Member_id, Course_id) VALUES ('" . $_POST['ntTime'] . "', '" . $_POST['ntMemberSelect'] . "', '" . $_POST['ntCourseSelect'] . "')"; 
                }
                else {
                    echo "Player is not a member at that course. Only members can schedule tee times at any course";
                }
                
                
            }
            catch (Exception $e) {
                echo "Error:";
                echo $e->getMessage();
            }

            #$sqlQuery = "INSERT INTO TEE_TIMES (Teetime, Member_id, Course_id) SELECT {$_POST['ntTime']}, {$_POST['ntMemberSelect']}, {$_POST['ntCourseSelect']} FROM MEMBERSHIPS AS MS WHERE MS.Member_id = {$_POST['ntMemberSelect']} AND MS.Course_id = {$_POST['ntCourseSelect']}"; 
            
        }
        else {                                                                                                                                                 
            echo "Empty entry. Please fill all fields.";
        }
    }

    //Handles when adding a new membership
    if(isset($_POST['nmsSubmit'])) {
        if(!empty($_POST['nmsMemberSelect']) && !empty($_POST['nmsCourseSelect']) && !empty($_POST['nmsRate'])) {
            $sqlQuery = "INSERT INTO MEMBERSHIPS (Member_id, Course_id, Rate) VALUES ('" . $_POST['nmsMemberSelect'] . "', '" . $_POST['nmsCourseSelect'] . "', '" . $_POST['nmsRate'] . "')";
        }
        else {
            echo "Empty entry. Please fill all fields.";
        }
    }

    //Handles when adding a new score
    if(isset($_POST['nsSubmit'])) {
        if(!empty($_POST['nsMemberSelect']) && !empty($_POST['nsScore']) && !empty($_POST['nsDate'])) {
            $sqlQuery = "INSERT INTO RECENT_SCORES (Score, Member_id, Date) VALUES ('" . $_POST['nsScore'] . "', '" . $_POST['nsMemberSelect'] . "', '" . $_POST['nsDate'] . "')";
        }
        else {
            echo "Empty entry. Please fill all fields.";
        }
    }

    //Handles when to show the tee times for a given course
    if(isset($_POST['sTT'])) {
        try {
            $showTT = mysqli_query($con, "SELECT T.Teetime, M.Name AS MName, C.Name AS CName FROM TEE_TIMES AS T, MEMBERS AS M, COURSES AS C WHERE M.Member_id = T.Member_id AND C.Course_id = T.Course_id AND {$_POST['sTTcid']} = T.Course_id");
            $courseQuery = mysqli_query($con, "SELECT Name FROM COURSES WHERE Course_id = {$_POST['sTTcid']}");
            foreach ($courseQuery as $row) {
                $ttCourse = $row['Name'];
            }
        }
        catch (Exception $e) {
            echo "Error:";
            echo $e->getMessage();
        }
    }

    //Handles when to show the memberships for a given course
    if(isset($_POST['sMS'])) {
        try {
            $showMS = mysqli_query($con, "SELECT M.Name AS MName, MS.Rate AS Rate, C.Name AS CName FROM MEMBERSHIPS AS MS, MEMBERS AS M, COURSES AS C WHERE M.Member_id = MS.Member_id AND C.Course_id = MS.Course_id AND {$_POST['sMScid']} = MS.Course_id");
            $courseQuery = mysqli_query($con, "SELECT Name FROM COURSES WHERE Course_id = {$_POST['sMScid']}");
            foreach ($courseQuery as $row) {
                $msCourse = $row['Name'];
            }
        }
        catch (Exception $e) {
            echo "Error:";
            echo $e->getMessage();
        }
    }

    //Handles when to show the recent scores for a given player
    if(isset($_POST['sRS'])) {
        try {
            $showRS = mysqli_query($con, "SELECT M.Name AS MName, RS.Score AS Score, RS.Date AS Date FROM RECENT_SCORES AS RS, MEMBERS AS M WHERE M.Member_id = RS.Member_id AND {$_POST['sRSmid']} = RS.Member_id");
            $memberQuery = mysqli_query($con, "SELECT Name FROM MEMBERS WHERE Member_id = {$_POST['sRSmid']}");
            foreach ($memberQuery as $row) {
                $rsMember = $row['Name'];
            }
        }
        catch (Exception $e) {
            echo "Error:";
            echo $e->getMessage();
        }
    }

    //Runs the sql query that is needed
    if($sqlQuery != "") {
        try {
            mysqli_query($con, $sqlQuery);
            echo "Success!";
        }
        catch (Exception $e) {
            echo "Error:";
            echo $e->getMessage();
        }
    }

    try {
        $courses = mysqli_query($con, "SELECT * FROM COURSES");
        $members = mysqli_query($con, "SELECT * FROM MEMBERS");
        $pros = mysqli_query($con, "SELECT DISTINCT P.Name AS Pname, C.Name AS Cname FROM PROS AS P, COURSES AS C WHERE P.Course_id = C.Course_id");
    }
    catch (Exception $e) {
        echo "Error:";
        echo $e->getMessage();
    }
?>

<html>
    <head>
        <style>
            table {
                border: 2px solid;
            }
            table th, td, tr {
                border: 2px solid;
                text-align: center;
                padding-left: 10px;
                padding-right: 10px;
            }
            * {
                box-sizing: border-box;
            }
            .row {
                margin-left:-5px;
                margin-right:-5px;
            }  
            .column {
                float: left;
                width: 33%;
                padding: 5px
            }
            .row::after {
                content: "";
                clear: both;
                display: table;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <h1> Golf Information </h1>
        <div class="row">
            <div class="column">
                <!-- Courses Table -->
                <table>
                    <tr>
                        <th>Course Name</th>
                        <th>Difficulty</th>
                    </tr>
                    <?php foreach($courses as $row): ?>
                        
                        <tr>
                            <td> <? echo $row['Name']; ?> </td>
                            <td> <? echo $row['Difficulty']; ?> </td>
                            <td>
                                <form action="/index.php" method="post">
                                    <input type="hidden" name="sTTcid" value="<? echo $row['Course_id'] ?>">
                                    <input type="submit" value="Show Tee Times" name="sTT">
                                </form>
                                <form action="/index.php" method="post">
                                    <input type="hidden" name="sMScid" value="<? echo $row['Course_id'] ?>">
                                    <input type="submit" value="Show Memberships" name="sMS">
                                </form>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
            <div class="column">
                <!-- Players Table -->
                <table>
                    <tr>
                        <th>Player Name</th>
                        <th>Handicap</th>
                    </tr>
                    <?php foreach($members as $row): ?>
                        <tr>
                            <td> <? echo $row['Name']; ?> </td>
                            <td> <? echo $row['Handicap']; ?> </td>
                            <td>
                                <form action="/index.php" method="post">
                                    <input type="hidden" name="sRSmid" value="<? echo $row['Member_id'] ?>">
                                    <input type="submit" value="Show Recent Scores" name="sRS">
                                </form>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
            <div class="column">
                <!-- Pro Table -->
                <table>
                    <tr>
                        <th>Pro Name</th>
                        <th>Course</th>
                    </tr>
                    <?php foreach($pros as $row): ?>
                        
                        <tr>
                            <td> <? echo $row['Pname']; ?> </td>
                            <td> <? echo $row['Cname']; ?> </td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
         
        </div>
        <!-- The following portion is for the insert forms -->
        <div class="row">
            <div class="column">
                <form action="/index.php" method="post">
                    <p>Add Course:</p>
                    <label for='ncName'>Name:</label>
                    <input type='text' name='ncName'>
                    <label for='ncDiff'>Difficulty:</label>
                    <select name='ncDiff'>
                        <option value="" disabled selected>Choose option</option>
                        <option value="Beginner">Beginner</option>
                        <option value="Beginner">Intermediate</option>
                        <option value="Beginner">Pro</option>
                    </select>
                    <input type='submit' value="Submit" name='ncSubmit'>
                </form>
            </div>
            <div class="column">
                <form action="/index.php" method="post">
                    <p>Add player:</p>
                    <label for='nmName'>Name:</label>
                    <input type='text' name='nmName'>
                    <label for='nmHandi'>Handicap:</label>
                    <input type='number' name='nmHandi'>
                    <input type='submit' value="Submit" name='nmSubmit'>
                </form>
            </div>
            <div class="column">
                <form action="/index.php" method="post">
                    <p>Add Pro:</p>
                    <label for='npName'>Name:</label>
                    <input type='text' name='npName'>
                    <label for='npCourse'>Select Course:</label>
                    <select name="npCourseSelect">
                        <option value="" disabled selected>Choose option</option>
                        <? foreach($courses as $option): ?>
                            <option value="<? echo $option['Course_id']; ?>"> <? echo $option['Name']; ?> </option>
                        <? endforeach; ?>
                    </select>
                    <input type='submit' value="Submit" name='npSubmit'>
                </form>
            </div>
        </div>
        <div class="row">
                <form action="/index.php" method="post">
                    <p>Join Club:</p>
                    <label for='nmsMemberSelect'>Who is Joining?:</label>
                    <select name="nmsMemberSelect">
                        <option value="" disabled selected>Choose option</option>
                        <? foreach($members as $option): ?>
                            <option value="<? echo $option['Member_id']; ?>"> <? echo $option['Name']; ?> </option>
                        <? endforeach; ?>
                    </select>
                    <label for='nmsCourseSelect'>Select Course:</label>
                    <select name="nmsCourseSelect">
                        <option value="" disabled selected>Choose option</option>
                        <? foreach($courses as $option): ?>
                            <option value="<? echo $option['Course_id']; ?>"> <? echo $option['Name']; ?> </option>
                        <? endforeach; ?>
                    </select>
                    <label for='nmsRate'>Rate:</label>
                    <input type='number' name='nmsRate'>
                    <input type='submit' value="Submit" name='nmsSubmit'>
                </form>
        </div>
        <div class="row">
            <form action="/index.php" method="post">
                <p>Upload New Score:</p>
                <label for='nsDate'>Date:</label>
                <input type='date' name='nsDate'>
                <label for='nsMemberSelect'>Select Member:</label>
                <select name="nsMemberSelect">
                    <option value="" disabled selected>Choose option</option>
                    <? foreach($members as $option): ?>
                        <option value="<? echo $option['Member_id']; ?>"> <? echo $option['Name']; ?> </option>
                    <? endforeach; ?>
                </select>
                <label for='nsScore'>Score:</label>
                <input type='text' name='nsScore'>
                <input type='submit' value="Submit" name='nsSubmit'>
            </form>
        </div>
        <div class="row">
            <form action="/index.php" method="post">
                <p>Schedule Tee Time:</p>
                <label for='ntTime'>Time:</label>
                <input type='datetime-local' name='ntTime'>
                <label for='ntCourseSelect'>Select Course:</label>
                <select name="ntCourseSelect">
                    <option value="" disabled selected>Choose option</option>
                    <? foreach($courses as $option): ?>
                        <option value="<? echo $option['Course_id']; ?>"> <? echo $option['Name']; ?> </option>
                    <? endforeach; ?>
                </select>
                <label for='ntMemberSelect'>Who is Playing?:</label>
                <select name="ntMemberSelect">
                    <option value="" disabled selected>Choose option</option>
                    <? foreach($members as $option): ?>
                        <option value="<? echo $option['Member_id']; ?>"> <? echo $option['Name']; ?> </option>
                    <? endforeach; ?>
                </select>
                <input type='submit' value="Submit" name='ntSubmit'>
            </form>
        </div>
        <!-- The following shows the results from one of the "show" buttons -->
        <div class="row">
            <div class="column">
                <!-- <? if(!empty($showTT)){echo "<p>Tee Times for {$ttCourse}</p>";} ?> -->
                <p>Tee Times for <?echo $ttCourse ?></p>
                <table>
                    <tr>
                        <!-- <? if(!empty($showTT)){echo "<th>Tee Time</th>";} ?>
                        <? if(!empty($showTT)){echo "<th>Member</th>";} ?> -->
                        <th>Tee Time</th>
                        <th>Member</th>
                    </tr>
                    <?php foreach($showTT as $row): ?>
                        <tr>
                            <td> <? echo $row['Teetime']; ?> </td>
                            <td> <? echo $row['MName']; ?> </td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
            <div class="column">
                <!-- <? if(!empty($showMS)){echo "<p>Memberships for {$msCourse}</p>";} ?> -->
                <p>Memberships for <? echo $msCourse ?></p>
                <table>
                    <tr>
                        <!-- <? if(!empty($showMS)){echo "<th>Member Name</th>";} ?>
                        <? if(!empty($showMS)){echo "<th>Rate</th>";} ?> -->
                        <th>Member Name</th>
                        <th>Rate</th>
                    </tr>
                    <?php foreach($showMS as $row): ?>
                        <tr>
                            <td> <? echo $row['MName']; ?> </td>
                            <td> <? echo $row['Rate']; ?> </td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
            <div class="column">
                <!-- <? if(!empty($showRS)){echo "<p>Recent Scores for {$rsMember}</p>";} ?> -->
                <p>Recent Scores for <? echo $rsMember ?></p>
                <table>
                    <tr>
                        <!-- <? if(!empty($showRS)){echo "<th>Date</th>";} ?>
                        <? if(!empty($showRS)){echo "<th>Score</th>";} ?> -->
                        <th>Date</th>
                        <th>Score</th>
                    </tr>
                    <?php foreach($showRS as $row): ?>
                        <tr>
                            <td> <? echo $row['Date']; ?> </td>
                            <td> <? echo $row['Score']; ?> </td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
        </div>   
    </body>
</html>