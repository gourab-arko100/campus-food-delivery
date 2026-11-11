# Campus Food Delivery System

A PHP and MySQL-based campus food delivery web application designed for university students to browse available food items, manage a shopping cart, place orders, and track order history. The system also includes an admin panel for managing menu items and customer orders.

## Features

### Customer
- User registration and login
- Secure password hashing
- Browse available menu items
- Add food items to cart
- Increase/decrease item quantities
- Remove items from cart
- Checkout and place orders
- Select payment method
- View order confirmation
- View previous orders and order status

### Admin
- Admin dashboard
- Add new menu items
- Edit menu items
- Delete menu items
- View customer orders
- Update order status

### Technical
- PHP
- MySQL
- PDO with prepared statements
- Session-based authentication
- Password hashing
- CSRF token protection
- Responsive Bootstrap interface
- Relational database design

## System Architecture

The application follows a simple modular PHP structure:

```text
Browser
   |
   v
PHP Web Application
   |
   +---- Authentication
   |
   +---- Customer Module
   |       +---- Menu
   |       +---- Cart
   |       +---- Checkout
   |       +---- Orders
   |
   +---- Admin Module
   |       +---- Menu Management
   |       +---- Order Management
   |
   v
MySQL Database
```

## Database Design

The database is organized around the following major entities:

- `users` — authentication and user roles
- `customers` — customer-specific information
- `menu` — available food items
- `cart` — active shopping-cart items
- `orders` — customer orders
- `order_items` — individual items belonging to an order
- `payments` — payment information associated with orders

### ER Diagram

The original project was designed around a relational ER model connecting users/customers, menu items, carts, orders, order items, and payments.

An ER diagram can be added here later if you want to include the original diagram in the repository.

## Project Structure

```text
food_delivery_php/
├── admin/
│   ├── index.php
│   ├── menu.php
│   └── orders.php
├── assets/
│   └── style.css
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── config/
│   ├── config.php
│   └── database.php
├── customer/
│   ├── cart.php
│   ├── checkout.php
│   ├── order_success.php
│   └── orders.php
├── database/
│   └── schema.sql
├── includes/
│   ├── footer.php
│   ├── header.php
│   └── helpers.php
├── uploads/
│   └── .gitkeep
├── .gitignore
├── index.php
└── README.md
```

## Technologies Used

| Technology | Purpose |
|---|---|
| PHP | Backend and server-side application logic |
| MySQL | Relational database |
| HTML5 | Page structure |
| CSS3 | Styling |
| Bootstrap | Responsive UI |
| JavaScript | Client-side interactions |
| PDO | Secure database access |

## Main User Flow

```text
Register / Login
       |
       v
Browse Menu
       |
       v
Add Items to Cart
       |
       v
Review Cart
       |
       v
Checkout
       |
       v
Select Payment Method
       |
       v
Place Order
       |
       v
View Order History
```

## Admin Flow

```text
Admin Login
    |
    v
Dashboard
    |
    +---- Manage Menu
    |       +---- Add
    |       +---- Edit
    |       +---- Delete
    |
    +---- Manage Orders
            +---- View
            +---- Update Status
```

## Running Locally

This repository is primarily intended to be uploaded to GitHub as a project portfolio/repository.

If you later want to run the application locally, it requires a PHP server and MySQL database. The SQL schema is provided in:

```text
database/schema.sql
```

The database connection settings can be configured in:

```text
config/config.php
```

## Admin Account

For testing, a registered user can be promoted to administrator by changing the user's role in the database:

```sql
UPDATE users
SET role = 'admin'
WHERE email = 'your@email.com';
```

## Security Considerations

The reconstructed application includes several basic security practices:

- Passwords are stored using PHP password hashing.
- Database queries use PDO prepared statements.
- Session-based authentication is used for protected pages.
- CSRF tokens are used for important form submissions.
- User roles are checked before accessing admin functionality.

For production deployment, additional security hardening, input validation, rate limiting, secure cookie configuration, and production payment integration would be recommended.

## Payment

The current project represents payment methods at the application/database level. It does **not** integrate with a real payment gateway.

Supported example payment methods include:

- Cash on Delivery
- bKash
- Card

These are demonstration options for the academic project and should not be considered real payment processing.

## Screenshots

The original screenshots of the project are no longer available.

To keep the repository honest, no fabricated screenshots are included. Screenshots can be added later if the application is run locally and new screenshots are captured.

Suggested section for future screenshots:

```text
docs/
└── screenshots/
    ├── home.png
    ├── login.png
    ├── menu.png
    ├── cart.png
    ├── checkout.png
    └── admin-dashboard.png
```

## Future Improvements

Possible improvements include:

- Real payment gateway integration
- Food category filtering
- Restaurant/vendor management
- Food image upload and management
- Search functionality
- Customer reviews and ratings
- Order notifications
- Delivery tracking
- Better admin analytics
- REST API integration
- Deployment to a cloud hosting platform

## Academic Project

This project was developed as a university-level web application project to demonstrate:

- Web application development
- PHP backend programming
- MySQL database design
- CRUD operations
- Authentication and authorization
- Session management
- E-commerce-style cart and checkout flow
- Relational database relationships

---

## 👨‍💻 Author

**Gourab Krishna Saha**

Computer Science & Engineering student | Web Development | Software Projects

If you find this project interesting, consider giving the repository a ⭐.

---
