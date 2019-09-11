---
id: 1829
title: 'HOWTO: Boot Linux from network using PXE and DNSMASQ proxy (Ubuntu 14.04)'
date: 2014-07-25T17:30:49+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: old-wordpress-guid=1829
permalink: /2014-07-25-howto-boot-linux-from-network-using-pxe-and-dnsmasq-proxy-ubuntu-14-04.html
dsq_thread_id:
  - "6163722910"
mytory_md_visits_count:
  - "2326"
categories:
  - Linux
  - PC
  - Tech
tags:
  - dnsmasq
  - ipxe
  - network install
  - proxydhcp
  - pxe server
  - ubuntu
---
**This an adaptation from this guide to fit to Ubuntu 14.04 LTS environment:**
  
 **<a title="http://danielboca.blogspot.it/2012/02/boot-linux-from-network-using-pxe-and.html?m=1" href="http://danielboca.blogspot.it/2012/02/boot-linux-from-network-using-pxe-and.html?m=1" target="_blank">http://danielboca.blogspot.it/2012/02/boot-linux-from-network-using-pxe-and.html?m=1</a>**

_The parts in italic are added or modified by me._

_I will assume you have an Ubuntu running installation and you know its IP addess._
  
 _My IP address is 192.168.0.1: modify the menus to suit yours._
  
 _The ISO images I used are ubuntu-12.04.4-desktop-i386.iso for 12.04 and ubuntu-14.04-server-i386.iso for 14.04 (alternate DVD does not exist anymore)._
  
 _Both are 32 bit, because my test box doesn’t allow 64 bit virtualization, but the process is identical: search and replace “i386” with “amd64”._

<span style="color: #ff0000;"><strong>ATTENTION! With this article I could get a working PXE server and correctly install Ubuntu 12.04.</strong></span>
  
<span style="color: #ff0000;"><strong> Ubuntu 14.04 installation boots kernel and initrd, but gets stuck after that!!! Any help will be greatly appreciated.</strong></span>

This tutorial allows to boot and install Linux from network using PXE and DNSMASQ.

DNSMASQ is a very light implementation of TFTPD,DHCPD and NAMED.

In most of the cases there is already a DHPCD server on the network and starting a new DHCPD server would not work.
  
Therefore we are going to configure DNSMASQ as a PROXY DHCPD for the existing server and specify what PXE service to use.

The example provides a menu that can install Ubuntu 12.04, Ubuntu 14.04 and also can start the Memtest utility from the network.

## Configure SYSLINUX

_Become superuser_
  
 _`sudo -i`_

Install syslinux
  
`<em>aptitude install</em> syslinux`

Create a folder for TFTP server with the following (similar) structure
  
`mkdir -p /tftpboot/pxelinux.cfg<br />
mkdir -p /tftpboot/images/<em>ubuntu/12.04/i386</em><br />
mkdir -p /tftpboot/images/<em>ubuntu/14.04/i386</em>`

Copy the necessary files from syslinux
  
`cp <em>/usr/lib/syslinux/vesamenu.c32</em> /tftpboot<br />
cp <em>/usr/lib/syslinux/pxelinux.0</em> /tftpboot`

_Now, let’s assume that the original ISOs have been copied/mounted in /iso/ubuntu12.04 and /iso/ubuntu14.04._

Copy initrd and linux kernel from the original ISOs
  
`cp <em>/iso/ubuntu12.04/install/netboot/ubuntu-installer/i386/initrd.gz /tftpboot/images/ubuntu/12.04/i386</em><br />
cp <em>/iso/ubuntu12.04/install/netboot/ubuntu-installer/i386/linux /tftpboot/images/ubuntu/12.04/i386</em>`

cp _/iso/ubuntu14.04/install/netboot/ubuntu-installer/i386/initrd.gz /tftpboot/images/ubuntu/14.04/i386_
  
cp _/iso/ubuntu14.04/install/netboot/ubuntu-installer/i386/linux /tftpboot/images/ubuntu/14.04/i386_

Download and copy memtest from <a title="http://memtest.org/" href="http://memtest.org/" target="_blank">memtest.org</a>
  
_`wget http://www.memtest.org/download/5.01/memtest86+-5.01.bin.gz<br />
gzip -d ./memtest86+-5.01.bin.gz<br />
cp ./memtest86+-5.01.bin /tftpboot/memtest`_

Create and edit /tftpboot/pxelinux.cfg/default file
  
The IP 192.168.0.1 can be changed to reflect the path to your installation files
  
`default vesamenu.c32<br />
prompt 0</p>
<p>menu title PXE Boot Menu</p>
<p>label <em>ubuntu-12.04-i386</em><br />
menu label <em>ubuntu-12.04-i386</em><br />
kernel images/<em>ubuntu/12.04/i386/linux</em><br />
append initrd=images/<em>ubuntu/12.04/i386/initrd.gz</em> method=nfs:192.168.0.1:/<em>iso/ubuntu12.04</em> lang=us keymap=us ip=dhcp noipv6</p>
<p>label <em>ubuntu-14.04-i386</em><br />
menu label <em>ubuntu-14.04-i386</em><br />
kernel images/<em>ubuntu/14.04/i386/linux</em><br />
append initrd=images/<em>ubuntu/14.04/i386/initrd.gz</em> method=nfs:192.168.0.1:/<em>iso/ubuntu14.04</em> lang=us keymap=us ip=dhcp noipv6</p>
<p>label memtest86<br />
menul label memtest86<br />
kernel memtest<br />
append -`

If you like a _simpler text mode menu_, change _vesamenu.c32 with menu.c32_ (after you have copied it from /usr/share/syslinux).

## Configure DNSMASQ

Install dnsmasq
  
`aptitude install dnsmasq`

Create and edit /etc/dnsmasq.d/pxe.conf with the following content
  
The IP 192.168.0.1 should be the IP of the machine that runs DNSMASQ

`tftp-root=/tftpboot<br />
enable-tftp<br />
dhcp-boot=pxelinux.0<br />
dhcp-option=vendor:PXEClient,6,2b<br />
dhcp-no-override<br />
pxe-prompt="Press F8 for boot menu", 3<br />
pxe-service=X86PC, "Boot from network", pxelinux<br />
pxe-service=X86PC, "Boot from local hard disk", 0<br />
dhcp-range=192.168.0.1,proxy`

Edit /etc/dnsmasq.conf and check you have a line at the of the file similar to this:
  
`conf-dir=/etc/dnsmasq.d`

(Re)start dnsmask
  
`sudo service dnsmasq restart`

Boot a machine using the network option from BIOS and install your system or run memtest!