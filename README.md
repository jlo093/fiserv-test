## Introduction

TBD

## Solution

The task specifically requests to create a **database design** to store the file/directory information. Personally, I would have preferred to use **Redis** over traditional databases, storing entries with a cache key consisting of a prefix i.e. "file:" and then a hash.


## Setup

For ease of use and testing I have provided a docker-compose configuration that'll spin up four containers:
- A php8.4 container
- A nginx container to serve requests
- A MySQL container
- An adminer container (tool to visually work with the database)

````sql
CREATE TABLE `filesystem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
````

## Supplementary Notes

I personally work with a Mac, without access to a Windows device. As such I haven't tested the solution on a Windows computer. If this was a real-life task/scenario, I would have set up a virtual machine to test the code under the slightly different filesystem Windows utilises.