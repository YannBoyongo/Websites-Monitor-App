## About Website Monitoring and Management System

Overview

This Laravel-based Website Monitoring and Management System allows administrators to monitor the status of multiple websites and manage website details efficiently. The application checks whether websites are up or down by hitting their endpoints and returns a report of the sites that are down. Additionally, the system provides functionality to add, update, enable, or disable websites in the database, ensuring full control over website management.
Features

- Website Status Monitoring: Periodically checks the availability of multiple websites by sending requests and returns a list of websites that are down.
- Add Website: Allows administrators to add websites with details such as URL, email, and phone number.
- Update Website Details: Edit existing website information (URL, email, phone number).
- Enable/Disable Website: Toggle the is_up status to enable or disable websites.
- Database Management: All website details, including status, are stored in a database, providing persistence and easy management.

Technologies

Laravel Framework for backend logic and database management.
MySQL/PostgreSQL (or any other supported database) for data storage.
GuzzleHTTP for sending HTTP requests to check website statuses.

How It Works

Monitoring Websites: The system sends requests to a list of websites and checks their availability. If a website is down, it is added to a report.
Manage Websites: Admins can add, update, or remove websites via a simple interface. They can also enable or disable websites based on their current status.
Notification Placeholder: The system is structured in such a way that email or SMS notifications could be added easily if websites go down (currently returns a list of down websites).