
## Setup

Clone the repo
```
git clone https://github.com/sarveshcchauhan/loop-webshop
```

Install the dependencies 

```
composer install
```

Now generate an application key for your project and steup database name inside your .env file and create the database and run migration file

```
php artisan key:generate
php artisan migrate
```

Now to export the customer and products data to database you can see the progress bar for the completed data also check the Logs file for the inserted data in storage/logs

```
php artisan import:customer
php artisan import:product
```

Once all the data is inserted 
Check the APIs

## APIs Endpoints

Add your server name ie. http://127.0.0.1:8000/ or localhost in below ENDPOINTS

To test the EndPoints open Postman add the method and url in Body tab choose x-www-form-urlencoded and add parameters over there 

| Method | Endpoint | Description | Parameters |
| ------ | ------ | ----- | ----- | 
| POST | /order | create a new order | customer => email, Get email from customer table to create orders |
| POST | /order/show|  show the order based on id | id => id, Pass id parameter to get the specified order |
| PUT | /order | Update the specified order | id => id, customer => email |
| DELETE | /order | Delete the specified order | id => id |
| POST | /order/{id}/add | Add products to specified order if payed is null by default payed is NULL | product_id => product_id |
| POST | /order/{id}/pay | Update the order of payed based on https://superpay.view.agentur-loop.com/pay result |   |