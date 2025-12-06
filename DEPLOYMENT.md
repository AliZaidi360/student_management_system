# How to Deploy to InfinityFree

This guide will help you deploy your Student Management System to InfinityFree hosting.

## Prerequisites
- An InfinityFree account.
- A created hosting account (domain name).
- FTP Client (like FileZilla) or use the "Online File Manager" in InfinityFree.

## Step 1: Database Setup

1.  **Log in to InfinityFree Client Area** and go to the **Control Panel** (vPanel).
2.  Click on **MySQL Databases**.
3.  **Create a New Database**:
    - Enter a name (e.g., `sms`).
    - Click **Create Database**.
    - **Note down** the following details shown on the page:
        - **MySQL Host Name** (usually something like `sql123.infinityfree.com`) -> *Wait, actually InfinityFree usually tells you to use the IP or specific host, but often `localhost` works if the script is on the same server, BUT usually they provide a specific hostname like `sql300.infinityfree.com`. CHECK YOUR VPANEL.*
        - **MySQL Database Name** (it will have a prefix like `if0_345678_sms`).
        - **MySQL Username** (e.g., `if0_345678`).
        - **MySQL Password** (This is your vPanel password).

4.  **Import the SQL File**:
    - Go back to the Control Panel main page.
    - Click on **phpMyAdmin**.
    - Click on the **Connect Now** button next to your new database.
    - In phpMyAdmin, click on the **Import** tab.
    - Click **Choose File** and select the `database.sql` file from your project folder.
    - Click **Go** at the bottom.
    - *Success! Your tables are now created.*

## Step 2: Configure the Application

1.  Open `php/connection.php` on your computer.
2.  Update the variables with the details you noted down in Step 1:

    ```php
    $servername = "sql300.infinityfree.com"; // EXAMPLE - Check your vPanel for the real host!
    $username = "if0_345678";                // Your MySQL Username
    $password = "your_vpanel_password";      // Your vPanel Password
    $dbname = "if0_345678_sms";              // Your full Database Name
    ```
3.  Save the file.

## Step 3: Upload Files

1.  Open the **Online File Manager** from the InfinityFree Control Panel (or use FileZilla).
2.  Navigate to the `htdocs` folder.
    - *Note: Delete the default `index2.html` or similar files if they exist.*
3.  **Upload** all your project files and folders (`css`, `php`, `index.html`, etc.) into the `htdocs` folder.
    - *Do not upload `database.sql` or this `DEPLOYMENT.md` file if you don't want to, but it doesn't hurt.*

## Step 4: Test

1.  Open your website URL in a browser.
2.  Try adding a student or logging in.
3.  If you see "Connection failed", double-check your credentials in `php/connection.php`.

## Troubleshooting

- **White screen / 500 Error**: Check the "Alter PHP Config" in vPanel and enable "Display Errors" to see what's wrong.
- **Database Connection Error**: Ensure the `$servername` is correct. It is rarely `localhost` on InfinityFree; it's usually a specific URL provided in the MySQL Databases section.
