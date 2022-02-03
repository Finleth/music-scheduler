[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

# Music Scheduler

## Description

This application integrates with TimeTree to provide automated musician schedule generation. It allows users to control the list of musicians, events, and created events all while providing a priority-based musician schedule assignment which is generated at the push of a button.

Built in Laravel 8, it can run on a simple web server serving up PHP and MySQL.

## Installation

### Requirements

- PHP 7.4
- Composer 2
- Laravel 8
- MySQL 8

### Setup

Clone the repository into your desired location. If you plan on making your own changes, be sure to create a fork of the repository first!

Within the application root directory, run the following command:

```bash
composer install
```

Once this is complete, create a copy of the `.env.example` file and rename it to `.env`. In this `.env` file, update the database configurations to your database and save.

With the `.env` set up, run the following commands one at a time:

```bash
php artisan key:generate
php artisan migrate
```

For my local server I used [Laravel Valet](https://laravel.com/docs/8.x/valet) but you can use any development environment of your choice.

Once you have your local server running, go to the URL pointing to this project and you should see the login page.

#### Connecting TimeTree

For the final steps of the setup, you will need to go to [TimeTree](https://timetreeapp.com/signup) and create an account if you haven't already. Once you've created an account and are logged in, create a [personal access token](https://timetreeapp.com/developers/personal_access_tokens). Give the token a name related to this application and give it read access to `calendar` and `event` and write access to `event`. Take this newly generated access token and save it into the `.env` TIME_TREE_TOKEN variable.

This next step to connect a TimeTree calendar is a brute force implementation and will be updated in the future to allow a seamless integration within the application UI itself.

To connect a TimeTree calendar to this application, you will have to create a TimeTree calendar and take the calendar ID which can be found in the URL when viewing said calendar in the browser like so: `https://timetreeapp.com/calendars/{id}`. With this calendar ID, manually create a record in the `time_tree_calendars` table with the values:

```json
{
    "time_tree_calendar_id": "your_calendar_id",
    "name": "name_of_your_calendar",
    "status": "active"
}
```

## Usage

To begin using this application you will need to create a user. Go to the `/register` route and fill in the inputs to create an account.

Once you're logged in you will need to create events. You will be required to give the title of the event and how often the event occurs.

After creating events you will need to add the musicians. Each musician can then be assigned to an event with the option to adjust their frequency so they get scheduled less frequently. Adding blackout dates for the musicians means the auto-generation will not schedule them during those dates. Adding instruments currently has no effect on the scheduling and is just for records.

Now you are ready to generate the schedule. Go to the calendar you wish to generate the schedule for and go to `Generate Schedule`. Here you can input the dates (dates are inclusive) and which events to schedule and click go! This will create a batch with the events that get created which you can view and confirm the schedule looks good. Once you've confirmed it looks good go to `Push Events to TimeTree`. Here you can select the batch you just created and push the events to TimeTree.

You can update the events at any point and they will be updated on TimeTree.

## Testing

To get the Laravel Unit Testing running you will first need to create a copy of your `.env` file and name it `.env.testing`. Replace the database credentials here with a database you can use for testing that is separate from your local development database.

To seed this database, you can run:

```bash
php artisan migrate --env=testing
```

Per the [Laravel docs](https://laravel.com/docs/8.x/configuration#additional-environment-files), passing the `--env` option tells Laravel to use a separare `.env` file suffixed with the parameter you pass.

To execute the tests, run:

```bash
php artisan test
```

Or to execute a specific feature test:

```bash
php artisan test --filter MusicianTest
```

Just replace `MusicianTest` with the name of the test you want to run.
