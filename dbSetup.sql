  --   Phase 3 DB Setup File
  --   Luke Schaefer 18186970
  
    CREATE SCHEMA GOLF;
    USE GOLF;

    CREATE TABLE MEMBERS (
        Name VARCHAR(32) NOT NULL,
        Handicap INT,
        Member_id INT NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (Member_id),
        UNIQUE KEY (Name),
        CONSTRAINT Handicap CHECK (Handicap BETWEEN -20 AND 50)
    );
    ALTER TABLE MEMBERS AUTO_INCREMENT=100;

    CREATE TABLE COURSES (
        Name VARCHAR(32) NOT NULL,
        Course_id INT NOT NULL AUTO_INCREMENT,
        Difficulty ENUM("Beginner", "Intermediate", "Pro"),
        PRIMARY KEY (Course_id),
        UNIQUE KEY (Name)
    );
    ALTER TABLE COURSES AUTO_INCREMENT=200;

    CREATE TABLE PROS(
        Name VARCHAR(32) NOT NULL,
        Pro_id INT NOT NULL AUTO_INCREMENT,
        Course_id INT NOT NULL,
        PRIMARY KEY (Pro_id),
        UNIQUE KEY (Course_id),
        FOREIGN KEY (Course_id) REFERENCES COURSES (Course_id)
    );
    ALTER TABLE PROS AUTO_INCREMENT=300;

    CREATE TABLE TEE_TIMES(
        Teetime DATETIME NOT NULL,
        Member_id INT NOT NULL,
        Course_id INT NOT NULL,
        PRIMARY KEY(Teetime, Course_id),
        FOREIGN KEY (Course_id) REFERENCES COURSES (Course_id),
        FOREIGN KEY (Member_id) REFERENCES MEMBERS (Member_id)
    );

    CREATE TABLE RECENT_SCORES(
        Score INT NOT NULL,
        Member_id INT NOT NULL,
        Date DATE NOT NULL,
        PRIMARY KEY (Member_id, Date),
        FOREIGN KEY (Member_id) REFERENCES MEMBERS (Member_id),
        CONSTRAINT Score CHECK (Score BETWEEN 0 AND 200)
    );

    CREATE TABLE HOLES (
    Course_id INT NOT NULL,
    Hole_number INT NOT NULL,
    Par INT NOT NULL,
    Distance INT NOT NULL,
    PRIMARY KEY (Course_id , Hole_number),
    FOREIGN KEY (Course_id)
        REFERENCES COURSES (Course_id),
    CONSTRAINT Hole_number CHECK (Hole_number BETWEEN 1 AND 18),
    CONSTRAINT Par CHECK (Par BETWEEN 2 AND 6),
    CONSTRAINT Distance CHECK (Distance BETWEEN 0 AND 1000)
    );

    CREATE TABLE MEMBERSHIPS (
    Member_id INT NOT NULL,
    Course_id INT NOT NULL,
    Rate DECIMAL(5 , 2 ),
    PRIMARY KEY (Member_id , Course_id),
    FOREIGN KEY (Member_id)
        REFERENCES MEMBERS (Member_id),
    FOREIGN KEY (Course_id)
        REFERENCES COURSES (Course_id),
    CONSTRAINT Rate CHECK (Rate > 0)
    );
    
    INSERT INTO MEMBERS (Name, Handicap) VALUES
		("Phil Mickelson", 1),
        ("Tiger Woods", -1),
        ("Rory Mcllroy", 0),
        ("Luke Schaefer", 16);
	
    INSERT INTO COURSES (Name, Difficulty) VALUES
		("Agusta National", "Pro"),
        ("Pebble Beach", "Intermediate"),
        ("The Landings", "Beginner");
	
    INSERT INTO PROS (Name, Course_id) VALUES
		("Johnny Test", 200),
        ("Finn Jones", 201),
        ("Mordecai Riggly", 202);
	
    -- YYYY-MM-DD HH:mm:SS
    INSERT INTO TEE_TIMES VALUES
		("2022-06-22", 100, 200),
        ("2022-07-12", 101, 200);
    
    INSERT INTO RECENT_SCORES VALUES
        (72, 100, "2022-06-22"),
        (71, 101, "2022-07-12");

    INSERT INTO HOLES VALUES
        (200, 1, 4, 450),
        (200, 2, 4, 475),
        (200, 3, 5, 525),
        (200, 4, 4, 425),
        (200, 5, 3, 185),
        (200, 6, 4, 400),
        (200, 7, 4, 415),
        (200, 8, 4, 430),
        (200, 9, 4, 455),
        (201, 1, 4, 450),
        (201, 2, 4, 475),
        (201, 3, 5, 525),
        (201, 4, 4, 425),
        (201, 5, 3, 185),
        (201, 6, 4, 400),
        (201, 7, 4, 415),
        (201, 8, 4, 430),
        (201, 9, 4, 455),
        (202, 1, 4, 450),
        (202, 2, 4, 475),
        (202, 3, 5, 525),
        (202, 4, 4, 425),
        (202, 5, 3, 185),
        (202, 6, 4, 400),
        (202, 7, 4, 415),
        (202, 8, 4, 430),
        (202, 9, 4, 455);

    INSERT INTO MEMBERSHIPS VALUES  
        (100, 200, 94.99),
        (101, 200, 94.99),
        (102, 201, 110.00),
        (103, 202, 44.99);
        
        
	-- SELECT M.Name, TT.Datetime, C.Name
--     FROM MEMBERS AS M, TEE_TIMES AS TT, COURSES AS C
--     
        