---
id: 2608
title: 'Problemi WiFi (K)Ubuntu 18.04 notebook HP 15-ay036nl (Energy Star) con scheda di rete Realtek RTL8723BE [RISOLTO]'
date: 2018-09-01T15:54:16+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: old-wordpress-guid=2608
permalink: /2018-09-01-problemi-wifi-kubuntu-18-04-notebook-hp-15-ay036nl-energy-star-con-scheda-di-rete-realtek-rtl8723be-risolto.html
categories:
  - Linux
  - PC
  - Tech
---
Se il tuo portatile è come il <a href="https://support.hp.com/it-it/document/c05228632" target="_blank" rel="noopener">mio</a> o se la scheda di rete risulta essere la stessa digitando il comando `lspci` (`Network controller: Realtek Semiconductor Co., Ltd. RTL8723BE PCIe Wireless Network Adapter`), allora queste due righe di codice ti possono salvare la vita:

    sudo modprobe -r rtl8723be
    sudo modprobe rtl8723be ant_sel=1

oppure:

    sudo modprobe -r rtl8723be
    sudo modprobe rtl8723be ant_sel=2

Dettagli nelle fonti:

  * https://forum.ubuntu-it.org/viewtopic.php?f=9&t=623161#
  * https://askubuntu.com/questions/883673/rtl8723be-wifi-incredibly-weak