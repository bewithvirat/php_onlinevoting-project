Online Voting System
An online voting system built with PHP, MySQL, Bootstrap, CSS, HTML, JavaScript, and jQuery. This system enables users to participate in elections securely and conveniently.



Features
User Registration and Login: Allows users to create accounts and log in securely.
Voting Mechanism: Users can cast votes for candidates in various elections.
Admin Panel: Admins can manage elections, candidates, and view results.
Real-time Vote Counting: Votes are counted in real-time, providing immediate results.
Responsive Design: Built with Bootstrap for a mobile-friendly interface.
Multiple Election Management: Support for managing multiple elections simultaneously.
Technologies Used
Frontend:

HTML
CSS
Bootstrap
JavaScript
jQuery
Backend:

PHP
Database:

MySQL
Installation
To set up the online voting system using WAMP, follow these steps:

Clone the Repository:

bash
Copy code
git clone https://github.com/bewithvirat/php_onlinevoting-project.git
Navigate to the Project Directory:

bash
Copy code
cd php_onlinevoting-project
Start WAMP:

Ensure that WAMP or XAMPP is running on your machine.
Create a MySQL Database:

Open phpMyAdmin (typically at http://localhost/phpmyadmin).
Create a new database named online_voting.
Import the Database Schema:

Import the database.sql file located in the database directory:
Select the online_voting database.
Click on the "Import" tab and upload database.sql.
Configure Database Connection:

Open config.php in the project root and update the database credentials:
php
Copy code
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'onlinevotingsystem.sql');
Access the Application:

Place the project folder in the www directory of your WAMP/XAMPP installation (e.g., C:\wamp\www\php_onlinevoting-project).
Open your web browser and navigate to http://localhost/php_onlinevoting-project.
Usage
Register: Create an account using the registration form.
Login: Use your credentials to log in.
Vote: Navigate to the voting section and cast your vote.
Admin Access: Admins can manage elections and view results from the admin panel.


Contributing
We welcome contributions to improve this project! Please fork the repository, create a new branch, and submit a pull request.
