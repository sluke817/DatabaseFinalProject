Luke Schaefer LESDMG 18186970

Hello! Welcome to my CS 3380 Project. This project has some setup to it.

Setup:
There is a little bit of setup. My application uses docker containers, but the setup is still very minimal.

Step 1: Make sure you have docker installed. It is necessary to run my application.
Step 2: In the docker-compose.yml file, make sure to change the indicated portion to the correct folder that the project folder is in.
    It is indicated in the file where to make the appropriate change.
Step 3: After making the change, run the containers. Run the command "docker-compose up -d" in the terminal in the folder location where the project is.
    Make sure to use -d, so it runs detached from the terminal.
Step 4: Run the following command in the terminal and copy the provided address where indicated in the connection.php file. This is necessary for the frontend to work properly.
    docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' phase3_mariadb_1
Step 5: Open a browser window and go to localhost:8080 . There you will encounter an Adminer page, which will help build the database. 
    Log in with the following credentials;  Userrname: root   Password: rootpwd
Step 6: Select the import button on the lefthand side of the screen. 
    From there, upload the dbSetup.sql file into the left upload box.
        (There are two different ways to upload, use the left option)
    Once it is loaded in, hit the execute button. Now the database is loaded!
Step 7: In your browser bar, navigate to localhost:3000 . Here is the frontend. It is ugly, I know, but the functionality is all there. 
    Play around with the different buttons, make some new players/courses, or try to shedule a tee time!

I also attached a screen recording on how to set it up as well. Please reach out if you need help:
    (314) 322 8588
    luke.schaefer@mail.missouri.edu
