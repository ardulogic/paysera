## Setup
* `composer install`
* `php artisan key:generate`
* `php artisan migrate:fresh --seed`
* `npm install`
* `npm run build`
* `php artisan serve`

## Run tests
* `php artisan test`

## My Concerns

* We should implement configurable slot duration
* Use WebSockets, Echo, or FireBase for realtime UI updates
* Improve UI for working hours
* Add Captchas / security, api throttling for submitting requests
* Validate appointment duration
* Add services table and provide a dropdown when making the appointment
* Appointment email confirmation
* We only disable dates by working hours to improve performance, but could potentially use cache and display dates as disabled when all slots are taken on that day
* We could use a better database instead of SQLite
* Frontend could be cleaner, styles could be more organised.
* We should add proper timezone handling

## Bottomline
* This is not the code I would put in production. But due to time concerns this is what I have.
