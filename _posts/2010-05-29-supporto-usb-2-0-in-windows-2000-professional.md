---
id: 700
title: Supporto USB 2.0 in Windows 2000 Professional
date: 2010-05-29T20:44:20+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: https://www.uncledan.it/2010/05/29/supporto-usb-2-0-in-windows-2000-professional/
permalink: /2010-05-29-supporto-usb-2-0-in-windows-2000-professional.html
mytory_md_visits_count:
  - "105"
categories:
  - PC
---
<p style="text-align: justify;">
  E&#8217; capitato ad un amico di avere un PC perfettamente funzionante con il glorioso <strong>Windows 2000 Professional</strong> di comprare un &#8220;innocuo&#8221; hub USB e di scoprire che la periferica&#8230; non si installa! Facendo una rapida indagine ho scoperto che il motivo (peraltro prevedibile) è che Windows 2000 non aveva disponibile all&#8217;origine il supporto per l&#8217;USB 2.0, come si capisce da <a href="http://support.microsoft.com/kb/319973/it" target="_blank">questo articolo</a> del Supporto Microsoft. Dato che il catalogo di Windows Update nel frattempo è divenuto una sorta di labirinto del minotauro, ho cercato il driver indicato e lo metto in mirror sul mio sito.
</p>

<p style="text-align: justify;">
  La procedura appare abbastanza semplice:
</p>

<ul style="text-align: justify;">
  <li>
    Assicurarsi di avere installato il Service Pack 4 di Windows 2000 <em>(Frank, tranquillo da te è già installato! 😉 )</em>
  </li>
  <li>
    Installare il driver sottoindicato:<a href="https://www.uncledan.it/files/archive/Articles/ramdisk-04-2010/Gavotte_RAMDisk_1.0.4096.5_200811130.zip">Microsoft Corporation &#8211; Other Hardware &#8211; NEC PCI to USB Enhanced Host Controller B0</a><br /> <em>(non trovando alcuna spiegazione sull&#8217;installazione deduco che vada scompattato per poi specificare la cartella decompressa quando Windows chiederà dove si trova il file &#8220;usbehci.sys&#8221;)</em>
  </li>
</ul>

<p style="text-align: justify;">
  I file di questo aggiornamento comprendono:
</p>

<div>
  <div>
    <pre>    Data         Ora     Versione        Dimensione   Nome file
    --------------------------------------------------------------
    23/04/2002   22.46   5.0.2195.5652      135.920   Usbport.sys
    18/04/2002   23.46   5.0.2195.5605       49.392   Usbhub20.sys
    23/04/2002   22.46   5.0.2195.5652       19.216   Usbehci.sys
    07/05/2002   16.52                        7.159   Usb2.inf
    09/05/2002   23.12                        8.736   Usb2.cat
    18/04/2002   23.46   5.0.2195.5605        6.416   Hccoin.dll</pre>
  </div>
</div>

<p style="text-align: justify;">
  &#8220;Dopo avere installato il driver e riavviato il computer, il controller USB 2.0 e la periferica sono elencati correttamente in Gestione periferiche.&#8221;  (cit.) <em>(o almeno così sostiene ZioBill)</em>
</p>

<p style="text-align: justify;">
  Due promemoria:
</p>

<ol style="text-align: justify;">
  <li>
    scorciatoia per Gestione periferiche: clic destro su Risorse del computer, Proprietà, linguetta Hardware, bottone Gestione periferiche
  </li>
  <li>
    anche se non specificato per la soluzione di questo problema, consiglio a tutti gli utilizzatori dell&#8217;intramontabile Windows 2000 di installare la Raccolta completa di aggiornamenti 1 per Windows 2000 SP4.
  </li>
</ol>

<p style="text-align: justify;">
  Spero di essere stato utile e fatemi sapere se Zio Bill diceva il vero! 😀
</p>