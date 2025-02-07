# FinTrack-Ledger

FinTrack-Ledger is a comprehensive financial accounting software designed to help businesses manage their finances effectively. This application provides core accounting features like invoicing, payroll, expenses, and tax management. It is built with a focus on scalability, multi-tenancy, and security.

## Features

- **Multi-Tenant Architecture**: Supports multiple companies, with each clients (company) having its own users.
- **Invoicing**: Create, track, and manage invoices, with auto-generated invoice numbers.
- **Payroll**: Process payroll for employees, including salary payments and tax deductions.
- **Expense Management**: Record and categorize business expenses.
- **Tax Management**: Maintain tax data, with flexible tax configurations such as income tax, social security, and pension.
- **Reporting**: Generate detailed financial reports.
- **Authentication**: Secure user login and management with role-based access control.

## Technologies Used

- **Backend**: Laravel 11 for Api Development
- **Database**: MySQL
- **Other Services**: AWS S3, Docker (for development and production), Redis (for caching)... todo.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/fintrack-ledger.git
   cd fintrack-ledger
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up your `.env` file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

5. Start the application:
   ```bash
   php artisan serve
   ```

## API Endpoints

- **Invoices**: Manage invoices.
- **Payroll**: Create and update payroll records.
- **Expenses**: Manage company expenses.
- **Taxes**: Store and update tax configurations for payroll.
  
<!-- For detailed API documentation, coming soon -->

## Contributing

Contributions are welcome! Please follow the standard pull request workflow:
- Fork the repository
- Create a new branch for your feature/bugfix
- Commit your changes
- Open a pull request



Included:
- Added CI/CD pipeline. (Git Actions workflow)
- Pint for php linting
