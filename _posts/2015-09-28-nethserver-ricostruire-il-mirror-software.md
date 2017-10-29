---
id: 2245
title: 'NethServer: ricostruire il mirror software'
date: 2015-09-28T20:00:08+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=2245
permalink: /nethserver-ricostruire-il-mirror-software-09-2015.html
dsq_thread_id:
  - "6164673896"
categories:
  - Linux
  - PC
  - Tech
tags:
  - nethserver
  - raid
  - rebuild
  - sostituzione disco
---
<p style="text-align: justify;">
  Ho di recente iniziato a sperimentare con una distribuzione Linux molto interessante che si chiama <a href="http://www.nethserver.org/" target="_blank">NethServer</a>. E&#8217; una distribuzione italiana basata su <a href="https://www.centos.org/" target="_blank">CentOS</a> 6 che si propone di realizzare alcuni task tipici in una azienda medio/piccola con semplicità e con l&#8217;utilizzo di una interfaccia web molto completa.
</p>

<p style="text-align: justify;">
  I parametri di installazione sono ridotti al minimo (indirizzo IP del server e poco altro) per velocizzare l&#8217;installazione e di standard, in presenza di due dischi, l&#8217;installatore crea un RAID mirror software con un volume LVM già dimensionato adeguatamente.
</p>

<p style="text-align: justify;">
  Il primo dubbio che mi sono posto è cosa fare se un disco del mirror si guasta.
</p>

<p style="text-align: justify;">
  Non sono un sistemista Linux esperto, ma con un paio di prove sono riuscito ad ottenere il risultato desiderato.
</p>

<div class="alert alert-danger">
  ATTENZIONE! Ciascuno di questi comandi è potenzialmente pericoloso per i vostri dati se usato senza criterio. Non limitatevi ad un copia e incolla senza riflettere!
</div>

<p style="text-align: justify;">
  Supponiamo che a guastarsi sia stato il disco <code>sdb</code>.
</p>

<p style="text-align: justify;">
  Se verifichiamo con il comando:
</p>

`cat /proc/mdstat`

<p style="text-align: justify;">
  Le partizioni del mirror avranno una situazione di fail sul secondo disco, identificata dalla sigla <code>[U_]</code>.
</p>

<p style="text-align: justify;">
  Dopo aver sostituito il disco con uno vergine o comunque &#8220;sacrificabile&#8221;, come prima cosa, dobbiamo creare una tabella delle partizioni identica a quella del disco ancora funzionante. Possiamo farlo con il comando:
</p>

`sfdisk -d /dev/sda | sfdisk /dev/sdb --force`

<p style="text-align: justify;">
  L&#8217;opzione <code>--force</code> si rende necessaria perché CentOS è un po&#8217; pignolo e pare che la configurazione automatica di NethServer lasci qualche cilindro non utilizzato.
</p>

<p style="text-align: justify;">
  Ora dobbiamo aggiungere le partizioni di boot e principale al RAID e lo facciamo con i comandi:
</p>

`mdadm --manage /dev/md1 --add /dev/sdb1<br />
mdadm --manage /dev/md2 --add /dev/sdb2`

<p style="text-align: justify;">
  A questo punto il raid è di nuovo attivo ed inizierà la sincronizzazione, che possiamo monitorare con il comando:
</p>

`cat /proc/mdstat`

<p style="text-align: justify;">
  Quando sarà terminata non ci saranno più percentuali di recovery in corso ed entrambe le partizioni saranno &#8220;up&#8221;, cioè con il simbolo <code>[UU]</code>.
</p>

<div class="container_share">
  <a href="http://www.facebook.com/sharer.php?u=http://www.danielelolli.it/nethserver-ricostruire-il-mirror-software-09-2015.html&t=NethServer: ricostruire il mirror software" target="_blank" class="button_purab_share facebook"><span><i class="icon-facebook"></i></span>
  
  <p>
    Facebook
  </p></a> 
  
  <a href="http://twitter.com/share?url=http://www.danielelolli.it/nethserver-ricostruire-il-mirror-software-09-2015.html&text=NethServer: ricostruire il mirror software" target="_blank" class="button_purab_share twitter"><span><i class="icon-twitter"></i></span>
  
  <p>
    Twitter
  </p></a> 
  
  <a href="https://plus.google.com/share?url=http://www.danielelolli.it/nethserver-ricostruire-il-mirror-software-09-2015.html" target="_blank" class="button_purab_share google-plus"><span><i class="icon-google-plus"></i></span>
  
  <p>
    Google +
  </p></a> 
  
  <a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.danielelolli.it/nethserver-ricostruire-il-mirror-software-09-2015.html&title=NethServer: ricostruire il mirror software" target="_blank" class="button_purab_share linkedin"><span><i class="icon-linkedin"></i></span>
  
  <p>
    Linkedin
  </p></a>
</div>