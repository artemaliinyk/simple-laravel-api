# Simple Laravel API

## Setup Instructions

### 1. Clone the repository
Clone this repository to your local machine

### 2. Install dependencies
Install the necessary dependencies using Composer:

`composer install`

### 3. Install dependencies
Create a copy of the .env.example file and rename it to .env:

`cp .env.example .env`

### 4. Configure the .env file
Open the .env file and configure the database settings and queue connection. Set the QUEUE_CONNECTION to database:

`DB_CONNECTION=mysql`

`DB_HOST=your_local_host (127.0.0.1)`

`DB_PORT=your_local_port (3306)`

`DB_DATABASE=your_database_name`

`DB_USERNAME=your_database_username`

`DB_PASSWORD=your_database_password`

`QUEUE_CONNECTION=database`

### 5. Generate the application key
Generate the application key using the following command:

`php artisan key:generate`

### 6. Run the migrations
Run the database migrations to create the necessary tables:

`php artisan migrate`

### 7. Start the queue worker
Start the queue worker to process jobs:

`php artisan queue:work`

### 8. Serve the application
Start the Laravel development server:

`php artisan serve`

## Testing

### API Endpoint: /api/submit
The /api/submit endpoint accepts a POST request with the following JSON payload structure:

`{
"name": "John Doe",
"email": "john.doe@example.com",
"message": "This is a test message."
}`


### Example Request (Using cURL)
You can test the API endpoint using tools like Postman or cURL.

#### 1. Open Postman
#### 2. Create a new POST request to http://127.0.0.1:8000/api/submit
#### 3. Set the request body to raw and select JSON
#### 4. Enter the JSON payload:
`{
"name": "John Doe",
"email": "john.doe@example.com",
"message": "This is a test message."
}`
#### 5. Send the request.

You should receive a response indicating that the submission was received and will be processed shortly.

## Running Tests

### Running Feature and Unit Tests
Laravel provides a simple way to run both feature and unit tests.

To run all tests, use the following command:

`php artisan test`

