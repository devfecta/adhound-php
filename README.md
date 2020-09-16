# AdHound Project (PHP)
This repository contains branches of the PHP version(s) that was developed over the years going back to version 1.0 that what used briefly in the real world. After that, I used it as a class project for a PHP and Java class.

The most extensive details can be found in the Wiki for the Java project I did in the spring of 2019.
[Project Details (Wiki)](https://github.com/kkelm/adhound/wiki)

AdHound™ empowers media distribution professionals with the most powerful and customizable advertisement placement tracking system on the market. Offering you and your clients accurate performance and accounting reports.

Forged by a talented team and extensive research of the media distribution industry AdHound™ converts the complexities of media distribution tracking into a powerful yet easy to use tool.

The team at AdHound™ recognizes that media distribution isn't a one person job. So the team has integrated the ability for media distribution professionals to assign multiple users/assistants under their account, and assign those users with specific viewing and editing privileges.

## Project Technologies/Techniques
- PHP 5.x and 7.x
- MySQL 5.x
- JavaScript
- jQuery
- Bootstrap CSS
- Stripe

## Project Details
This project was built from the database on up. I started with laying out the database schema using Draw.io to make a basic Entity Relationship Diagram (ERD), and then created SQL files in the project's database directory that could be imported into the database. These SQL files also contained test data that I could use throughout the development process.

Another consideration for this project was "mobile first". One of the goals for this application was to have in run on phones and tablets, so I incorporated Bootstrap CSS to make it easier for the application to adjust to certain screen sizes.

Also, in previous versions of the project I used a poor MVC design pattern, so I used this as an opportunity to approve upon it using jQuery to call a primitive API.
