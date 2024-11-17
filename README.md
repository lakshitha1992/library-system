# Library Management System

## Overview

The Library Management System is a web application that allows librarians and users to manage books, borrowing and lending processes, and user registrations. It provides an interface for users to borrow books, return them, and view their borrowing history, while librarians can manage users, books, fines, and more.

## Features

- **User Management**: Admins can manage users, including registration, login, and profile updates.
- **Book Management**: Admins can add, update, and categorize books. Users can search and view books in the library catalog.
- **Borrowing & Lending**: Users can borrow and return books. Admins can view the borrowing status and manage overdue fines.
- **Fines & Penalties**: Track unpaid fines and allow users to pay them.
- **Notifications**: Send notifications for due dates, fines, and other updates.
- **Digital Resources**: Manage and provide access to digital books or materials.

## Technology Stack

- **Frontend**: HTML, CSS, Bootstrap 5
- **Backend**: PHP
- **Database**: MySQL

## Database Schema

### 1. **Users Table**

Stores information about library users (both admins and regular users).

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

2. Categories Table
Stores book categories (e.g., Fiction, Non-Fiction, Science, etc.).

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);
3. Books Table
Stores information about books, including title, author, genre, ISBN, and category.

sql
Copy code
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,
    publication_date DATE NOT NULL,
    language VARCHAR(50) NOT NULL,
    cover_image BLOB, -- For storing cover images
    category_id INT,
    status ENUM('available', 'borrowed') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
4. Fines Table
Tracks fines for users, including the amount and status (paid/unpaid).

sql
Copy code
CREATE TABLE fines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('paid', 'unpaid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
5. Borrowed Books Table
Tracks books borrowed by users, including borrow and return dates.

sql
Copy code
CREATE TABLE borrowed_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    borrow_date DATE NOT NULL,
    return_date DATE,
    fine_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (fine_id) REFERENCES fines(id) ON DELETE CASCADE
);
Setup Instructions
1. Database Setup
Clone or download the project files.
Create a MySQL database using the provided SQL commands.
Import the database schema into MySQL.
sql
Copy code
CREATE DATABASE LibrarySystem;
USE LibrarySystem;

-- Then run all SQL commands from above
2. Backend Setup
Install a local server environment like XAMPP or MAMP.
Place the project files into the web server's root directory (htdocs for XAMPP).
Ensure that the navi.php and footer.php files are correctly referenced.
3. Frontend Setup
The frontend is designed with Bootstrap 5 to ensure a responsive and user-friendly design.

Admin Login: Allows the admin to manage users, books, and inventory.
User Login: Allows users to browse books, borrow them, and view their borrowing history.
4. Configuration
Make sure to update the connection settings in PHP files to match your MySQL server configuration.

php
Copy code
$host = 'localhost';
$dbname = 'LibrarySystem';
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password
File Structure
plaintext
Copy code
/LibrarySystem
│
├── /assets
│   ├── /img       # Images used in the UI (e.g., book cover images)
│   └── /css       # Custom CSS files
├── /includes
│   ├── footer.php  # Footer content
│   ├── navi.php    # Navigation bar content
│   └── db.php      # Database connection file
├── /admin
│   ├── admin-home.php  # Admin home page
│   ├── admin-book-cataloging.php  # Add a new book
│   └── admin-registration.php  # User management page
├── /user
│   ├── user-home.php  # User home page
│   └── user-borrow-book.php  # Page for borrowing books
├── index.php  # Main entry page
└── README.md  # This file
Contributing
Feel free to fork the repository, create an issue, or submit a pull request. Please follow the project's code style and contribute respectfully.

License
This project is licensed under the MIT License - see the LICENSE file for details.

Author
Lakshitha D. Hemasinghe LinkedIn Profile
css
Copy code


This `README.md` file provides a detailed overview of the system, setup instructions, and how to use the provided PHP and MySQL code for the Library Management System. You can expand or modify the sections as needed.
```
