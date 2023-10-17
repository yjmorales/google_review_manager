# Google Review Link Generator & Manager.

### It's already online: [Manage My Review](http://managemyreview.com) 

> http://managemyreview.com

## Overview

### Subsystems

This project is a web application to generate and manage businesses Google Review Links  
based on their location.

The two subsystems being part of this project are:

- **Generator** Single webpage available on internet for all unauthenticated users for
  the generation of reviews links.
- **Manager:** Only accessible via `/login`  path. It manages the already created Google Review Links.

### How the review links are delivered?

A Google Review Link is a URL used to leave a review of a business or place.
Once the user access the **generator** page it fist enters the place location. The system will
find the correct address, and then it is asked to enter a valid email address to send the review.
Once both of them, the place address and the contact email address are correctly provided, the
system generates the review link and display it. Also, an email is sent with the same information.

A QR Code is generated holding the link. That QR code can be used by the business owners to
print it and locate it in the business to gain more review amounts.

## Technologies

- Symfony
- jQuery
- Sendgrid
- OpenAPI
- Google reCaptchaV3
- Google Place API

## Installation

### Packages

Once the project is cloned via `git` or copied via `ftp` or directly on a filesystem the following must me executed.

    # cd google_review_manager 
    # composer install 
    # npm install  
    # gulp run  

For security reasons the `.env` file is not included in this repo. It's a task of the server
where this project will be deployed to create it and correctly secure it with the right file permissions.
There are mandatory environmental variables that need to be created in that file:

### Environment

**APP_ENV:** The environment where the system will run.

**APP_SECRET:** This is a string that should be unique to your application.
It's commonly used to add more entropy to security related operations.
Its value should be a series of characters, numbers and symbols chosen randomly and the recommended length is around 32
characters.
Reference: https://symfony.com/doc/current/reference/configuration/framework.html#secret

**DATABASE_URL:** Database string connection

**DATABASE_URL_CONSOLE:** _(Optional)_ Database string connection for development
environment using docker. All the operations performed on the terminal like commands require to use this value as string
database connection. The parameter
DATABASE_URL should be disabled.

**MAILER_DSN:**    Mailer DSN value. This system works using SendGrid as email server provider.

### Values of the environmental variables

To secure the used api keys and other sensitive information **never** the values are typed on the .env file.
Instead, it is being used [Secrets](https://symfony.com/doc/current/configuration/secrets.html), a Symfony feature.
The secrets hold the real value of the env variables.

It's responsibility of the developer to create the secrets for each env.

All the secrets should be created by running the following command:

    php bin/console secrets:set SECRET_NAME_EAMPLE

See secrets [doc](https://symfony.com/doc/current/configuration/secrets.html) for further information.

For security reason the file `config/secrets/dev/dev.decrypt.private.php` for dev env and
`config/secrets/prod/prod.decrypt.private.php` for production env
are not part of the delivered code. They are used to decrypt sensitive information.

It's responsibility of the developer to copy the files into the `config/secrets/dev` and `config/secrets/prod`
directories respectively on the server.
**Note:** Those files are excluded in the `.gitignore` file.

The variables to save as secrets are:

- **GOOGLE_MAP_API_KEY**: Holds the project Google API Key for developers.
- **SENDGRID_API_KEY**: Holds the SendGrid API key used to send emails via SendGrid API.
- **SENDGRID_INTERNAL_TO_EMAIL**: Holds an internal email address used as administrator email. This is useful when it’s
  required to generate an email to the system administrator.
- **SENDGRID_SENDER_EMAIL**: An email address used as sender. All the emails will be sent by this sender.
- **SYS_ADMIN_EMAIL**: The email address of the system administrator. Useful for contact.
- **SYS_ADMIN_LINKEDIN_PROFILE_URL**: Holds the system administrator or system’s owner profile on Indeed.
- **SYS_ADMIN_PERSONAL_PAGE_URL**: Holds the administrator personal page or organization web URL.
- **SYS_ADMIN_PHONE**: Holds the main contact phone.
- **REDIS_HOST**: Holds the redis host machine.
- **REDIS_PORT**: Holds the redis port on the host machine.
- **REDIS_PASSWORD**: Holds the redis password.
- **RECAPTCHA_V3_GRM_SITE_KEY** Holds the site key used by Google reCaptcha V3
- **RECAPTCHA_V3_GRM_SECRET_KEY** Holds the secret key used by Google reCaptcha V3
- **GOOGLE_MEASUREMENT_ID** Holds the Google Analytic key.

### Where to save the QR Code Images respective to the Google Review Links.

Create a folder:

    mkdir -p  public/google_reviews/qr_codes

### Migrations: 

The following will build the database structure:

    php bin/console doctrine:migrations:migrate

### Contact


> **Yenier Jimenez**
<br>
http://yenierjimenez.net
<br>
yjmorales86@gmail.com

