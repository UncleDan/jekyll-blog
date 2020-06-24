---
title: Formattare una chiavetta in ext4 con parted
date: 2020-06-23T13:19:00+00:00
author: Daniele Lolli (UncleDan)
layout: post
permalink: /2020-06-23-formattare-una-chiavetta-in-ext4-con-parted
categories:
  - Linux
  - Tech
tags:
  - chiavetta
  - ext4
  - fdisk
  - formattare
  - linux
  - parted
  - mkfs
  - pendrive
---
## ATTENZIONE: questa procedura cancellerà tutto il contenuto preesistente sulla chiavetta!!!

Questa guida è stata scritta su [NethServer 7.8](https://www.nethserver.org/) (magnifica distribuzione per piccoli server basata su *CentOS*), ma i comandi dovrebbero essere sostanzialmente identici su qualunque distribuzione con i comandi `fdisk` e `parted` installati.

Identificare la chiavetta (solitamente dalla dimensione) con il comando:
  
`fdisk -l`

Solitamente la chiavetta è distinguibile dalla dimensione, nell'esempio sotto la mia chiavetta da 16GB è idenfiticata come `sdg`
```
Disk /dev/sdg: 15.5 GB, 15472047104 bytes, 30218842 sectors
Units = sectors of 1 * 512 = 512 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk label type: dos
Disk identifier: 0x00000000
```
### Da qui non si torna indietro! Il prossimo comando cancellerà il contenuto della chiavetta.
A questo punto creiamo una nuova tabella delle partizioni di tipo MBR (`parted` la chiama `msdos`):

`parted -s -a optimal /dev/sdg mklabel msdos`

E creiamo una nuova partizione primaria:

`parted -s -a optimal /dev/sdg mkpart primary 1 100%`

Infine la formattiamo in `ext4`:

`mkfs.ext4 -L NSBACKUP /dev/sdg1`

**FINITO.**
