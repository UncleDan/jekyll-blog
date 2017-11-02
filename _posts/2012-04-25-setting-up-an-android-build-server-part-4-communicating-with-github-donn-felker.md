---
id: 1661
title: 'Setting up an Android Build Server – Part 4: Communicating With Github « Donn Felker'
date: 2012-04-25T19:52:34+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=1661
permalink: /setting-up-an-android-build-server-part-4-communicating-with-github-donn-felker-04-2012.html
categories:
  - Android
  - Linux
  - PC
  - Tech
---
[http://blog.donnfelker.com/2010/10/26/setting-up-an-android-build-server-part-4/](http://www.donnfelker.com/)

> This post is part 3 in a series of posts of how to set up an Android build server.
> 
> Posts:
> 
> * Part 1 – [The Server](http://www.donnfelker.com/)
  
> * Part 2 – [Installing Hudson](http://www.donnfelker.com/)
  
> * Part 3 – [Installing the Android SDK](http://www.donnfelker.com/)
  
> * Part 4 – Communicating with GitHub (this post)
  
> * Part 5 – Creating a Hudson Build Job
> 
> In this post I’m going to show you how to install set up communication between an 64 bit Ubuntu 10.04 LTS headless server (no gui) with GitHub.
> 
> Note: I did this about 2 months ago. If you see any typos or find any errors, please comment so I can fix them. Thanks!
> 
> ## Communicating with GitHub
> 
> I’m using git for my version control repository, and I’m hosting it at <a href="http://www.github.com/" target="_blank">GitHub.com</a>. Sure, there are<a href="http://www.projectlocker.com/" target="_blank">some</a> <a href="http://www.unfuddle.com/" target="_blank">free</a> git hosting sites, but I prefer to use GitHub because when I work with a team of developers or an offsite client, I can fire up a repository (public or private if you have a paid account) and everyone can connect quite easily. In the mobile world, most users are familiar with Git (at least in my experience).  While this is all well and dandy, what we really need is a way for Hudson CI to know when someone has checked in some new code. To do that, Hudson must be able to speak to GitHub through the server. In order to do that we’ll have to do the following:
> 
>   * Install Git on the server
>   * Set up an SSH Key for the ‘hudson’ user in the system.
>   * Initialize a repository in the Hudson work area.
>   * Tell Hudson where the repository is at so it can poll for changes.
> 
> ### Installing Git on the Server
> 
> Thankfully, installing Git on the server is quite painless. To do so, run the following command once you’re logged into your server:
> 
> <pre title="">apt-get install git-core</pre>
> 
> This will install git into your server. You may have to log out and log back in for your PATH variables to be updated. Once the PATH variables are updated you can type “git” and you will see a list of available options.
> 
> ### Setting up the SSH Key
> 
> When you work with Git, you have to get familiar with SSH and SSH keys. This is pretty easy when you’re working on your workstation or a regular server with a normal user id. In the Step 2, we set up Hudson as a CI server, and Hudson runs under a locked down ‘hudson’ account (aka: non-interactive – meaning you cannot log in as ‘hudson’). Therefore creating an SSH key for this little guy gets tricky. Here’s how to do it.
> 
> #### Back Up the ‘hudson’ Users Existing SSH Key
> 
> Before we create a new SSH key for the ‘hudson’ user, you’ll want to back up the existing one. To do so, type the following command:
> 
> <pre title="">cp /var/lib/hudson/.ssh/id_rsa* /var/lib/hudson/.ssh/key_backup/</pre>
> 
> This will copy the public and private key pair into a backup folder (just in case you need it in the future for any reason).
> 
> #### Creating a new SSH Key
> 
> I’m going to log into the server and then ‘su’ to hudson and then I’ll follow the GitHub.com Help pages for setting up an SSH key and I’ll add that key to my allowed SSH Keys in GitHub, on the project web page.
> 
> First things first, log into your server. Now, you’re going to ‘su’ into the ‘hudson’ user account. Type the following to su into the hudson account:
> 
> <pre title="">su hudson</pre>
> 
> Now follow the steps in the [GitHub.com Help Page for creating SSH Keys](http://help.github.com/linux-key-setup/).
> 
> #### Setting The Servers Git Identity
> 
> When the server communicates with GitHub, we want the build server to have a known identity. Therefore we’ll need to set the Git username and email for the user. Type the following into the command prompt to set them (replacing example.com with your domain):
> 
> <pre title="">git config --global user.name "Build Server"

git config --global user.email "build@example.com"</pre>
> 
> Now, when the server communicates with GitHub it will be known as “Build Server” and will have the email you specified.
> 
> #### Connecting to GitHub
> 
> Finally, we need to connect to GitHub to have the server set up its initial RSA keys with the GitHub servers. To do that, type the following:
> 
> <pre title="">ssh git@github.com</pre>
> 
> You will be prompted to verify if you want to connect. Type “yes” and press enter. You will the be connected and immediately disconnected from Github.com’s servers. At this point, the ‘hudson’ user can now talk to GitHub!
> 
> ## Wrap Up
> 
> In this post you installed Git and connected it to GitHub.com after setting up your SSH Keys. In the next step, I’ll show you how to create a new Hudson build job and how to have hudson poll GitHub.com for changes in the source. If a change is detected, the source will be pulled down to the server.