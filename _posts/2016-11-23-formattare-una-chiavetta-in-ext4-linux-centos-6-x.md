---
id: 2369
title: Formattare una chiavetta in ext4 (Linux CentOS 6.x)
date: 2016-11-23T13:10:45+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=2369
permalink: /formattare-una-chiavetta-in-ext4-linux-centos-6-x-11-2016.html
categories:
  - Linux
  - Tech
tags:
  - chiavetta
  - ext4
  - fdisk
  - formattare
  - linux. centos
  - mkfs
  - pendrive
---
<div class="alert alert-info">
  Questo articolo è una <b><u>bozza</u></b>.
</div>

Identificare la chiavetta con:
  
`fdisk -l`

<div class="alert alert-danger">
  ATTENZIONE: le istruzioni seguenti cancelleranno il contenuto della chiavetta!
</div>

Usare:
  
`fdisk /dev/sdx`

Per:

  * Cancellare eventuali partizioni presenti
  * Creare una nuova partizione
  * Assegnarle il tipo 83

Formattare la partizione con:
  
`mkfs.ext4 -L ETICHETTA /dev/sdx1`

**FINITO.**

<div class="container_share">
  <a href="http://www.facebook.com/sharer.php?u=http://www.danielelolli.it/formattare-una-chiavetta-in-ext4-linux-centos-6-x-11-2016.html&t=Formattare una chiavetta in ext4 (Linux CentOS 6.x)" target="_blank" class="button_purab_share facebook"><span><i class="icon-facebook"></i></span>
  
  <p>
    Facebook
  </p></a> 
  
  <a href="http://twitter.com/share?url=http://www.danielelolli.it/formattare-una-chiavetta-in-ext4-linux-centos-6-x-11-2016.html&text=Formattare una chiavetta in ext4 (Linux CentOS 6.x)" target="_blank" class="button_purab_share twitter"><span><i class="icon-twitter"></i></span>
  
  <p>
    Twitter
  </p></a> 
  
  <a href="https://plus.google.com/share?url=http://www.danielelolli.it/formattare-una-chiavetta-in-ext4-linux-centos-6-x-11-2016.html" target="_blank" class="button_purab_share google-plus"><span><i class="icon-google-plus"></i></span>
  
  <p>
    Google +
  </p></a> 
  
  <a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.danielelolli.it/formattare-una-chiavetta-in-ext4-linux-centos-6-x-11-2016.html&title=Formattare una chiavetta in ext4 (Linux CentOS 6.x)" target="_blank" class="button_purab_share linkedin"><span><i class="icon-linkedin"></i></span>
  
  <p>
    Linkedin
  </p></a>
</div>