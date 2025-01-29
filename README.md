# TheProDeoProject
This repository is a shelved and unfinished project of mine, waiting for a new owner who likes to continue the project! It started out as a dream to create a game with factual correct historical information about the Bible, but the factual correct history part has gotten so back that I needed a database to store all the information in. Since I wanted to share this information, I decided to start working on a website that displayed all this info in a "human readable way" with timelines, familytrees, worldmaps and tables. 

Aside from wanting to make a game, I also wanted this to be a gift to God. Something to help people realise that the Bible is more than a dusty old book abused to justify many bad christian behavior, hate, rasicm and other trash. It is a collection of historical stories, rewritten many times with customs and behaviors of a different time. A time that it very different from the time we're currently living in and thus not always suitable to be taken literally.

I believe that God is not the same for everyone, since everyone is different and has different needs. He's not a one-size-fits-all solution, but a person with His own desires and thoughts. It's important to learn who you are and who God is to you.

## Why is it shelved?
I lost sight of my original plan of making a game and having fun myself. I am a perfectionist and it costed me about 10 years to realise I was reaching way too high for a project ran by a single person. So I needed to make the tough decision of pruning away some projects, until the game itself was left. I've tried to package the database/website project as neatly as possible, with as much documentation on the code, environment and goals as I can.

## Two parts
The ProDeo Project is divided into two larger parts, the database and the website. Both have their own development environment that can be created by using Docker. This makes it a lot easier to get all the necessary tools needed to run both projects from your own computer with minimal effort.

### Database
The database contains the actual information itself. All the Bible books, people, locations and some information that doesn't fit exactly in those categories. The database is a MySQL database, but using the development environment it can be imported to and exported from a set of text files in a human readable format.

### Website
The website is the tool used to visualize the database in a way that is much easier to understand than a bunch of tables and rows. I've written some code to generate a timeline and a familytree with the data in the datbase, so it can be easily filled up and edited with more recent data.

## How to set it up
1. Clone this repository
2. Copy ./website/sample.env and rename the copy to .env
3. Fill in any username and password you would like to use for the database. These will be used when creating the database server for the development environment
4. Repeat steps 2 and 3 with ./database/sample.env. The database credentials for the `./database` environment don't have to be the same as those for the `./website` environment as these databases do not communicate.
5. go to ./website and run the command `docker compose up --build` to build the website development environment
6. go to ./database and run the command `docker compose up --build` to build the database development environment

You now have the bare bones development set-up to start working on both projects, though you will need to do some more work to have all components working properly. 
* For the Google Maps API on the website, you will need to generate an API key. How to do this, is explained [here](https://developers.google.com/maps/documentation/javascript/get-api-key). Without this key, the worldmap data will be loaded, but not the map.
* To have the contact form working properly, you need to insert login credentials to be used for sending emails. If you have a Google account, this won't work by using your own credentials. You can create a special password [here](https://myaccount.google.com/apppasswords) to be used by PHPMailer and use smtp.gmail.com as `EMAIL_HOST`.

## Help out
Do you want to help out or adopt the project? Please contact me! This project is really special to me and I want to be sure any contributors or possible new owners will be a good fit for this project.

## Check the Wiki
The Wiki contains more information on how the directory is structured, how the code works and other detailed information.