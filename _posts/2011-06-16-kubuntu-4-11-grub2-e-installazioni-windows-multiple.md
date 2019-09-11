---
id: 1409
title: 'Kubuntu 4.11: GRUB2 e installazioni Windows multiple'
date: 2011-06-16T22:06:10+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: old-wordpress-guid=1409
permalink: /2011-06-16-kubuntu-4-11-grub2-e-installazioni-windows-multiple.html
mytory_md_visits_count:
  - "374"
image: /uploads/2011/06/screenshot.png
categories:
  - PC
tags:
  - bootloader
  - dual boot
  - grub2
  - kubuntu
  - multimple installation
  - multiple instance
  - windows
---
<p style="text-align: right;">
  <small><em><strong><a title="Kubuntu 4.11: GRUB2 and multiple Windows installations - English Version" href="/2011-06-20-kubuntu-4-11-grub2-and-multiple-windows-installations.html">English Version </a></strong></em></small><em><strong><a title="Kubuntu 4.11: GRUB2 and multiple Windows installations - English Version" href="/2011-06-20-kubuntu-4-11-grub2-and-multiple-windows-installations.html"><img class="alignnone size-full wp-image-149" title="uk-flag-xsmall" src="/uploads/2009/03/uk-flag-xsmall.gif" alt="" width="20" height="15" /></a> </strong></em>
</p>

<p style="text-align: justify;">
  Quando ho <a title="Kubuntu 4.11: prima impressione… sconvolgente!" href="/2011-06-13-kubuntu-4-11-prima-impressione-sconvolgente.html">installato Kubuntu 4.11 sul mio Toshiba Qosmio</a> uno dei pochi problemi riscontrati è stato il boot. Ho impiegato molto tempo (da &#8220;antiguru&#8221; quale sono) per perfezionare il meccanismo per avere due installazioni Windows completamente indipendenti, una per il lavoro ed una per il tempo libero. Non volendo nemmeno che la partizione di avvio di un sistema fosse visibile all&#8217;altro, l&#8217;unica soluzione che potevo adottare era un <em>boot loader</em> che attivasse una partizione e nascondesse l&#8217;altra. Per fare questo ho usato un vecchio <em>boot manager opensource</em> chiamato <a title="Smart Boot Manager" href="http://btmgr.sourceforge.net/" target="_blank">Smart Boot Manager</a>; si tratta di un progetto datato ed esteticamente non entusiasmante, ma estremamente efficace. Infatti è così piccolo che si installa nel solo <em><a title="MBR" href="http://it.wikipedia.org/wiki/Master_boot_record" target="_blank">master boot record</a></em> del disco fisso e fa le operazioni che mi erano necessarie. Quando però ho installato Kubuntu mi sono detto: non è possibile che un <em>boot loader</em> come <a title="GRUB" href="http://www.gnu.org/software/grub/" target="_blank">GRUB</a> non sia in grado di nascondere una partizione! Quindi ho lasciato che <a title="GRUB" href="http://www.gnu.org/software/grub/" target="_blank">GRUB</a> si installasse nell&#8217;MBR del disco, dato che aveva anche correttamente riconosciuto le due installazioni di Windows. Come previsto però, entrambe non si avviavano con un bellissimo &#8220;schermo blu della morte&#8221;. Niente panico e un po&#8217; di ricerca. Trovato <a title="HowTo: Multiple, Independent WinXP Installs on the Same HardDrive via Grub" href="http://www.linuxforums.org/forum/installation/66476-howto-multiple-independent-winxp-installs-same-harddrive-via-grub.html" target="_blank">questo articolo</a> pensavo di aver fatto tombola, ma purtroppo mi sono presto reso conto che la versione 4.11 utilizza <a title="GRUB" href="http://www.gnu.org/software/grub/" target="_blank">GRUB2</a>, la cui sintassi dei comandi è completamente diversa dalle versioni precedenti. Altra google-ata: manuale di GRUB. Questa volta tombola davvero. C&#8217;è addirittura un paragrafetto che sembra fatto apposta per il mio caso specifico. La chiave del gioco è questa piccola sequenza di istruzioni.
</p>

<pre>parttool (hd0,1) hidden-
     parttool (hd0,2) hidden+
     set root=(hd0,1)
     chainloader +1
     parttool <tt>${root}</tt> boot+
     boot</pre>

<p style="text-align: justify;">
  Dopo averla testata da linea di comando e verificato che funziona, provvedo all&#8217;operazione. Ecco i (pochi) dettagli.
</p>

<p style="text-align: center;">
  <span style="color: #ff0000;"><strong>ATTENZIONE: se decidete di fare la stessa operazione effettuate prima un backup dei file originali. Se sbagliate qualcosa il vostro PC potrebbe risultare *NON AVVIABILE* né in Windows né in Linux!</strong></span>
</p>

<p style="text-align: justify;">
  Come al solito anche per questa operazione ho cercato di trovare una strada complicatissima, mentre la soluzione era semplicissima. Non è necessario fare manovre da console o altro, è sufficiente editare un file di testo facendo però (questo sì) a mantenerne inalterata la sintassi.
</p>

1. Cercate il file

<pre>/boot/grub/grub.conf</pre>

e fatene una copia. Io l&#8217;ho chiamata

<pre>grub.conf.ORIGINAL</pre>

2. Lanciate il vostro editor preferito, io ho usato _Kate_. Per evitare sorprese ho aperto una _Konsole_ e usato il comando

<pre>sudo kate</pre>

(per essere sicuro di poter poi poi salvare il file) ed aprite il file:

<pre>/boot/grub/grub.conf</pre>

3. Cercate la prima sezione che avvia Windows, che da me aveva questo aspetto:

<pre>menuentry "Windows XP Media Center Edition (on /dev/sda1)" --class windows --class os {
	insmod part_msdos
	insmod ntfs
	set root='(/dev/sda,msdos1)'
	search --no-floppy --fs-uuid --set=root 6638F37738F34519
	drivemap -s (hd0) ${root}
	chainloader +1
}</pre>

4. Sostituitela con queste istruzioni, chiaramente &#8220;ispirate&#8221; a quelle contenute nel manuale di GRUB2:

<pre>menuentry "Win@Home - Windows XP Media Center Edition (on /dev/sda1)" --class windows --class os {
	parttool (hd0,1) hidden-
	parttool (hd0,2) hidden+
	set root=(hd0,1)
	chainloader +1
	parttool ${root} boot+
}</pre>

Fate attenzione a non confondervi la sezione si apre con una graffa alla fine della riga _menuentry_ e si chiuse con una graffa &#8220;solitaria&#8221;.

5. Ripetete l&#8217;operazione per l&#8217;altra sezione, ricordandovi che questa volta le partizioni _hd0,1_ e _hd0,2_ vanno scambiate: la 1 si nasconde (_hidden+_) e la 2 si scopre (_hidden-_) e si attiva (_set root_).

Salvate il tutto e riavviate.

Il gioco è fatto.

Già che c&#8217;ero ho fatto due piccole modifiche utili nel mio caso: non è detto che servano anche a voi, ma ormai che sono arrivato fino a qui, tanto vale spendere due righe in più, no? 🙂

* * *

Per cambiare la chiamata di default di GRUB (io ho messo Windows Home, in modo che se qualcun altro in famiglia ha bisogno del PC si trovi in un ambiente a lui più&#8230; familiare) ho cambiato la riga

<pre>set default="0"</pre>

in

<pre>set default="4"</pre>

Questo fa sì che venga avviata automaticamente la **quinta scelta** (la numerazione parte da 0, quindi la quinta scelta corrisponde al numero 4!)

* * *

<a name="unhide_windows"></a>Infine ho aggiunto il comando:

<pre>parttool (hd0,1) hidden-
	parttool (hd0,2) hidden-</pre>

all&#8217;avvio normale di Linux in modo che le due partizioni dei sistemi Windows fossero &#8220;scoperte&#8221; e quindi visibile dall&#8217;interno di Linux.

* * *

Adesso penso che sia proprio tutto. Alla prossima soluzione!

* * *

<p style="text-align: center;">
  <a title="grub.cfg.ORIGINAL" href="/uploads/2011/06/grub.cfg_.ORIGINAL.txt" target="_blank">grub.cfg.ORIGINAL</a> | <a title="grub.cfg" href="/uploads/2011/06/grub.cfg_.txt" target="_blank">grub.cfg</a> | <em><a title="Partitions" href="/uploads/2011/06/screenshot.png" target="_blank">Partitions</a></em>
</p>

* * *

<small><em><strong>EDIT 27-10-2011:</strong> purtroppo ho scoperto che il procedimento è da ripetere dopo ogni upgrade che coinvolge il kernel o dist-upgrade (evidentemente grub rigenera automaticamente il file <strong>grub.cfg</strong>). Se qualcuno ha suggerimenti su come rendere permanente la modifica delle sezioni Windows, non ha che da postare un commento! 😉</em></small>