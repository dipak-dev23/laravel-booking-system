# Booking System – Practical Assignment

### Overview

This project implements a simple booking system on top of a fresh Laravel 12 installation. It was built as a practical assignment with the following goals:

- User registration and login with email verification
- Non-duplicate bookings with overlap rules for full day, half day, and custom time bookings
- Consideration for performance with ~10k bookings per day and 1M+ existing records

### Tech Stack

- **Framework:** Laravel 12 (PHP 8.2)
- **Database:** MySQL (configurable via `.env`)
- **Auth:** Laravel authentication with `MustVerifyEmail`
- **Views:** Blade templates with a simple card-based layout

### Setup Instructions

1. **Install dependencies**

   ```bash
   composer install
   ```

2. **Environment configuration**

   Copy `.env.example` to `.env` and generate the app key:

   ```bash
   php artisan key:generate
   ```

   Update database and mail settings in `.env`, for example:

   ```env
   APP_URL=http://127.0.0.1:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=booking_system
   DB_USERNAME=root
   DB_PASSWORD=your_password

   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=booking@example.com
   MAIL_FROM_NAME="Booking System"
   ```

3. **Run migrations**

   ```bash
   php artisan migrate
   ```

4. **Serve the application**

   ```bash
   php artisan serve
   # then open http://127.0.0.1:8000
   ```

### Implemented Features

#### Authentication & Email Verification

- Registration fields: `first_name`, `last_name`, `email`, `password`, `password_confirmation`
- Validation: required fields, email format, minimum password length, `unique:users,email`, password confirmation
- `User` model implements `MustVerifyEmail`
- After registration, the user is logged in and a verification email is sent
- Until `email_verified_at` is set, login is blocked and user is redirected to the verify page
- Routes:
  - `GET/POST /register`
  - `GET/POST /login`
  - `POST /logout`
  - `GET /email/verify`, `GET /email/verify/{id}/{hash}`, `POST /email/verification-notification`
- `guest` middleware protects `/login` and `/register` so authenticated users are redirected to the booking dashboard

#### Booking Module

After successful login and email verification, users are redirected to the booking form:

- `GET /booking` – show booking form
- `POST /booking` – create booking

Booking form fields:

- `customer_name`
- `customer_email`
- `booking_date` (date)
- `booking_type` (`full_day`, `half_day`, `custom`)
- `booking_slot` (`first_half`, `second_half`) – visible only for half-day type
- `start_time`, `end_time` – visible only for custom type

#### Data Model & Performance

- `bookings` table:
  - `user_id`, `customer_name`, `customer_email`
  - `booking_date` (DATE)
  - `booking_type` (`full_day`, `half_day`, `custom`)
  - `booking_slot` (`first_half`, `second_half`, nullable)
  - `start_time`, `end_time` (TIME, nullable)
- Indexes:
  - `booking_date`
  - `booking_date, booking_type`
  - `booking_date, start_time, end_time`

These indexes help keep lookups efficient even with ~1M bookings and 10k new bookings per day.

#### Overlap Rules

Half-day slot ranges (assumption used in logic):

- First half: 09:00–13:00
- Second half: 14:00–18:00

Rules enforced:

- Full day booking blocks any other booking on that date
- Half day booking:
  - Rejected if a full day exists on that date
  - Rejected if another half day with the same slot exists
  - Rejected if any custom booking overlaps the half-day range
- Custom booking:
  - Requires `start_time < end_time`
  - Rejected if a full day exists
  - Rejected if any custom overlaps the time window
  - Rejected if any half-day range overlaps the time window

These rules are implemented with date + time range queries that leverage the defined indexes.

