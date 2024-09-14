-- memberships Table
CREATE TABLE memberships (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(100),
    role VARCHAR(50),
    address VARCHAR(255)
);

-- Categories Table
CREATE TABLE Categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    description TEXT
);

-- Products Table
CREATE TABLE Products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10, 2),
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES Categories(category_id)
);

-- Sales Table
CREATE TABLE Sales (
    sale_id INT PRIMARY KEY AUTO_INCREMENT,
    sale_date DATE,
    total_amount DECIMAL(10, 2),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES memberships(user_id)
);

-- Sale Details Table
CREATE TABLE SaleDetails (
    sale_id INT,
    product_id INT,
    price DECIMAL(10, 2),
    quantity INT,
    PRIMARY KEY (sale_id, product_id),
    FOREIGN KEY (sale_id) REFERENCES Sales(sale_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

-- Orders Table
CREATE TABLE Orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    order_date DATE,
    shipping_method VARCHAR(100),
    shipping_time TIME,
    location VARCHAR(255),
    status VARCHAR(50),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES memberships(user_id)
);

-- Order Details Table
CREATE TABLE OrderDetails (
    order_id INT,
    product_id INT,
    order_type VARCHAR(50),
    size VARCHAR(50),
    quantity INT,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);
