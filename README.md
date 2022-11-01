## beer4peer - Voting App
A stupid voting app for the beer4peer Slack. This code is open source so that it's auditable and transparent. If you have any questions, please contact the Beer4Peer team.

This app tracks who votes, but not the actual vote.

Emails are not used, as they can in theory be tracked. Instead, Slack handles and logins are used.

## Installation

1. Clone the repo and `cd` into it
1. `composer install`
1. Rename or copy `.env.example` file to `.env`
1. `php artisan key:generate`
1. Setup a database and add your database credentials in your `.env` file
1. `php artisan migrate` or `php artisan migrate --seed` if you want seed data
1. `yarn`
1. `yarn dev`
1. `php artisan serve` or use Laravel Valet
1. Visit `localhost:8000` in your browser
