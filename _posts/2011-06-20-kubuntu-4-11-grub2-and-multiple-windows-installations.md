---
id: 1420
title: 'Kubuntu 4.11: GRUB2 and multiple Windows installations'
date: 2011-06-20T19:28:59+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=1420
permalink: /kubuntu-4-11-grub2-and-multiple-windows-installations-06-2011.html
categories:
  - PC
tags:
  - bootloader
  - dual boot
  - grub2
  - kubuntu
  - multiple installations
  - multiple instance
  - windows
---
<p style="text-align: right;">
  <small><em><strong><a title="Kubuntu 4.11: GRUB2 e installazioni Windows multiple - Versione Italiana" href="http://www.danielelolli.it/2011/06/kubuntu-4-11-grub2-e-installazioni-windows-multiple/">Versione Italiana</a> </strong></em></small><em><strong><a title="Kubuntu 4.11: GRUB2 e installazioni Windows multiple - Versione Italiana" href="http://www.danielelolli.it/2011/06/kubuntu-4-11-grub2-e-installazioni-windows-multiple/"><img class="alignnone size-full wp-image-149" title="it-flag-xsmall" src="http://www.danielelolli.it/wp-content/uploads/2009/03/it-flag-xsmall.gif" alt="" width="20" height="15" /></a> </strong></em>
</p>

<p style="text-align: justify;">
  When I <a title="Kubuntu 4.11: the first impression ... shocking!" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://www.danielelolli.it/2011/06/kubuntu-4-11-prima-impressione-sconvolgente/&usg=ALkJrhjnQPpuZlZ5L6HyNMq8zA3aWoomKQ">installed Kubuntu 4.11 on my Toshiba Qosmio</a> one of the few problems encountered was the boot. It took me a long time (from &#8220;antiguru&#8221; as they are) to refine the mechanism to have two completely independent of Windows installations, one for work and one for leisure. Not wanting to even the boot partition of a system was seen at the other, the only solution that I could take a <em>boot loader</em> that had a partition active and hide the other. To do this I used an old<em>boot manager opensource</em> called <a title="Smart Boot Manager" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://btmgr.sourceforge.net/&usg=ALkJrhi3oG4c-M8U2VsZxZKJBWzaFtz84A" target="_blank">Smart Boot Manager</a> , it is a draft dated and aesthetically uninspiring, but extremely effective. In fact it is so small that you install only the <a title="MBR" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://it.wikipedia.org/wiki/Master_boot_record&usg=ALkJrhhHeswATob3SdNRBTKGqSDTTo2KUA" target="_blank"><em>master boot record</em></a> of hard disk and do the operations that were necessary to me. But when I installed Kubuntu I said, it is possible that a <em>boot loader</em> such as <a title="GRUB" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://www.gnu.org/software/grub/&usg=ALkJrhh666KZsg6vssSdLrt9V7Xmrinsqw" target="_blank">GRUB</a> is unable to hide a partition! So I let <a title="GRUB" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://www.gnu.org/software/grub/&usg=ALkJrhh666KZsg6vssSdLrt9V7Xmrinsqw" target="_blank">GRUB</a> to the MBR of the disk is, as he also correctly recognized the two installations of Windows. As expected, however, both do not start with a beautiful &#8220;blue screen of death&#8221;. Do not panic and a little &#8216;research. Found <a title="HowTo: Multiple, Independent WinXP Installs on the Same HardDrive via Grub" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://www.linuxforums.org/forum/installation/66476-howto-multiple-independent-winxp-installs-same-harddrive-via-grub.html&usg=ALkJrhh-1EM3h82-uC39Z6dt2XVSP0EZdg" target="_blank">this article</a> I thought I had the jackpot, but unfortunately I soon realized that the version 4.11 uses <a title="GRUB" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://www.gnu.org/software/grub/&usg=ALkJrhh666KZsg6vssSdLrt9V7Xmrinsqw" target="_blank">GRUB2</a> , whose command syntax is completely different from previous versions. Another googling: <a title="GRUB Manual" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://www.gnu.org/software/grub/manual/grub.html&usg=ALkJrhjZRUNdpNUEnQiC94Wzpnx79Loorw" target="_blank">GRUB Manual</a> . This time really bingo. There&#8217;s even a <a title="GRUB Manual - DOS / WINDOWS" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://www.gnu.org/software/grub/manual/grub.html&usg=ALkJrhjZRUNdpNUEnQiC94Wzpnx79Loorw#DOS_002fWindows" target="_blank">little paragrap</a>h that seems made ​​for my specific case. The key to this game is small sequence of instructions.<span style="font-family: Consolas, Monaco, 'Courier New', Courier, monospace; font-size: 12px; line-height: 18px; white-space: pre;"> </span>
</p>

<pre>parttool (hd0,1) hidden-
     parttool (hd0,2) hidden+
     set root=(hd0,1)
     chainloader +1
     parttool <tt>${root}</tt> boot+
     boot</pre>

<p style="text-align: justify;">
  After having tested the command line and verified that it works, provides the transaction. Here are the (few) details.
</p>

<p style="text-align: center;">
  <span style="color: #ff0000;"><strong>WARNING: If you decide to do the same operation, make sure to have a backup of the original files before continuing. </strong><strong>If something goes wrong,  your PC may be *UNBOOTABLE* either from  Windows or from Linux!</strong></span>
</p>

<p style="text-align: justify;">
  As usual for this I tried to find a complicated way, while the solution was simple. There is no need to maneuver or other console, just edit a text file while taking care (yes) to maintain unchanged the syntax.
</p>

1. Look for the file <span style="font-family: Consolas, Monaco, 'Courier New', Courier, monospace; font-size: 12px; line-height: 18px; white-space: pre;"></span>

<pre>/boot/grub/grub.conf</pre>

<span style="font-family: Georgia, 'Times New Roman', 'Bitstream Charter', Times, serif; font-size: 14px; line-height: 19px; white-space: normal;">and make a copy. I called</span>

<pre>grub.conf.ORIGINAL</pre>

2. Launch your favorite editor, I used _Kate._ To avoid surprises, I opened a _Konsole_ and use the command

<pre>sudo kate</pre>

(To make sure you can then save the file) and open the file:

<pre>/boot/grub/grub.conf</pre>

3. Look for the section that first start Windows, which to me looked like this:

<pre>menuentry "Windows XP Media Center Edition (on /dev/sda1)" --class windows --class os {
	insmod part_msdos
	insmod ntfs
	set root='(/dev/sda,msdos1)'
	search --no-floppy --fs-uuid --set=root 6638F37738F34519
	drivemap -s (hd0) ${root}
	chainloader +1
}</pre>

4. Replace it with these instructions, clearly &#8220;inspired&#8221; to those contained in the manual GRUB2:

<pre>menuentry "Win@Home - Windows XP Media Center Edition (on /dev/sda1)" --class windows --class os {
	parttool (hd0,1) hidden-
	parttool (hd0,2) hidden+
	set root=(hd0,1)
	chainloader +1
	parttool ${root} boot+
}</pre>

Be careful not to confuse the section opens with a clip at the end of the line _menuentry_ and closed with a staple &#8220;lonely&#8221;.

5. Repeat for the other section, remembering that this time the partition _hd0, 1_ and _hd0, 2_ are exchanged: the 1 is hidden _(hidden +)_ and 2 turns out _(hidden-)_ and active _(set root)._

Save everything and reboot.

You&#8217;re done.

While I was there I made two small changes useful in my case: it is said to serve to you, but now that I have come this far, might as well say a few more lines, no? ![:-)](http://www.danielelolli.it/wp-includes/images/smilies/icon_smile.gif)

* * *

To change the default called GRUB (I put the Windows Home, so that if someone else in the family needs the PC is in an environment familiar to him than &#8230;) I changed the line

<pre>set default = "0"</pre>

in

<pre>set default = "4"</pre>

This makes the **fifth choice** to start automatically (the numbering starts at 0, then the fifth choice is the number 4!)

* * *

<a name="unhide_windows"></a>Finally I added the command:

<pre>parttool (hd0,1) hidden-
	parttool (hd0,2) hidden-</pre>

to the normal boot Linux so that the two partitions of Windows systems were &#8220;discovered&#8221; and therefore visible from within Linux.

* * *

Now I think it&#8217;s everything. The next solution!

* * *

<p style="text-align: center;">
  <a title="grub.cfg.ORIGINAL" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://www.danielelolli.it/wp-content/uploads/2011/06/grub.cfg_.ORIGINAL.txt&usg=ALkJrhhrOmh7J5lUQsSCt65ssb6AzhUXEA" target="_blank">grub.cfg.ORIGINAL</a> | <a title="grub.cfg" href="http://translate.googleusercontent.com/translate_c?ie=UTF8&rurl=translate.google.com&sl=it&tl=en&twu=1&u=http://www.danielelolli.it/wp-content/uploads/2011/06/grub.cfg_.txt&usg=ALkJrhhcdnXgkqMtmYkB8sxB5Pk5pX6EGA" target="_blank">grub.cfg</a> | <a title="Partitions" href="http://www.danielelolli.it/wp-content/uploads/2011/06/screenshot.png" target="_blank"><em>Partitions</em></a>
</p>

<p style="text-align: center;">
  <small><em>This is a <a title="Google automatic translation" href="http://translate.google.com/translate?u=http%3A%2F%2Fwww.uncledan.it%2F2011%2F06%2Fkubuntu-4-11-grub2-e-installazioni-windows-multiple%2F&sl=it&tl=en&hl=&ie=UTF-8" target="_blank">Google automatic translation</a>. Something more refined may come one day&#8230;</em></small>
</p>