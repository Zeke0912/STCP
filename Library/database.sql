-- Create the database
CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- Create Books table
CREATE TABLE Books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(13) NOT NULL UNIQUE,
    quantity INT NOT NULL DEFAULT 1,
    published_year YEAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Borrowers table
CREATE TABLE Borrowers (
    borrower_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Loans table
CREATE TABLE Loans (
    loan_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    borrower_id INT NOT NULL,
    loan_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES Books(book_id),
    FOREIGN KEY (borrower_id) REFERENCES Borrowers(borrower_id)
);

-- Add indexes for better performance
CREATE INDEX idx_books_title ON Books(title);
CREATE INDEX idx_books_author ON Books(author);
CREATE INDEX idx_loans_dates ON Loans(loan_date, due_date, return_date);

-- Insert sample data
INSERT INTO Books (title, author, isbn, quantity, published_year) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', '9780743273565', 2, 1925),
('To Kill a Mockingbird', 'Harper Lee', '9780446310789', 3, 1960),
('1984', 'George Orwell', '9780451524935', 4, 1949);

INSERT INTO Borrowers (first_name, last_name, email, phone, address) VALUES
('John', 'Doe', 'john.doe@email.com', '123-456-7890', '123 Main St'),
('Jane', 'Smith', 'jane.smith@email.com', '098-765-4321', '456 Oak Ave'),
('Bob', 'Johnson', 'bob.johnson@email.com', '555-555-5555', '789 Pine Rd'); 