---
title: Installare le VirtualBox Guest Additions su Debian 12 Bookworm da linea di comando (console/bash)
date: 2020-07-27T18:30:00+02:00
author: Daniele Lolli (UncleDan)
layout: post
permalink: /2023-07-27-installare-le-virtualbox-guest-additions-su-debian-12-bookworm-da-linea-di-comando-console-bash
categories:
  - Linux
  - Tech
tags:
  - virtualbox
  - guest additions
  - debian
  - 12
  - bookworm
  - console
  - linea di comando
  - bash
---
# Installare le VirtualBox Guest Additions su Debian 12 Bookworm da linea di comando (console/bash)

## ATTENZIONE! Riflettere prima di fare copia-e-incolla. Declino ogni responsabilità da qualsiasi cosa, ivi compresi i disastri nucleari.

```
apt install -y linux-headers-$(uname -r)
mount /dev/cdrom /media/cdrom0
/media/cdrom0/VBoxLinuxAdditions.run```

**FINITO.**
