## Research

When I started working on the task my first consideration was how to implement the recursive scanning over files and directories in a performant way. I considered both PHP's own iterator classes as well as a manual approach using scan_dir and alike to manually iterate over directories.

Doing a little testing and some research, it turns out that PHP iterators are significantly faster and less memory intense than doing the manual approach to iteration using scan_dir etc. The only issue I encountered was, that the iterators may throw an exception if they lack permission to access a directory (can be solved by graceful error handling with try-catch) and a concern, that if an iterator was to follow a symlink, this could cause an infinite loop - however - it turns out that iterators do by default **not** follow symlinks. So I decided to use the iterator classes PHP provides out of the box.

As for the database, I was decided to use MySQL for it's ease of use for a small test project like this + with the use of an index on the path, queries are actually fairly fast. I was considering that in a real-life scenario, Redis might have been an interesting layer to allow for caching and bypass the database completely, though I didn't think about that in too much detail and instead focuses on solving the task in a simple, pragmatic manner.

## Solution

I worked with PHP8.4 and to ensure I have a consistent environment to test in, I also built a docker-compose configuration and Dockerfile to build a number of containers to serve the project (php, nginx, mysql, adminer).

There's three main directories in the /src directory.

**Services** contains the main service classes **RecursiveFileReaderService**, **DatabaseService** and **ConfigService**. While the RecursiveFileReader handles most of the task-specific logic, I used the DatabaseService and ConfigService to build basic db-connection and config logic that would usually be present when using a framework.

I've used a repository pattern and a model to interact with the database. The reason is, that this way I could easily switch to another way of storing the file tree information (i.e. Redis, NoSQL databases, different SQL variant like PostgreSQL or MariaDB).

The **scripts** directory contains a script that can be called to generate a file structure as requested in the task and to fill up the database, as well as generate a text file. See **Setup** for details below.

The **public** directory contains the index.php that serves requests and shows the simplistic UI for searching file/directories. This is the directory served by nginx.

I'm using composer to load dependencies and handle autoloading. The only dependency used is PHPUnit. I started writing unit tests, but since time was limited I didn't add overly extensive coverage. In a real-life scenario I would have preferred adding more tests.

Everything is prepared to run out of the box following instructions below.

## Setup

Pre-requisite is having an installed Docker Desktop version.

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
UNIQUE KEY `path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
````

Run the provided script to create the requested ``.txt`` file and fill the database with file entries:

````
docker exec fiserv-test-php php scripts/createDataForTest.php
````

(You could in theory provided a path as an argument after the command, for example ``docker exec fiserv-test-php php scripts/createDataForTest.php
 /usr/var`` to let the script only recurse a specific path. Keep in mind that this way the script is running **inside** the Docker container, so all files read will be from there, not your local system).

This will build a file tree based on the files in the PHP container.

You can find the ``.txt`` file (**results.txt**) in the root of the project directory after execution.

You can find the web ui to search files here:  http://localhost/index.php

## Supplementary Notes

I personally work with a Mac, without access to a Windows device. As such I haven't tested the solution on a Windows computer. If this was a real-life task/scenario, I would have set up a virtual machine to test the code under the slightly different filesystem Windows utilises.