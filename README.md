# TXT.com

## Description

TXT.com is a web application for a play theater where you can purchase tickets, concessions, view upcoming plays and seatings.

## Table of Contents

- [Installation](#Installation)
- [Dependencies](#Dependencies)
- [Contributing](#Contributing)
- [Deployment](#Deployment)
- [License](#License)

## Installation

1. Clone the repository:

    ```bash
    $ git clone https://github.com/vinschzen/proyek-fai.git
    $ cd project
    ```

2. Install PHP dependencies using Composer:

    ```bash
    $ composer install
    ```

3. Set up Laravel environment:

    ```bash
    $ cp .env.example .env
    $ php artisan key:generate
    ```

    Update the `.env` file with your database and other configurations.
## Dependencies

This project relies on the following external libraries and frameworks:

- **Laravel Framework:** The web application framework used for building the project. [Laravel Repository](https://github.com/laravel/laravel)

- **Midtrans API:** Used for payment processing. [Midtrans Repository](https://github.com/midtrans/midtrans-api)

- **Firebase Realtime Database and Firestore:** Cloud-based databases used for storing and retrieving data. [Firebase Repository](https://github.com/kreait/laravel-firebase)

- **Tailwind CSS:** A utility-first CSS framework used for styling the project. [Tailwind CSS Repository](https://github.com/tailwindcss/tailwindcss)

## Contributing

Contributions are always welcome!

See `contributing.md` for ways to get started.

Please adhere to this project's `code of conduct`.


## Deployment

To deploy this project run

```bash
   php artisan serve
```


This project is deployed and hosted on [Vercel](https://vercel.com/). Any changes pushed to the `main` branch will automatically trigger a deployment.

You can access the live version of the project at [proyek-fai-t4z6-azure.vercel.app](proyek-fai-t4z6-azure.vercel.app).



## License

[MIT](https://choosealicense.com/licenses/mit/)

