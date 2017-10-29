---
id: 1652
title: 'Setting up an Android Build Server – Part 2: Installing Hudson « Donn Felker'
date: 2012-04-25T15:20:32+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=1652
permalink: /setting-up-an-android-build-server-part-2-installing-hudson-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tec-04-2012.html
categories:
  - Android
tags:
  - android
  - build server
  - setup
---
[http://blog.donnfelker.com/2010/10/22/setting-up-an-android-build-server-part-2-installing-hudson/](http://www.donnfelker.com/)

> This post is part 2 in a series of posts of how to set up an Android build server.
> 
> Posts:
> 
> &nbsp;
> 
>   * Part 1 – <a href="http://www.donnfelker.com/" target="_blank">The Server</a>
>   * Part 2 – Installing Hudson (this post)
>   * Part 3 – [Installing the Android SDK](http://www.donnfelker.com/)
>   * Part 4 – Communicating with GitHub (coming this week)
>   * Part 5 – Creating a Hudson Build Job (coming this week)
> 
> &nbsp;
> 
> In this post I’m going to show you how to install <a href="http://hudson-ci.org/" target="_blank">Hudson CI</a> on a 64bit Ubuntu 10.04 LTS headless server (no gui).
> 
> &nbsp;
> 
> _Note: I did this about 2 months ago. If you see any typos or find any errors, please comment so I can fix them. Thanks!_
> 
> &nbsp;
> 
> # Installing Hudson
> 
> &nbsp;
> 
> Once you have Java installed you can install Hudson. Hudson can be installed as a Debian package, and that’s what I’m going to do here. You may want to look at the hudson installation documents as these instructions may be out of date (depending on when you found this site) or if you’re using a non-debian system.
> 
> &nbsp;
> 
> The instructions on Hudson’s site can be found here:
  
> http://wiki.hudson-ci.org/display/HUDSON/Installing+Hudson
> 
> &nbsp;
> 
> Installing Hudson as a Debian package offers a few benefits:
> 
> &nbsp;
> 
>   * Automatic upgrade of Hudson via apt
>   * /etc/init.d hook up to start Hudson on boot
> 
> &nbsp;
> 
> Here’s how I added Hudson CI to my Ubuntu 10.04 LTS server.
> 
> &nbsp;
> 
> From the command line, type the following:
> 
> &nbsp;
> 
> <pre title="">wget -O /tmp/key http://hudson-ci.org/debian/hudson-ci.org.key
sudo apt-key add /tmp/key</pre>
> 
> &nbsp;
> 
> This will add a temporary key to the Hudson CI Debian repository and the second line will add the repository to the list of sources.
> 
> &nbsp;
> 
> Now, it’s as simple as typing the following:
> 
> &nbsp;
> 
> <pre title="">wget -O /tmp/hudson.deb http://hudson-ci.org/latest/debian/hudson.deb
sudo dpkg --install /tmp/hudson.deb</pre>
> 
> &nbsp;
> 
> The first line gets the hudson.deb package file.
> 
> &nbsp;
> 
> The second line installs Hudson.
> 
> &nbsp;
> 
> #### Troubleshooting
> 
> &nbsp;
> 
> Right after I ran:
> 
> &nbsp;
> 
> <pre title="">wget -O /tmp/hudson.deb http://hudson-ci.org/latest/debian/hudson.deb
 sudo dpkg --install /tmp/hudson.deb</pre>
> 
> &nbsp;
> 
> I received an error:
> 
> &nbsp;
> 
> hudson: Depends: daemon but it is not installed
> 
> &nbsp;
> 
> To fix this, you will need to install the hudson deamon by running the following command:
> 
> &nbsp;
> 
> <pre title="">apt-get install hudson daemon</pre>
> 
> &nbsp;
> 
> Now, re-run the following and you shouldn’t have any issues.
> 
> &nbsp;
> 
> <pre title="">wget -O /tmp/hudson.deb http://hudson-ci.org/latest/debian/hudson.deb
sudo dpkg --install /tmp/hudson.deb</pre>
> 
> &nbsp;
> 
> Updating Your Hudson Install
> 
> &nbsp;
> 
> Once you’re Hudson CI is installed, its best for you to update it to make sure you have all the patches and updates possible. To get those updates, run the following command:
> 
> &nbsp;
> 
> <pre title="">apt-get update; apt-get install hudson</pre>
> 
> &nbsp;
> 
> This will update Hudson and make sure that Hudson is installed and up to date.
> 
> &nbsp;
> 
> ## Wrap Up
> 
> &nbsp;
> 
> Hudson is now installed. You should be able to go to your web browser and hit port 8080 on the server, such as http://example.org:8080 and Hudson will load. If you want Hudson to run under port 80, please see the Hudson documentation for instructions. I’m running mine on port 8080 with no problems. You should see a screen that looks similar to this:
> 
> &nbsp;
> 
> [<img class="alignleft" style="border-image: initial; border-width: 5px; border-color: black; border-style: solid;" title="hudson" src="http://blog.donnfelker.com/wp-content/uploads/2010/10/hudson-300x209.jpg" alt="" width="300" height="209" />](http://blog.donnfelker.com/wp-content/uploads/2010/10/hudson.jpg)
> 
> &nbsp;
> 
> In the next post I’ll install the Android SDK and download all of the necessary SDK’s (Android versions) that we need to build our applications.
> 
> &nbsp;

<div class="container_share">
  <a href="http://www.facebook.com/sharer.php?u=http://www.danielelolli.it/setting-up-an-android-build-server-part-2-installing-hudson-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tec-04-2012.html&t=Setting up an Android Build Server – Part 2: Installing Hudson « Donn Felker" target="_blank" class="button_purab_share facebook"><span><i class="icon-facebook"></i></span>
  
  <p>
    Facebook
  </p></a> 
  
  <a href="http://twitter.com/share?url=http://www.danielelolli.it/setting-up-an-android-build-server-part-2-installing-hudson-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tec-04-2012.html&text=Setting up an Android Build Server – Part 2: Installing Hudson « Donn Felker" target="_blank" class="button_purab_share twitter"><span><i class="icon-twitter"></i></span>
  
  <p>
    Twitter
  </p></a> 
  
  <a href="https://plus.google.com/share?url=http://www.danielelolli.it/setting-up-an-android-build-server-part-2-installing-hudson-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tec-04-2012.html" target="_blank" class="button_purab_share google-plus"><span><i class="icon-google-plus"></i></span>
  
  <p>
    Google +
  </p></a> 
  
  <a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.danielelolli.it/setting-up-an-android-build-server-part-2-installing-hudson-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures-of-a-tec-04-2012.html&title=Setting up an Android Build Server – Part 2: Installing Hudson « Donn Felker" target="_blank" class="button_purab_share linkedin"><span><i class="icon-linkedin"></i></span>
  
  <p>
    Linkedin
  </p></a>
</div>