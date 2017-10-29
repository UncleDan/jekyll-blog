---
id: 1650
title: 'Setting up an Android Build Server – Part 1: The Server « Donn Felker'
date: 2012-04-25T15:19:43+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=1650
permalink: /setting-up-an-android-build-server-part-1-the-server-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tech-heal-04-2012.html
categories:
  - Android
tags:
  - android
  - build server
  - setup
---
[http://blog.donnfelker.com/2010/10/21/setting-up-an-android-build-server-part-1-the-server/](http://www.donnfelker.com/)

> This post is the beginning of a series of posts that will outline how to create an Android build server using the <a href="http://hudson-ci.org/" target="_blank">Hudson Continuous Integration</a> (AKA: Hudson CI) software.
> 
> This post is part 1 in the series:
> 
> Posts:
> 
> * Part 1 – The Server (This post)
  
> * Part 2 – [Installing Hudson](http://www.donnfelker.com/)
  
> * Part 3 – [Installing the Android SDK](http://www.donnfelker.com/)
  
> * Part 4 – Communicating with GitHub (coming this week)
  
> * Part 5 – Creating a Hudson Build Job (coming this week)
> 
> In this post I’m going to outline the server and what I did in order to get the components installed in order to install Hudson CI.
> 
> ## The Server
> 
> I’m using a <a href="http://releases.ubuntu.com/lucid/" target="_blank">64 bit Ubuntu 10.04 LTS</a> headless server (no gui) that is hosted on <a href="http://chunkhost.com/" target="_blank">ChunkHost.com</a>with 256mb of Ram. The reason why I’m using a Linux box is simply because I was part of the ChunkHost.com beta testing program and I had a free box for almost a half year to test on. You can run Hudson CI on Windows as well if you prefer. However, this series is based around 64bit Ubuntu 10.04.
> 
> ## Hudson CI Requirements
> 
> Hudson is a Java program, therefore we need Java installed (if we’re building Android, then we also need Java for that, so its a double requirement). If you’ve played with Ubuntu 10.04 before, you may have noticed that **Java is NOT part of the default 10.04 install. **I’m not exactly sure of the reasons, but regardless, it sucks. Therefore you have to install it.
> 
> If you look on the web you’ll find tons of posts stating__
> 
> > _Just run ‘add-apt-repository “deb http://archive.canonical.com/ lucid partner”, and you’ll be golden, brother! Just run “apt update” and you’ll be good to go!
  
> >_ 
> 
> Yeah. Sure. Right. It wasn’t “that” easy. If you run that command you’ll probably get an error that looks something like this:
> 
> > add-apt-repository command not found
> 
> I received this error (above, this is not the exact error, but it looked something like that) and had to figure out how to get this command to work, so I could add the apt repository. Could I have edited the sources list manually? Yes. But I wanted to know how it worked. So I figured it out.
> 
> ### Adding add-apt-repository
> 
> To get the command, add-apt-repository, you have to add python-software-libraries to your system. To do that, run the following code:
> 
> <pre title="">apt-get install python-software-properties</pre>
> 
> Once that has been run, you’ll then be able to add java by adding the new repository.
> 
> ### Adding Java to Ubuntu 10.04
> 
> Now that we have the python software libraries I can add the new repository for apt to find java.
> 
> Add the the repository by doing this from the command line:
> 
> <pre title="">add-apt-repository "deb http://archive.canonical.com/ lucid partner"</pre>
> 
> This will add a new repository to the list of sources that APT will look in when its updating the system software.
> 
> You will now need to update the apt cache. Do that by running the following command:
> 
> <pre title="">apt-get update</pre>
> 
> You’re FINALLY ready to get Java installed on this server.
> 
> To install Sun’s Java, run the following command:
> 
> <pre title="">sudo apt-get install sun-java6-bin sun-java6-jre sun-java6-jdk</pre>
> 
> The command above installs Suns JRE, JDK and binaries.
> 
> You should see a Java EULA screen. Once it shows up, tab to <OK> and hit enter. Then arrow to the <Yes> and hit enter.
> 
> Sun Java should now install on your machine.
> 
> ## Wrap Up
> 
> In this post I took a Ubuntu 10.04 LTS headless server and got Java up and running on the machine. In the next post I’ll show you how to set up Hudson CI.

&nbsp;

<div class="container_share">
  <a href="http://www.facebook.com/sharer.php?u=http://www.danielelolli.it/setting-up-an-android-build-server-part-1-the-server-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tech-heal-04-2012.html&t=Setting up an Android Build Server – Part 1: The Server « Donn Felker" target="_blank" class="button_purab_share facebook"><span><i class="icon-facebook"></i></span>
  
  <p>
    Facebook
  </p></a> 
  
  <a href="http://twitter.com/share?url=http://www.danielelolli.it/setting-up-an-android-build-server-part-1-the-server-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tech-heal-04-2012.html&text=Setting up an Android Build Server – Part 1: The Server « Donn Felker" target="_blank" class="button_purab_share twitter"><span><i class="icon-twitter"></i></span>
  
  <p>
    Twitter
  </p></a> 
  
  <a href="https://plus.google.com/share?url=http://www.danielelolli.it/setting-up-an-android-build-server-part-1-the-server-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tech-heal-04-2012.html" target="_blank" class="button_purab_share google-plus"><span><i class="icon-google-plus"></i></span>
  
  <p>
    Google +
  </p></a> 
  
  <a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.danielelolli.it/setting-up-an-android-build-server-part-1-the-server-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tech-heal-04-2012.html&title=Setting up an Android Build Server – Part 1: The Server « Donn Felker" target="_blank" class="button_purab_share linkedin"><span><i class="icon-linkedin"></i></span>
  
  <p>
    Linkedin
  </p></a>
</div>