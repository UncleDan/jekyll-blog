---
id: 1837
title: 'Installare Windows Seven (7)  Professional 32 bit su Toshiba Qosmio G20'
date: 2014-08-13T15:56:24+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=1837
permalink: /installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014.html
categories:
  - PC
  - Tech
  - Windows
tags:
  - 32bit
  - G20
  - qosmio
  - seven
  - toshiba
  - upgrade
  - windows
  - windows 7 professional
---
<div class="alert alert-danger" style="text-align:center;">
  <strong>ATTENZIONE!</strong><br />Nulla di ciò che leggerete è supportato da Microsoft, Toshiba o chiunque altro.<br />Tutto deriva <strong>*ESCLUSIVAMENTE</strong>* dall&#8217;esperienza del sottoscritto che peraltro<br /><strong>*DECLINA OGNI RESPONSABILITA&#8217;*</strong> in caso di danneggiamenti al vostro PC,<br />guerra termonucleare e qualunque altro cataclisma!
</div>

_Inoltre tutti i marchi registrati citati appartengono ai legittimi proprietari._

Detto questo, cominciamo.

Sarà capitato anche a voi di voler aggiornare il vostro vecchio glorioso portatile.

Nel mio caso si tratta di un gloriosissimo <a title="Toshiba Qosmio G20-129" href="http://www.toshiba.it/discontinued-products/qosmio-g20-129/" target="_blank">Toshiba Qosmio G20</a> che, nonostante gli ormai 12 anni suonati, se la cava ancora egregiamente: chiaramente con qualche accorgimento (2GB RAM e una SSD da 120GB come disco principale) anche il buon vecchio Windows XP Media Center Edition se la cava egregiamente.

Si sa che però ogni tanto una bella &#8220;formattata&#8221; al buon vecchio Windows non fa mai male. Si da il caso però che il vecchio Qosmio abbia un lettore CD difettoso e quindi non si possa usare il lettore CD e che il BIOS non preveda (data l&#8217;età tutto sommato è normale) l&#8217;avvio tramite USB.

A questo punto perché non cogliere l&#8217;occasione ed installare qualcosa di più recente? Da <a href="http://forums.computers.toshiba-europe.com/forums/thread.jspa?threadID=20494" target="Qosmio G20 Vista drivers? Not needed to install Windows Vista">questo articolo</a> pareva che installare Vista non fosse così difficile e tra Vista e Seven tutto sommato le differenze sono poche (o almeno così ho pensato).

## 1 &#8211; Controller SATA (TOSHIBA RAID)

Il primo scoglio è stato il driver del controller SATA dei dischi: è molto particolare speciamente per un portatile (possibilità di striping e mirroring tra i due dischi, anche se il li uso &#8220;normalmente&#8221;) e ovviamente Toshiba non lo rilascia. Con enorme sorpresa mi rendo conto che&#8230; già l&#8217;avevo! Infatti il driver per Windows XP (estratto grazie al preziosissimo <a title="Driver Backup!" href="http://drvback.sourceforge.net/" target="_blank">Driver Backup!</a>) funziona anche per Seven 32 bit!

Avviata l&#8217;installazione sulla partizione dove già risiedeva XP, senza alcun download possibile, l&#8217;installazione è terminata con un paio di riavvii e senza particolari problemi. Ecco il risultato&#8230; niente male

<img src="http://www.danielelolli.it/wp-content/uploads/2014/08/Immagine1.png" alt="Immagine1.png" width="800" height="600" />

Il punto interrogativo sul _TOSHIBA Virtual Device SCSI Processor Drive_ può essere facilmente risolto fornendo la &#8220;seconda parte&#8221; del driver precedentemente estratto.
  
[<img class="alignnone size-full wp-image-1854" src="http://www.danielelolli.it/wp-content/uploads/2014/08/virtual.png" alt="virtual" width="530" height="486" srcset="https://www.danielelolli.it/wp-content/uploads/2014/08/virtual.png 530w, https://www.danielelolli.it/wp-content/uploads/2014/08/virtual-300x275.png 300w" sizes="(max-width: 530px) 100vw, 530px" />](http://www.danielelolli.it/wp-content/uploads/2014/08/virtual.png)

## 2 &#8211; Scheda Video NVIDIA GeForce Go 6600

Anche per la scheda video può (almeno in prima battuta) essere fornito il driver estratto dall&#8217;installazione XP: in realtà l&#8217;accelerazione hardware non mi pare attiva ma almeno ci consentirà di impostare la risoluzione video 1440&#215;900. Poi vedremo se si può fare di meglio. _(EDIT: effettivamente cercando in rete ho trovato un <a title="driverscape.com" href="http://www.driverscape.com/download/nvidia-geforce-go-6600" target="_blank">driver più recente</a> e nettamente più performante)_.
  
[<img class="alignnone size-full wp-image-1852" src="http://www.danielelolli.it/wp-content/uploads/2014/08/video.png" alt="video" width="187" height="37" />](http://www.danielelolli.it/wp-content/uploads/2014/08/video.png)

## 3 &#8211; Scheda di rete wireless Intel PRO/Wireless 2200BG

In questo caso, anche se la Intel si premura di avvisarci che Seven non è supportato, il <a title="2200BG Vista 32bit" href="https://downloadcenter.intel.com/Detail_Desc.aspx?DwnldID=15798&lang=eng&ProdId=1637" target="_blank">driver per Vista 32</a> bit funziona ragionevolmente bene (non riesco ancora ad avere una completa individuazione delle reti, ma procedendo con session ID e password la connessione funziona perfettamente.
  
[<img class="alignnone size-full wp-image-1851" src="http://www.danielelolli.it/wp-content/uploads/2014/08/wireless.png" alt="wireless" width="326" height="71" srcset="https://www.danielelolli.it/wp-content/uploads/2014/08/wireless.png 326w, https://www.danielelolli.it/wp-content/uploads/2014/08/wireless-300x65.png 300w" sizes="(max-width: 326px) 100vw, 326px" />](http://www.danielelolli.it/wp-content/uploads/2014/08/wireless.png)

## 4 &#8211; Toshiba PCI DVB-T/Analog Hybrid Tuner

Il _Controller video multimediale_ altro non è che il sintonizzatore. Dato per impossibile che si potesse trovare un driver per Seven, ho tentato la carta del driver XP estratto con Driver Backup! Anche in questo caso il driver pare funzionare (o almeno consente di togliere la periferica dalla lista di quelle sconosciute, perché in realtà non ho nessun software in grado di sfruttarlo installato per ora.)
  
<span style="color: #ff0000;"><strong><a href="http://www.danielelolli.it/wp-content/uploads/2014/08/tuner.png"><img class="alignnone size-full wp-image-1850" src="http://www.danielelolli.it/wp-content/uploads/2014/08/tuner.png" alt="tuner" width="280" height="54" /></a></strong></span>

&nbsp;

## 5 &#8211; Scheda audio SigmaTel High Definition Audio

Probabilmente anche in questo caso il driver XP estratto dalla precedente installazione era compatibile già di suo, comunque ho preferito riscaricarlo dal <a title="Driver Toshiba Italia" href="http://www.toshiba.it/innovation/download_drivers_bios.jsp?service=IT" target="_blank">sito Toshiba</a> (dove comunque viene indicato funzionante solo fini a XP).

[<img class="alignnone size-full wp-image-1868" src="http://www.danielelolli.it/wp-content/uploads/2014/08/audio.png" alt="audio" width="282" height="55" />](http://www.danielelolli.it/wp-content/uploads/2014/08/audio.png)

##  6 &#8211; Lettore SD Card

In questo caso l&#8217;installazione del solo driver estratto dall&#8217;installazione non è stata sufficiente, ma ho dovuto installare anche l&#8217;utilità di formattazione SD Card (di per sé inutile in quanto formatta al massimo schede da 2GB!!!) per far funzionare il tutto, L&#8217;ho recuperato, per velocità, dal <a title="Vista on Qosmio G20" href="http://forums.computers.toshiba-europe.com/forums/thread.jspa?threadID=20494" target="_blank">topic sull&#8217;installazione di Vista su Qosmio G20</a> a cui ho già fatto cenno.

[<img class="alignnone size-full wp-image-1870" src="http://www.danielelolli.it/wp-content/uploads/2014/08/card.png" alt="card" width="373" height="34" srcset="https://www.danielelolli.it/wp-content/uploads/2014/08/card.png 373w, https://www.danielelolli.it/wp-content/uploads/2014/08/card-300x27.png 300w" sizes="(max-width: 373px) 100vw, 373px" />](http://www.danielelolli.it/wp-content/uploads/2014/08/card.png)

## 7 &#8211; Bluetooth Stack

Ultimo, ma non meno importante viene il driver Bluetooth. Mi ha fatto un po&#8217; impazzire perché non è immediato che un dispositivo con codice ACPI/TOS6205 sia lo stack Bluetooth!! Fortunatamente <a title="DRIVER TOSHIBA ACPI/TOS6205" href="http://www.altainformatica.it/index.php/blog-tecnico/10-driver-toshiba-acpi-tos6205" target="_blank">questo articolo</a> mi ha &#8220;illuminato&#8221;. Una volta imparato di cosa si trattava, reperirlo sul sito Toshiba non è difficile anche perché i driver Bluetooth hanno un <a title="Driver Bluetooth Toshiba - OS Independent" href="http://aps2.toshiba-tro.de/bluetooth/index.php?page=download-toshiba" target="_blank">minisito dedicato</a> e indipendente dal modello cercato.

Ora il mio mitico **Toshiba Qosmio G20-129** è perfettamente funzionante con **Windows Seven (7) Professional 32 bit**!

Spero che questi appunti possano essere utili a qualcuno ed elenco qui di seguito (con possibilità di download) i driver utilizzati.

## Riepilogo driver utilizzati:

  * [1A &#8211; TOSHIBA RAID](http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/1A-TOSHIBA-RAID.zip) driver riciclato
  * [1B &#8211; TOSHIBA RAID Virtual Device](http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/1B-TOSHIBA-RAID-Virtual-Device.zip) driver riciclato
  * [2A &#8211; NVIDIA GeForce Go 6600](http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/2A-NVIDIA-GeForce-Go-6600.zip) driver riciclato
  * [2B &#8211; NVIDIA GeForce Go 6600 (Update)](http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/2B-NVIDIA-GeForce-Go-6600-Update.zip) driver scaricato
  * [3 &#8211; Intel(R) PRO Wireless 2200BG Network Connection](http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/3-IntelR-PRO-Wireless-2200BG-Network-Connection.zip) driver Vista
  * [4 &#8211; TOSHIBA PCI DVB-T Analog Hybrid Tuner](http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/4-TOSHIBA-PCI-DVB-T-Analog-Hybrid-Tuner.zip) driver riciclato
  * [5 &#8211; SigmaTel High Definition Audio CODEC](http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/5-SigmaTel-High-Definition-Audio-CODEC.zip) driver scaricato
  * [6A &#8211; SD Card Reader](http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/6A-SD-Card-Reader.zip) driver riciclato
  * <a href="http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/6B-SD-Card-Reader-Utilities.zip" target="_blank">6B &#8211; SD Card Reader Utilities</a> driver scaricato
  * [7 &#8211; Bluetooth Stack](http://www.danielelolli.it/files/archive/Articles/installare-windows-seven-7-professional-32-bit-su-toshiba-qosmio-g20-08-2014/7-Bluetooth-Stack.zip) driver scaricato

&nbsp;