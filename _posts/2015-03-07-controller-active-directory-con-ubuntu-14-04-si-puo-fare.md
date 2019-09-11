---
id: 1953
title: 'Controller Active Directory con Ubuntu 14.04? Si&#8230; Può&#8230; Fare!!!'
date: 2015-03-07T16:14:40+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: old-wordpress-guid=1953
permalink: /2015-03-07-controller-active-directory-con-ubuntu-14-04-si-puo-fare.html
mytory_md_visits_count:
  - "318"
categories:
  - Linux
  - PC
  - Tech
  - Windows
tags:
  - active direvtory
  - linux
  - pdc
  - primary domain controller
  - ubuntu
  - ubuntu 14.04
---
<p style="text-align: justify;">
  Ho cercato a lungo di mettere in piedi un primary domain controller con Linux e Samba. Senza risultato. Al punto che avevo del tutto rinunciato a favore di una soluzione &#8220;chiavi in mano&#8221;: <a title="Turnkey Domain Controller" href="http://www.turnkeylinux.org/domain-controller" target="_blank">Turnkey Domain Controller</a>. Poi, un po&#8217; la scarsità degli aggiornamenti, un po&#8217; la mia proverbiale cocciutaggine (dovevo farlo io, non trovarlo già fatto!)
</p>

<p style="text-align: justify;">
  Ed è a questo punto della storia che si innesta questo articolo&#8230; Un primary domain controller con Linux e Samba si può fare, e in pochi minuti!!!
</p>

<p style="text-align: justify;">
  Anzi con Ubuntu 14.04 si può creare non solo un primary domain controller, ma addirittura un server Active directory che puoi gestire direttamente con i tool di Microsoft.
</p>

<p style="text-align: justify;">
  Vale decisamente una lettura e chissà, magari una traduzione prima o poi.
</p>

# <a title="Setting up an Active Directory Domain Controller using Samba 4 on Ubuntu 14.04" href="https://jimshaver.net/2014/07/13/setting-up-an-active-directory-domain-controller-using-samba-4-on-ubuntu-14-04/" target="_blank">Setting up an Active Directory Domain Controller using Samba 4 on Ubuntu 14.04</a> {.entry-title}