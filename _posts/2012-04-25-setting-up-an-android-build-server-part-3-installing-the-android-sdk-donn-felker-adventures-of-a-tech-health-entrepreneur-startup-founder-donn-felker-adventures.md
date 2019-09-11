---
id: 1654
title: 'Setting up an Android Build Server –  Part 3: Installing the Android SDK « Donn Felker'
date: 2012-04-25T15:23:28+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: old-wordpress-guid=1654
permalink: /2012-04-25-setting-up-an-android-build-server-part-3-installing-the-android-sdk-donn-felker-adventures-of-a-tech-health-entrepreneur-startup-founder-donn-felker-adventures.html
mytory_md_visits_count:
  - "77"
categories:
  - Android
tags:
  - android
  - build server
  - setup
---
[http://blog.donnfelker.com/2010/10/25/setting-up-an-android-build-server-part-3-installing-the-android-sdk/](http://www.donnfelker.com/)

> This post is part 3 in a series of posts of how to set up an Android build server.
> 
> Posts:
> 
> * Part 1 – [The Server](http://www.donnfelker.com/)
  
> * Part 2 – [Installing Hudson](http://www.donnfelker.com/)
  
> * Part 3 – [Installing the Android SDK](http://www.donnfelker.com/) (this post)
  
> * Part 4 – Communicating with GitHub
  
> * Part 5 – Creating a Hudson Build Job
> 
> In this post I’m going to show you how to install the Android SDK on an 64 bit Ubuntu 10.04 LTS headless server (no gui).
> 
> Note: I did this about 2 months ago. If you see any typos or find any errors, please comment so I can fix them. Thanks!
> 
> ## Requirements
> 
> **You will need to be running a machine which is capable of running X windows as I will be tunneling X windows through SSH in this section. I’m running a Mac OS X Snow Leopard and have had no problems with this.If you’re running any Linux Gui Workstation you should not have any issues (I assume).**
> 
> **You can also accomplish the same thing (from what I’m told) with PLink and PuTTy on a Windows operating system however I have never attempted it.**
> 
> **If you do accomplish this with PuTTY and PLink, please comment so others can bask in the glory that you have accomplished! ![:)](http://blog.donnfelker.com/wp-includes/images/smilies/icon_smile.gif)
  
>** 
> 
> # Installing the Android SDK
> 
> In order to install the Android SDK, you need to have access to the GUI. At the time of writing, there is now way to install the SDK without the gui (major downfall IMO).
> 
> Since I’m working on a headless server (no gui to work with) I need to tunnel the gui components to my local ssh connection where I do have X windows. As stated in the requirements, I’m running a Mac OS X Snow Leopard machine.
> 
> ### 32 Bit Compatibility
> 
> The Android SDK currently supports 32 bit, not 64 bit. Therefore you will need to install a library before you can run the Android SDK. These library will allow you to run 32 bit programs in the 64 bit environment (if you’re running in 64 bit, that is).
> 
> To install these libraries, run the following command:
> 
> <pre title="">apt-get install ia32-libs</pre>
> 
> This will install the ia32-libs libraries. We can now run the Android SDK (shown below).
> 
> ### Downloading the Android SDK
> 
> We now need to downlaod the Android SDK. To do so, execute the following on the server:
> 
> <pre title="">cd ~

wget http://dl.google.com/android/android-sdk_r07-linux_x86.tgz</pre>
> 
> This .tgz file is from this url:<a href="http://d.android.com/sdk/index.html" target="_blank"> http://d.android.com/sdk/index.html</a>
> 
> wget is a command that will execute and go and get a file from the internet and copy it locally.
> 
> Once you’ve downloaded it locally and extracted it, follow STEPS 1 and 2 on the Android SDK set up, <a href="http://d.android.com/sdk/installing.html" target="_blank">located here</a>. We will be installing the necessary SDK versions in the sections that follow.
> 
> ### Gui Components
> 
> To install specific Android SDK’s we need to work with the Android GUI, and in order to do that we need to tunnel gui windows to your local machine through ssh -X (these are windows that would normally show up on a workstation or a headed server – server with a gui). To do this, you will need to install a library that allows the headless server to create some of the windowing infrastructure.
> 
> To add this library, run the following command:
> 
> <pre title="">apt-get install libswt-gtk-3.5-jni</pre>
> 
> ### Tunneling via SSH
> 
> The next part of this adventure begins with tunneling via SSH. But before we continue, a little background …
> 
> The Android SDK requires that you install the SDK from a GUI. There is no CLI for the SDK. If you’re on a headless server (a server without a gui) then you’re going to be up a creek. This is where tunneling via SSH comes into play. What you can do is tell your terminal window (in my case a terminal window on OSX) to use the -X option ssh. What does the -X option do? Take a look from the man page:
> 
> -X      Enables X11 forwarding.  This can also be specified on a per-host basis in
  
> a configuration file.
> 
> X11 forwarding should be enabled with caution.  Users with the ability to
  
> bypass file permissions on the remote host (for the user’s X authorization
  
> database) can access the local X11 display through the forwarded connec-
  
> tion.  An attacker may then be able to perform activities such as keystroke
  
> monitoring.
> 
> For this reason, X11 forwarding is subjected to X11 SECURITY extension
  
> restrictions by default.  Please refer to the ssh -Y option and the
  
> ForwardX11Trusted directive in ssh_config(5) for more information.
> 
> X11 is basically the X Window system. By providing the -X switch we are asking SSH to bring the windows from the server to the local machine. If you’re running on a Linux maching (or OSX) you can do this with no problem. If you’re on Microsoft Windows, you’ll have to do some PuTTy and PLink magic. I’m not sure how to do it on Window though, but if you do, please post in the comments below.
> 
> By enabling X window tunneling, we can now launch the Android SDK from the server and bring it down to the server.
> 
> To connect with SSH tunneling you will need to start a new SSH connection with this command:
> 
> <pre title="">ssh -X username@yourserver.com</pre>
> 
> Next, navigate to your Android SDK installation folder, then navigate into the tools/ directory.
> 
> _**Note: When you tunnel windows via SSH, it will be SLOWWWWWW. Don’t expect super fast response time. If you click a window, or a button, or anything, it can take many seconds for it to respond. Therefore, before you continue, be ready to spend a good hour or so in front of the computer while the SDK downloads/installs on the server.**_
> 
> Now type:
> 
> <pre title="">./android update sdk</pre>
> 
> This will start the Android SDK manager and it will look something like this:
> 
> [<img title="Screen shot 2010-10-22 at 1.31.32 PM" src="http://blog.donnfelker.com/wp-content/uploads/2010/10/Screen-shot-2010-10-22-at-1.31.32-PM-300x173.png" alt="" width="300" height="173" />](http://blog.donnfelker.com/wp-content/uploads/2010/10/Screen-shot-2010-10-22-at-1.31.32-PM.png)
> 
> Notice how the screens resemble the X Window system? This is because your tunneling the windows from a headless server down to your local machine.
> 
> Please remember, this stuff takes forever to download. So be VERY VERY VERY patient. Once you install your SDK’s you wont have to do it again until a new version comes out. So no need to fret, this only has to happen once!
> 
> Slowly go through the steps to install the SDK’s you need. The X Windows you’re using will download the files onto the server where the SDK needs them to be (platforms/ and add-ons/ directories).
> 
> ## Wrap Up
> 
> In this post you installed the necessary 32bit compatibility libraries and gui components needed to run the X Window system. After that you downloaded the SDK’s that you needed on your build server. In the next section we’ll tell Hudson how to get files from GitHub when they change (on a polling basis). After that we’ll go into actually building the Android app after the sources are cloned/fetched locally.