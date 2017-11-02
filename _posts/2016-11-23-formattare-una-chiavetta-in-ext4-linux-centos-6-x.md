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