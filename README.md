[Order API.postman_collection.json](https://github.com/user-attachments/files/22212004/Order.API.postman_collection.json)
# Orders API System
Order API system for managing orders and their status

# Prerequisites
To run this project, ensure you have the following installed:

PHP: >= 8.2
Composer: 2.x
MySQL: Compatible database (MariaDB recommended)
Docker: For running MySQL and Adminer (optional)

# Getting Started
Follow these steps to set up and run the Orders API system.
Setting Up the Environment

# Clone the Repository:
git clone https://github.com/slovn1k/orders_api_system
cd orders_api_system


# Install Dependencies:Run the following command to install PHP dependencies:
composer install


# Set Up Docker
    version: '3.9'
    services:
    mysql:
    image: 'mariadb:10.8.3'
    command: '--default-authentication-plugin=mysql_native_password'
    restart: always
    environment:
    MYSQL_ROOT_PASSWORD: root
    ports:
    - '3306:3306'
    adminer:
    image: adminer
    restart: always
    ports:
      - '8080:8080'
      vol[umes:
      sail-mysql:
      driver: local

# Start the Docker containers:
    docker-compose up -d

# Configure Environment:

    Copy the .env.example file to .env:cp .env.example .env

# Update the .env file with your database connection details, e.g.:DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=orders_api
    DB_USERNAME=root
    DB_PASSWORD=root

# Run Migrations and Seeders:Create the database structure and seed initial data:
    php artisan migrate
    php artisan db:seed

# Start the Laravel Server:
    php artisan serve

    The API will be accessible at http://localhost:8000.


# API Documentation
# The Orders API provides endpoints for managing orders, including creating, retrieving, listing, and updating orders.
# Create Order (POST /orders)
    Request:
    {
        "order_number": "Order4",
        "total_amount": 34.43,
        "tags": ["vip"],
        "items": [
            {
            "product_name": "Product 3",
            "quantity": 25,
            "price": 77.23
            }
        ]
    }

    Response (201):
    {
        "order_number": "Order4",
        "status": "pending",
        "total_amount": 34.43,
        "tags": [
            {
            "id": 1,
            "name": "Vip",
            "slug": "vip",
            "pivot": {
                    "added_at": "2025-06-23"
                    }
            }
        ],
        "items": [
            {
            "product_name": "Product 3",
            "quantity": 25,
            "price": 77.23
            }
        ]
    }

# Updated Order (PUT /order/{order_number})
    Request:
    {
        "status": "canceled",
        "tags": ["priority"]
    }

    Response (200):
    {
        "order_number": "Order4",
        "status": "canceled",
        "total_amount": 34.43,
        "tags": [],
        "items": []
    }

# Get Order (GET /orders/{order_number})
    Response (200):
    {
        "order_number": "Order4",
        "status": "pending",
        "total_amount": 34.43,
        "tags": [
                {
                "id": 1,
                "name": "Vip",
                "slug": "vip",
                "pivot": {
                        "added_at": "2025-06-23"
                        }
                }
        ],
        "items": [
            {
            "product_name": "Product 3",
            "quantity": 25,
            "price": 77.23
            }
        ]
    }

# Get all Orders (GET /orders)
# Parameters:

    status: Filter by order status (e.g., pending, shipped).
    tags[]: Filter by tags (e.g., tags[]=urgent&tags[]=vip).

    Response (200):
    [
        {
        "order_number": "Order4",
        "status": "pending",
        "total_amount": 34.43,
        "tags": [],
        "items": []
        }
    ]
    
    Update Order (PUT /orders/{order_number})
    Request:
    {
        "status": "canceled",
        "tags": ["vip"]
    }
    
    Response (200):
    {
        "order_number": "Order4",
        "status": "shipped",
        "total_amount": 34.43,
        "tags": [],
        "items": []
    }
