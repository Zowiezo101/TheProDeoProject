**IMPORTANT**

*This section might be difficult to follow for people who aren't familiar with version control software, web development or programming in general. If you are part of the ProDeo Website team, but have no programming knowledge, take your time to learn whatever is needed to help out. I highly suggest starting with free lessons from [Sololearn](https://www.sololearn.com/) and [W3 schools](https://www.w3schools.com/)* 

# What do you need
Before you start, I suggest having the following software installed on your computer:
* ***[Docker](https://www.docker.com/)***
* ***Any version control software that supports GIT***. I personally use [SmartGit](https://www.syntevo.com/smartgit/), as you can get a free hobby license for it. There are also IDEs that have integration with GIT, for example [Visual Studio Code](https://code.visualstudio.com/) also has the option to be used as version control software after manually downloading [GIT](https://git-scm.com/downloads).
* ***Any IDE (**I**ntegrated **D**evelopment **E**nvironment) that supports HTML, CSS, Javascript and PHP***. I suggest using [Visual Studio Code](https://code.visualstudio.com/) for this, as it has integrations for debugging and GIT. It also has an extension for Docker.

## Docker
The first step is to install Docker, as this allows you to actually test and run the website on your computer. After installation the PC will need to restart and then it will ask you to install WSL (Windows Subsystem for Linux). This is needed to make Docker work and needs to be installed. 

### Optional
- You can log in with a Docker account, but this is not required for Docker to work properly
- Some IDEs have an integration or extension to use Docker from the IDE itself. 

## Version Control Software
If you use Visual Studio Code, you do not necessarily need extra version control software. If you prefer to use another program for this, feel free to do so! If you do decide to use the version control software integrated in an IDE, there's a chance you need to manually load the submodules with the commands `git submodule init` and `git submodule update` in the main repository. This only needs to be done once for the submodules to be properly registered by the IDE

## IDE (**I**ntegrated **D**evelopment **E**nvironment)
This software will be used to read, write and test code. It's need to be an IDE that supports HTML, CSS, Javascript and PHP, as those are the languages used for the website. It's important that this IDE supports xDebug as well, as this is a technology used to actually test the code while it's active and running. Without this, it's a lot more difficult to test code.