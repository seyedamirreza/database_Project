# Travel Ticket Booking System

This is a PHP-based backend server for a travel ticket booking system, supporting transportation by air, train, and bus. It includes user authentication, ticket search, reservation, cancellation, payment, and admin reporting features.

## Technologies Used

* **PHP** (without ORM)
* **MySQL** as the database
* **Redis** for caching and temporary storage (e.g., OTP codes)
* **JWT (JSON Web Token)** for user authentication

## Project Structure

* **Controllers**:

  * `UserController`: Handles user sign-up, sign-in via OTP, verification, profile editing.
  * `TicketController`: Supports ticket searching (with Redis caching), reservation, cancellation, and admin review.
  * `PaymentController`: Handles reservation payments.
  * `ReportController`: Enables users to submit reports.

* **Routes**:
  Defined in `routes/web.php`, with middleware (like `VerifyCsrfToken`) disabled for API endpoints.

## Endpoints

### 🔐 Authentication

* `POST /signUp`: User registration
* `POST /signIn`: Request OTP
* `POST /verifySignIn`: Verify OTP
* `PUT /editUser`: Edit user profile

### 🎫 Tickets

* `GET /getCityTicket`: Get list of all cities used in tickets
* `GET /searchTicket`: Search tickets by filters (origin, destination, date, vehicle type, etc.)
* `GET /getdetailTicket`: Get detailed ticket info
* `POST /reserveTicket`: Reserve a ticket
* `GET /getTicketUserBooking?mood=booked|cancelled`: User's tickets by booking status

### 💳 Payment

* `POST /createPayment`: Pay for a reservation

### 🚫 Cancellation

* `POST /showCancelValue`: Check cancellation fee (10% of ticket price)
* `POST /cancelTicket`: Cancel a reservation and refund to user's wallet

### 📋 Reports

* `POST /createReport`: Create report for a reservation

### 🛠️ Admin

* `GET /adminTicketManagement?filter=all|cancelled|reported|suspicious`: Admin overview of reservations

## Running the Project Locally

1. Clone the repo
2. Set up `.env` and configure MySQL database
3. Run migrations to create tables
4. Make sure Redis server is running
5. Serve the project using Laravel's local server or Apache

## Sample Test Cases (for Postman)

Test cases include user registration, OTP login, ticket reservation, payment, and cancellation. Ready-to-import JSON collection is provided for convenience.

## Notes

* Redis is used to cache search results (30 min expiry) and store temporary OTP passwords.
* Expired reservations are automatically cleaned up.
* Passwords are hashed before storing.

---

Let us know what else you'd like to add — such as team info, screenshots, or deployment notes!
