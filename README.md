<<<<<<< HEAD

# ResQher - SOS Alert & Live Location Tracking

ResQher is a web application designed to help users in distress by allowing them to send SOS alerts. Other users, including volunteers and law enforcement, can track the user's live location and offer assistance.

## Features

- **Admin Panel**: Manages users and oversees alerts.
- **User Types**: 4 types of users – Admin, User, Volunteer, Law Enforcement.
- **SOS Alerts**: Users can create an SOS alert when in danger.
- **Live Location Tracking**: Other users can track the user's location in real-time.
- **MySQL Database**: Stores user data, SOS alerts, and location information.

## Server Requirements

- PHP >= 8.3
- MySQL Database

## Installation

### Prerequisites

Ensure you have the following installed:

- PHP >= 8.3
- Composer
- MySQL

### Steps

1. **Clone the repository**

```bash
git clone https://github.com/iamsuzon/resqher.git
cd resqher
```

2. **Install PHP dependencies**

```bash
composer install
```

3. **Set up your environment file**

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

4. **Generate the application key**

```bash
php artisan key:generate
```

5. **Set up the database**

Update your `.env` file with the correct database connection settings.

Run the migrations to set up the database tables:

```bash
php artisan migrate
```

6. **Serve the application**

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

## Usage

Once the application is up and running, you can:

- Admin: Manage users and oversee alerts.
- Users: Create SOS alerts and track other users’ locations.
- Volunteers/Law Enforcement: Receive alerts and track users' live location to offer assistance.

### Routes

- `/` – Home page (user login page)
- `/admin` – Admin dashboard
- `/alert` – Create and manage SOS alerts
- `/track` – Track live location of a user

## Contribution

Feel free to fork the repository and submit pull requests for new features or bug fixes.

## License

This application is open-source and available under the [MIT License](LICENSE).
=======
# ResQher
A Women safety app
>>>>>>> 3b34ce3da511da6c4e15c5453c2e587ffc26756c
