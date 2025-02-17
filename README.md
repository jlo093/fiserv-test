## Introduction

TBD

## Solution

TBD

## Setup

For ease of use and testing I have provided a docker-compose configuration that'll spin up four containers:
- A php8.4 container
- A nginx container to serve requests
- A MySQL container
- An adminer container (tool to visually work with the database)

After creating the containers using:
````
docker compose up -d
````

Navigate to:
http://localhost:8080/?server=fiserv-test-mysql&username=test&db=fiserv

Use the password: ``test``

Use the ``SQL command`` feature of ``adminer`` to execute the following SQL:

````sql
CREATE TABLE `filesystem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
````

Run the provided script to create the requested ``.txt`` file and fill the database with file entries:

````
docker exec fiserv-test-php php scripts/createDataForTest.php
````

This will build a file tree based on the files in the PHP container.

You can find the ``.txt`` file in the root of the project directory after execution.

You can find the web ui to search files here:  http://localhost/index.php

## Supplementary Notes

I personally work with a Mac, without access to a Windows device. As such I haven't tested the solution on a Windows computer. If this was a real-life task/scenario, I would have set up a virtual machine to test the code under the slightly different filesystem Windows utilises.