# php-lumen
PHP 8 API + Lumen Framework.
This is a simple API solely built with the intent of learning Lumen Framework and use new features from PHP 8.

## Summary

- About
- Requirements
- Getting Started
  - Cloning & Building
  - Environment Set Up
- Documentation
- Next features

## About

The API is a simple financial system, designed to transfer money between users with some ruling involved.
We have two types of users, being them Legal and Natural ones. Natural users are civil people who can perform any type of money transaction, payments and deposits. And we have the Legal users, stores, enterprises, corporations, you name it. This users CANNOT transfer any money between Legal nor Natural users.
Last but not least, we have a notification system (WIP for now) that notifies the user when receiving any amount of money.

There it is, simple enough right? Now let's try it hands on!

## Requirements

To make this little project work properly you'll need some tools!

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/gettingstarted)
- [Git](https://git-scm.com/downloads)
- [Postman](https://www.getpostman.com/apps)

## Getting Started
### 1. Cloning & Building
  **1.1.** First you need to clone the repository:

  ```
   # git clone git@github.com:tiagoboschetti/php-lumen.git
   # git checkout master
  ```

  **1.2.** Then build & run the project using Docker Container:

  ```
   # docker-compose up --build -d
  ```
  ---

### 2. Environment Set Up
  **2.1.** Now we install the project dependencies:

  ```
   # docker exec -it php bash
   # composer install
  ```

  **2.2.** (Optional) I had some trouble with Guzzle 7 and had to include it manually, if you are getting some weird http errors, please execute following commands: 

  ```
   # docker exec -it php bash
   # composer require guzzlehttp/guzzle
  ```

  **2.3.** Running the tests:

  ```
   # docker exec -it php bash
   # composer test
  ```
  ---

### 3. Database Set Up
  **3.1.** Creating the database:

  ```
    # docker exec -it postgres bash
    # psql -U postgres
    # create database postgres;
    # \connect postgres;
    # \q
  ```

  **3.2.** Executing the migrations (creating our database structure):

  ```
    # docker exec -it php bash
    # cp .env.example .env
    # php artisan migrate;
  ```

  Now the Application should be up and running on localhost listening on port 9090. 
  
  ### Hooray!!

## 4. Documentation
  Now we need to know how to use the system. All of the postman collection, including examples for all requests, are in the `docs/postman` folder.
  We have some simple routes that make the cogs turn, and some rules that keep the system working properly.

  ### 4.1 Creating users.
  First of all, we need some users to use the system right!

  **Creating Natural Users:**

  **POST** -> `http://localhost:9090/user`

  Body:
  ```
    {
        "name": "Fujiwara Takumi",
        "email": "takumi.ae86@gmail.com",
        "type": "natural",
        "document_type": "cpf",
        "document_number": "64542524000"
    } 
  ```
  Intended response:

  ```
    {
        "data": {
            "name": "Fujiwara Takumi",
            "email": "takumi.ae86@gmail.com",
            "type": "natural",
            "document": {
                "type": "cpf",
                "numer": "64542524000"
            }
        }
    }
  ```
  ---

  **Creating Legal Users:**

  **POST** -> `http://localhost:9090/user`

  Body:
  ```
    {
        "name": "Fujiwara Tofu Shop",
        "email": "fujiwara@tofu.com",
        "type": "legal",
        "document_type": "cnpj",
        "document_number": "80687044000170"
    }
  ```
  Intended response:

  ```
    {
        "data": {
            "name": "Fujiwara Tofu Shop",
            "email": "fujiwara@tofu.com",
            "type": "legal",
            "document": {
                "type": "cnpj",
                "numer": "80687044000170"
            }
        }
    }
  ```
  ---

  ### 4.2 Deposit.
  
  Now we need to feed a little bit of money into the user's wallet, so that we can perform some transactions later.

  **POST** -> `http://localhost:9090/deposit`

  Body:
  ```
    {
        "amount": 5000,
        "payee": 1
    }
  ```
  Intended response:

  ```
    "Deposit successful!"
  ```
  ---

  ### 4.3 Transactions.

  **Natural to Legal user**

  **POST** -> `http://localhost:9090/transaction`

  Body:
  ```
    {
        "amount": 1000,
        "payer": 1,
        "payee": 2
    }
  ```
  Intended response:

  ```
    "Payment successful!"
  ```
  ---

  **Legal to Natural user;**

  **POST** -> `http://localhost:9090/transaction`

  Body:
  ```
    {
        "amount": 1000,
        "payer": 2,
        "payee": 1
    }
  ```
  Intended response:

  ```
    "Legal users cannot perform transactions."
  ```
  ---

  **Insufficient funds;**

  **POST** -> `http://localhost:9090/transaction`

  Body:
  ```
    {
        "amount": 10000,
        "payer": 1,
        "payee": 2
    }
  ```
  Intended response:

  ```
    "Insufficient balance!"
  ```

  ## Next Features

  - Email notification (WIP);
  - Deposit ruling;
  - Treat Deposits as transactions of different types (Payments and Deposits);
  - SQS for notification service;
  - Build retry job for notification service;
