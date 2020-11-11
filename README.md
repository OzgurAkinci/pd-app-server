# PD Application Server - Codeigniter 4

Please follow my instructions:

Step 1 - Create Database

Please create a database named ci4_auth_jwt

Step 2 - Migrate Table Users

Follow this command through the terminal:

php spark migrate

Step 3 - Testing

Follow this command through the terminal:

php spark serve

Then open the postman software with the post method.

Register URL:

localhost:8080/auth/register

Login:

localhost:8080/auth/login

Home:

localhost:8080/home

When accessing home must include a token in the header and use get method.

Enjoy!
