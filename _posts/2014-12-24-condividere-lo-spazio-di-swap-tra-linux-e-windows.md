---
id: 1944
title: Condividere lo spazio di swap tra Linux e Windows
date: 2014-12-24T14:06:19+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=1944
permalink: /condividere-lo-spazio-di-swap-tra-linux-e-windows-12-2014.html
image: /wp-content/uploads/2014/12/swap.jpg
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
  Ho installato una bellissima SSD da 120 GB sul mio portatile. Come al solito ho una sistema un dual boot Windows (ora Seven) e Linux (ora Mint XFCE). Mi sono chiesto: sia Linux che Windows vogliono uno spazio di swap&#8230; Ma è possibile che non si possa usare lo stesso spazio anziché sprecarne di prezioso sulla SSD?<br /> Dopo qualche ricerca infruttuosa mi sono imbattuto in questo splendido trucchetto: <a title="http://ubuntuforums.org/showthread.php?t=245393" href="http://ubuntuforums.org/showthread.php?t=245393" target="_blank">http://ubuntuforums.org/showthread.php?t=245393</a>, che condivido (e traduco) sperando possa servire a qualcuno.<br /> Si tratta, come capirete a breve, di usare la partizione di swap di Linux anche per il pagefile di Windows.<br /> Quindi procedete con la vostra canonica installazione dual boot, avendo cura solamente che l&#8217;area di swap di Linux sia una partizione &#8220;fisica&#8221; e non all&#8217;interno di un volume LVM.<br /> Ora avviate Windows, scaricate da <a title="http://www.acc.umu.se/~bosse/" href="http://www.acc.umu.se/~bosse/" target="_blank">http://www.acc.umu.se/~bosse/</a> il file <strong>SwapFs-3.0.zip</strong> (mirror).<br /> Prima procedere dobbiamo capire dove si trova la partizione di swap secondo Windows.<br /> Anche in questo caso mi sono rifatto ad un commento dell&#8217;articolo originale.<br /> Apriamo un bel prompt dei comandi e digitiamo:
</p>

`C:\>diskpart`

Ora vediamo i dischi presenti:

`DISKPART> list disk</p>
<p>N. disco Stato Dimensioni Disponibile Din GPT<br />
-------- ------------- ------------- ------------- --- ---<br />
Disco 0 Online 298 Gbytes 3602 Mbytes<br />
Disco 1 Online 111 Gbytes 1024 Kbytes`

Nel mio caso la SSD è il secondo disco (disco 1 dato che si parte da 0 a contare), quindi:

`DISKPART> select disk 1</p>
<p>Il disco attualmente selezionato è il disco 1.`

`DISKPART> list partition</p>
<p>Partizione ### Tipo Dim. Offset<br />
--------------- ---------------- ------- -------<br />
Partizione 1 Primario 53 Gb 32 Kb<br />
Partizione 2 Primario 53 Gb 53 Gb<br />
Partizione 3 Primario 4096 Mb 107 Gb`

Eccola lì, 4096MB, i miei 4GB di swap (il portatile ha 2GB di RAM).
  
Quindi mi annoto disco **<span style="color: #ff0000;">1</span>**, partizione **<span style="color: #ff0000;">3</span>**.

Ora apriamo il file swapfs.reg e adeguiamolo alle nostre necessità:

`REGEDIT4</p>
<p>[HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services\SwapFs]</p>
<p>"ErrorControl"=dword:00000001</p>
<p>"Group"="Filter"</p>
<p>#<br />
# When to start the driver:<br />
# At boot: Start=1<br />
# Manually: Start=3<br />
#<br />
"Start"=dword:00000001</p>
<p>"Type"=dword:00000001</p>
<p>#<br />
# (/dev/hda1 in Linux = \\Device\\Harddisk0\\Partition1 in NT, an extended<br />
# partition is skipped in the enumeration)<br />
#</p>
<p>[HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services\SwapFs\Parameters]</p>
<p>"SwapDevice"="\\Device\\Harddisk<span style="color: #ff0000;"><strong>1</strong></span>\\Partition<strong><span style="color: #ff0000;">3</span></strong>"</p>
<p>[HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\Session Manager\DOS Devices]</p>
<p>"S:"="\\Device\\Harddisk<span style="color: #ff0000;"><strong>1</strong></span>\\Partition<strong><span style="color: #ff0000;">3</span></strong>"`
  
Infine, ma non meno importante, copiamo il driver (**swapfs.sys)** in &#8220;%systemroot%\system32\drivers\&#8221;.

Al riavvio noteremo la presenza di una unità S: (che come vedete dalla figura, non corrisponde a nessuna partizione Windows) che sembra fatta apposta per noi&#8230; anzi lo è!

[<img class="alignnone size-full wp-image-1947" src="http://www.danielelolli.it/wp-content/uploads/2014/12/dischi.png" alt="dischi" width="601" height="303" srcset="https://www.danielelolli.it/wp-content/uploads/2014/12/dischi.png 601w, https://www.danielelolli.it/wp-content/uploads/2014/12/dischi-300x151.png 300w" sizes="(max-width: 601px) 100vw, 601px" />](http://www.danielelolli.it/wp-content/uploads/2014/12/dischi.png)

Nel mio caso ho abilitato la gestione automatica del file di swap solo su S: e ho creato un file di dimensione fissa 200MB che ho lasciato su C:.

<p style="text-align: justify;">
  <a href="http://www.danielelolli.it/wp-content/uploads/2014/12/memoria.png"><img class="alignnone size-full wp-image-1946" src="http://www.danielelolli.it/wp-content/uploads/2014/12/memoria.png" alt="memoria" width="354" height="464" srcset="https://www.danielelolli.it/wp-content/uploads/2014/12/memoria.png 354w, https://www.danielelolli.it/wp-content/uploads/2014/12/memoria-229x300.png 229w" sizes="(max-width: 354px) 100vw, 354px" /></a>
</p>

<p style="text-align: justify;">
  Questo perché Windows si premura di farmi sapere che con senza un file di almeno 200MB su C: potrebbe non essere possibile reperire le informazioni di debug in caso di problemi.
</p>

Vero o no, 200MB sono un sacrificio che posso affrontare.

Un altro riavvio e&#8230; abbiamo liberato 4GB!

**ATTENZIONE:** la partizione S: lavora un po&#8217; come un ramdisk ed il suo contenuto va perso allo spegnimento, quindi usiamola solo per il file di paging!!!

<div class="container_share">
  <a href="http://www.facebook.com/sharer.php?u=http://www.danielelolli.it/condividere-lo-spazio-di-swap-tra-linux-e-windows-12-2014.html&t=Condividere lo spazio di swap tra Linux e Windows" target="_blank" class="button_purab_share facebook"><span><i class="icon-facebook"></i></span>
  
  <p>
    Facebook
  </p></a> 
  
  <a href="http://twitter.com/share?url=http://www.danielelolli.it/condividere-lo-spazio-di-swap-tra-linux-e-windows-12-2014.html&text=Condividere lo spazio di swap tra Linux e Windows" target="_blank" class="button_purab_share twitter"><span><i class="icon-twitter"></i></span>
  
  <p>
    Twitter
  </p></a> 
  
  <a href="https://plus.google.com/share?url=http://www.danielelolli.it/condividere-lo-spazio-di-swap-tra-linux-e-windows-12-2014.html" target="_blank" class="button_purab_share google-plus"><span><i class="icon-google-plus"></i></span>
  
  <p>
    Google +
  </p></a> 
  
  <a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.danielelolli.it/condividere-lo-spazio-di-swap-tra-linux-e-windows-12-2014.html&title=Condividere lo spazio di swap tra Linux e Windows" target="_blank" class="button_purab_share linkedin"><span><i class="icon-linkedin"></i></span>
  
  <p>
    Linkedin
  </p></a>
</div>