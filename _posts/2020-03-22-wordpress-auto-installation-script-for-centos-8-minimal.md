---
title: WordPress Auto Installation Script for CentOS 8 (Minimal)
date: 2020-03-22T16:00:00+00:00
author: Daniele Lolli (UncleDan)
layout: post
permalink: /2020-03-22-wordpress-auto-installation-script-for-centos-8-minimal.html
categories:
  - Web Development
  - Linux
  - WordPress
  - Centos
  - Bash
  - Scripts
tags:
  - wordpress
  - bash
  - script
  - auto
  - installation
  - linux
  - centos8
---
# WordPress Auto Installation Script for CentOS 8 (Minimal)
I wrote this script for myself, to automate some annying tasks to create virtual machines for testing my WordPress sites.
I hare in case it can be useful to someone.
Quick instructions:

* Install a **minimal** *Centos 8* installation
* SSH to the server as `root` (e.g. with Putty)
* If you want to run with default parametersm just type or copy-and-paste:
```
curl https://raw.githubusercontent.com/UncleDan/linux-scripts/master/wordpress-centos8.sh|sh ; exit
```
* If you wanto to adjust parameters to your needs use:
```
curl https://raw.githubusercontent.com/UncleDan/linux-scripts/master/wordpress-centos8.sh --output wordpress-centos8.sh
vi wordpress-centos8.sh
sh wordpress-centos8.sh
```
* Thats' pretty much all.

If someone wants to view/review/collaborate, it is in my [linux-scripts](https://github.com/UncleDan/linux-scripts) repo at this address:

[https://github.com/UncleDan/linux-scripts/blob/master/wordpress-centos8.sh](https://github.com/UncleDan/linux-scripts/blob/master/wordpress-centos8.sh)

