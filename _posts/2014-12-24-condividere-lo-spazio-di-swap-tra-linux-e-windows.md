---
id: 1944
title: Condividere lo spazio di swap tra Linux e Windows
date: 2014-12-24T14:06:19+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: https://www.uncledan.it/?p=1944
permalink: /2014-12-24-condividere-lo-spazio-di-swap-tra-linux-e-windows.html
mytory_md_visits_count:
  - "202"
image: /wp-content/uploads/2017/10/swap.png
categories:
  - Linux
  - PC
  - Tech
  - Windows
tags:
  - condivisione
  - driver swap
  - linux
  - share swap
  - swap
  - windows
---
<p style="text-align: justify;">
  Ho installato una bellissima SSD da 120 GB sul mio portatile. Come al solito ho una sistema un dual boot Windows (ora Seven) e Linux (ora Mint XFCE). Mi sono chiesto: sia Linux che Windows vogliono uno spazio di swap&#8230; Ma è possibile che non si possa usare lo stesso spazio anziché sprecarne di prezioso sulla SSD?<br /> Dopo qualche ricerca infruttuosa mi sono imbattuto in questo splendido trucchetto: <a title="http://ubuntuforums.org/showthread.php?t=245393" href="http://ubuntuforums.org/showthread.php?t=245393" target="_blank" rel="noopener">http://ubuntuforums.org/showthread.php?t=245393</a>, che condivido (e traduco) sperando possa servire a qualcuno.<br /> Si tratta, come capirete a breve, di usare la partizione di swap di Linux anche per il pagefile di Windows.<br /> Quindi procedete con la vostra canonica installazione dual boot, avendo cura solamente che l&#8217;area di swap di Linux sia una partizione &#8220;fisica&#8221; e non all&#8217;interno di un volume LVM.<br /> Ora avviate Windows, scaricate da <a title="http://www.acc.umu.se/~bosse/" href="http://www.acc.umu.se/~bosse/" target="_blank" rel="noopener">http://www.acc.umu.se/~bosse/</a> il file <strong>SwapFs-3.0.zip</strong> (mirror).<br /> Prima procedere dobbiamo capire dove si trova la partizione di swap secondo Windows.<br /> Anche in questo caso mi sono rifatto ad un commento dell&#8217;articolo originale.<br /> Apriamo un bel prompt dei comandi e digitiamo:
</p>

`C:\>diskpart`

Ora vediamo i dischi presenti:

`DISKPART> list disk`

N. disco Stato Dimensioni Disponibile Din GPT
  
&#8212;&#8212;&#8211; &#8212;&#8212;&#8212;&#8212;- &#8212;&#8212;&#8212;&#8212;- &#8212;&#8212;&#8212;&#8212;- &#8212; &#8212;
  
Disco 0 Online 298 Gbytes 3602 Mbytes
  
Disco 1 Online 111 Gbytes 1024 Kbytes

Nel mio caso la SSD è il secondo disco (disco 1 dato che si parte da 0 a contare), quindi:

`DISKPART> select disk 1`

Il disco attualmente selezionato è il disco 1.

`DISKPART> list partition`

Partizione ### Tipo Dim. Offset
  
&#8212;&#8212;&#8212;&#8212;&#8212; &#8212;&#8212;&#8212;&#8212;&#8212;- &#8212;&#8212;- &#8212;&#8212;-
  
Partizione 1 Primario 53 Gb 32 Kb
  
Partizione 2 Primario 53 Gb 53 Gb
  
Partizione 3 Primario 4096 Mb 107 Gb

Eccola lì, 4096MB, i miei 4GB di swap (il portatile ha 2GB di RAM).
  
Quindi mi annoto disco **<span style="color: #ff0000;">1</span>**, partizione **<span style="color: #ff0000;">3</span>**.

Ora apriamo il file swapfs.reg e adeguiamolo alle nostre necessità:

`REGEDIT4`

[HKEY\_LOCAL\_MACHINE\SYSTEM\CurrentControlSet\Services\SwapFs]

&#8220;ErrorControl&#8221;=dword:00000001

&#8220;Group&#8221;=&#8221;Filter&#8221;

#
  
\# When to start the driver:
  
\# At boot: Start=1
  
\# Manually: Start=3
  
#
  
&#8220;Start&#8221;=dword:00000001

&#8220;Type&#8221;=dword:00000001

#
  
\# (/dev/hda1 in Linux = \\Device\\Harddisk0\\Partition1 in NT, an extended
  
\# partition is skipped in the enumeration)
  
#

[HKEY\_LOCAL\_MACHINE\SYSTEM\CurrentControlSet\Services\SwapFs\Parameters]

&#8220;SwapDevice&#8221;=&#8221;\\Device\\Harddisk<span style="color: #ff0000;"><strong>1</strong></span>\\Partition**<span style="color: #ff0000;">3</span>**&#8221;

[HKEY\_LOCAL\_MACHINE\SYSTEM\CurrentControlSet\Control\Session Manager\DOS Devices]

&#8220;S:&#8221;=&#8221;\\Device\\Harddisk<span style="color: #ff0000;"><strong>1</strong></span>\\Partition**<span style="color: #ff0000;">3</span>**&#8221;
  
Infine, ma non meno importante, copiamo il driver (**swapfs.sys)** in &#8220;%systemroot%\system32\drivers\&#8221;.

Al riavvio noteremo la presenza di una unità S: (che come vedete dalla figura, non corrisponde a nessuna partizione Windows) che sembra fatta apposta per noi&#8230; anzi lo è!

[<img class="alignnone size-full wp-image-1947" src="/wp-content/uploads/2014/12/dischi.png" alt="dischi" width="601" height="303" srcset="/wp-content/uploads/2014/12/dischi.png 601w, /wp-content/uploads/2014/12/dischi-300x151.png 300w" sizes="(max-width: 601px) 100vw, 601px" />](/wp-content/uploads/2014/12/dischi.png)

Nel mio caso ho abilitato la gestione automatica del file di swap solo su S: e ho creato un file di dimensione fissa 200MB che ho lasciato su C:.

<p style="text-align: justify;">
  <a href="/wp-content/uploads/2014/12/memoria.png"><img class="alignnone size-full wp-image-1946" src="/wp-content/uploads/2014/12/memoria.png" alt="memoria" width="354" height="464" srcset="/wp-content/uploads/2014/12/memoria.png 354w, /wp-content/uploads/2014/12/memoria-229x300.png 229w" sizes="(max-width: 354px) 100vw, 354px" /></a>
</p>

<p style="text-align: justify;">
  Questo perché Windows si premura di farmi sapere che con senza un file di almeno 200MB su C: potrebbe non essere possibile reperire le informazioni di debug in caso di problemi.
</p>

Vero o no, 200MB sono un sacrificio che posso affrontare.

Un altro riavvio e&#8230; abbiamo liberato 4GB!

**ATTENZIONE:** la partizione S: lavora un po&#8217; come un ramdisk ed il suo contenuto va perso allo spegnimento, quindi usiamola solo per il file di paging!!!