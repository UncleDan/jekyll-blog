---
title: 'Nethserver 6.x - Expanding capacity by adding two new disks in mirror (TESTING)'
date: 2019-05-05T20:00:00+00:00
author: Daniele Lolli (UncleDan)
layout: post
permalink: /2019-05-05-nethserver-6-x-expanding-capacity-by-adding-two-new-disks-in-mirror.html
categories:
  - Tech
  - Linux
tags:
  - linux
  - nethserver
  - raid
  - lvm
  - capacity
---

```
THIS ARTICLE IS STILL IN BETA STAGE! (although the first tests gave encouraging results)
Use the informations at AT YOUR OWN. I am not responsible of any damage to you system,
data loss or any other occurrence. 
```

# Nethserver 6.x - Expanding capacity by adding two new disks in mirror

Let's assume that you intalled Nethserver on two disks in mirror and later in use you realize you lack of space in them.

The intent of this guide is to add two disks, also in mirror, ang espand the root LVM volume on them.

So the original disks are `sda` and `sdb` (50GB each in this example), while the new disks to add are `sdc` and `sdd` (100GB each in this example).

The system base is an unattended NethServer 6.x installation.

## Disks layout
Let's assume the system is configured ad follow:

4 disks: `sda`, `sdb`, `sdc` and `sdd`:

sda and sdb are the disks containing the OS

`md1` is the RAID 1 on `sda1` and `sdb1` for the boot partition

`md2` is the RAID 1 on `sda2` and `sdb2` for the root partition

You can list all disks using this command:

`fdisk -l`

You can list all configured software raid using this command:

`cat /proc/mdstat`

We are going to create a new md3 raid on sdc1 and sdb1.

## Install required packages
Login to shell using with root, then install parted:

`yum -y install parted`

## Create disks partitions
Create the partition:
```
parted -s -a optimal /dev/sdc mklabel gpt
parted -s -a optimal /dev/sdc mkpart primary 0% 100%
parted -s -a optimal /dev/sdd mklabel gpt
parted -s -a optimal /dev/sdd mkpart primary 0% 100%
```
## Create RAID 1

Create the RAID on sdc1 and sdd1, execute:

`mdadm --create --verbose /dev/md3 --level=1 --raid-devices=2 /dev/sdc1 /dev/sdd1`

The system will output something like this:

```mdadm: Note: this array has metadata at the start and
    may not be suitable as a boot device.  If you plan to
    store '/boot' on this device please ensure that
    your boot-loader understands md/v1.x metadata, or use
    --metadata=0.90
mdadm: size set to 975452160K
mdadm: automatically enabling write-intent bitmap on large array
Continue creating array? y
```
Answer **y** to the question, then the system will proceed to start the new array.

## Configure the system for automount
Save mdadm configuration:

`mdadm --detail --scan > /etc/mdadm.conf`

## Create new LVM physical volume
Execute:

`pvcreate /dev/md3`

## Extend LVM logical volume *lv_root*

First, extend the volume group to use the new physical volume, executing:

`lvextend -l +100%FREE /dev/VolGroup/lv_root`

Then, extend the file system, executing:

`resize2fs /dev/VolGroup/lv_root`

Enjoy.


*(I must add some result messages here and mdmadm.conf "after")*


### BEFORE
```
[root@ns6-expand ~]# cat /etc/fstab
#------------------------------------------------------------
# BE CAREFUL WHEN MODIFYING THIS FILE! It is updated automatically
# by the NethServer software. A few entries are updated during
# the template processing of the file and white space is removed,
# but otherwise changes to the file are preserved.
#------------------------------------------------------------
/dev/mapper/VolGroup-lv_root    /       ext4    defaults,acl,user_xattr 1 1
UUID=f7ef4f29-f846-43b0-aebe-98f2d6b1e8cb       /boot   ext3    defaults       1 2
/dev/mapper/VolGroup-lv_swap    swap    swap    defaults        0 0
tmpfs   /dev/shm        tmpfs   defaults        0 0
devpts  /dev/pts        devpts  gid=5,mode=620  0 0
sysfs   /sys    sysfs   defaults        0 0
proc    /proc   proc    defaults        0 0
[root@ns6-expand ~]# cat /etc/mdadm.conf
# mdadm.conf written out by anaconda
MAILADDR root
AUTO +imsm +1.x -all
ARRAY /dev/md1 level=raid1 num-devices=2 UUID=d7cb4e4d:28442f0a:6c0ecb2b:c47e75c9
ARRAY /dev/md2 level=raid1 num-devices=2 UUID=b5f976c4:a648941e:8db58ec2:da229a47
[root@ns6-expand ~]# pvdisplay
  --- Physical volume ---
  PV Name               /dev/md2
  VG Name               VolGroup
  PV Size               49.47 GiB / not usable 31.00 MiB
  Allocatable           yes (but full)
  PE Size               32.00 MiB
  Total PE              1582
  Free PE               0
  Allocated PE          1582
  PV UUID               eNWohx-4VE2-VPdR-Kc83-B3uH-idjf-cB2Rdm

[root@ns6-expand ~]# lvdisplay
  --- Logical volume ---
  LV Path                /dev/VolGroup/lv_swap
  LV Name                lv_swap
  VG Name                VolGroup
  LV UUID                kjA9Mr-KPNT-BZkF-Btfj-8ICJ-nXhL-dAhhPN
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-05-15 09:45:06 +0200
  LV Status              available
  # open                 1
  LV Size                1.97 GiB
  Current LE             63
  Segments               1
  Allocation             inherit
  Read ahead sectors     auto
  - currently set to     256
  Block device           253:0

  --- Logical volume ---
  LV Path                /dev/VolGroup/lv_root
  LV Name                lv_root
  VG Name                VolGroup
  LV UUID                diNZsr-bi84-qfTw-Mup8-RWOf-dIBe-3HbYva
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-05-15 09:45:07 +0200
  LV Status              available
  # open                 1
  LV Size                47.47 GiB
  Current LE             1519
  Segments               1
  Allocation             inherit
  Read ahead sectors     auto
  - currently set to     256
  Block device           253:1

[root@ns6-expand ~]# vgdisplay
  --- Volume group ---
  VG Name               VolGroup
  System ID
  Format                lvm2
  Metadata Areas        1
  Metadata Sequence No  3
  VG Access             read/write
  VG Status             resizable
  MAX LV                0
  Cur LV                2
  Open LV               2
  Max PV                0
  Cur PV                1
  Act PV                1
  VG Size               49.44 GiB
  PE Size               32.00 MiB
  Total PE              1582
  Alloc PE / Size       1582 / 49.44 GiB
  Free  PE / Size       0 / 0
  VG UUID               2Bz38Z-txko-yCLe-uHQC-Wfn9-gVKs-Z01uRI

[root@ns6-expand ~]#
```
### AFTER
```
[root@ns6-expand ~]# cat /etc/fstab
#------------------------------------------------------------
# BE CAREFUL WHEN MODIFYING THIS FILE! It is updated automatically
# by the NethServer software. A few entries are updated during
# the template processing of the file and white space is removed,
# but otherwise changes to the file are preserved.
#------------------------------------------------------------
/dev/mapper/VolGroup-lv_root    /       ext4    defaults,acl,user_xattr 1 1
UUID=f7ef4f29-f846-43b0-aebe-98f2d6b1e8cb       /boot   ext3    defaults       1 2
/dev/mapper/VolGroup-lv_swap    swap    swap    defaults        0 0
tmpfs   /dev/shm        tmpfs   defaults        0 0
devpts  /dev/pts        devpts  gid=5,mode=620  0 0
sysfs   /sys    sysfs   defaults        0 0
proc    /proc   proc    defaults        0 0
[root@ns6-expand ~]# pvdisplay
  /dev/root: read failed after 0 of 4096 at 158242635776: Input/output error
  /dev/root: read failed after 0 of 4096 at 158242693120: Input/output error
  --- Physical volume ---
  PV Name               /dev/md2
  VG Name               VolGroup
  PV Size               49.47 GiB / not usable 31.00 MiB
  Allocatable           yes (but full)
  PE Size               32.00 MiB
  Total PE              1582
  Free PE               0
  Allocated PE          1582
  PV UUID               eNWohx-4VE2-VPdR-Kc83-B3uH-idjf-cB2Rdm

  --- Physical volume ---
  PV Name               /dev/md3
  VG Name               VolGroup
  PV Size               99.94 GiB / not usable 30.00 MiB
  Allocatable           yes (but full)
  PE Size               32.00 MiB
  Total PE              3197
  Free PE               0
  Allocated PE          3197
  PV UUID               cdtd1p-uSbB-rtVP-FElY-ZNrO-xQFy-x7DT1s

[root@ns6-expand ~]# lvdisplay
  /dev/root: read failed after 0 of 4096 at 158242635776: Input/output error
  /dev/root: read failed after 0 of 4096 at 158242693120: Input/output error
  --- Logical volume ---
  LV Path                /dev/VolGroup/lv_swap
  LV Name                lv_swap
  VG Name                VolGroup
  LV UUID                kjA9Mr-KPNT-BZkF-Btfj-8ICJ-nXhL-dAhhPN
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-05-15 09:45:06 +0200
  LV Status              available
  # open                 1
  LV Size                1.97 GiB
  Current LE             63
  Segments               1
  Allocation             inherit
  Read ahead sectors     auto
  - currently set to     256
  Block device           253:0

  --- Logical volume ---
  LV Path                /dev/VolGroup/lv_root
  LV Name                lv_root
  VG Name                VolGroup
  LV UUID                diNZsr-bi84-qfTw-Mup8-RWOf-dIBe-3HbYva
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-05-15 09:45:07 +0200
  LV Status              available
  # open                 1
  LV Size                147.38 GiB
  Current LE             4716
  Segments               2
  Allocation             inherit
  Read ahead sectors     auto
  - currently set to     256
  Block device           253:2

[root@ns6-expand ~]# vgdisplay
  /dev/root: read failed after 0 of 4096 at 158242635776: Input/output error
  /dev/root: read failed after 0 of 4096 at 158242693120: Input/output error
  --- Volume group ---
  VG Name               VolGroup
  System ID
  Format                lvm2
  Metadata Areas        2
  Metadata Sequence No  5
  VG Access             read/write
  VG Status             resizable
  MAX LV                0
  Cur LV                2
  Open LV               2
  Max PV                0
  Cur PV                2
  Act PV                2
  VG Size               149.34 GiB
  PE Size               32.00 MiB
  Total PE              4779
  Alloc PE / Size       4779 / 149.34 GiB
  Free  PE / Size       0 / 0
  VG UUID               2Bz38Z-txko-yCLe-uHQC-Wfn9-gVKs-Z01uRI

[root@ns6-expand ~]#
```



Source for mirror creation:

[https://wiki.nethserver.org/doku.php?id=howto_manually_create_raid1](https://wiki.nethserver.org/doku.php?id=howto_manually_create_raid1)

Source for LVM expansion:

[https://fdiforms.zendesk.com/hc/en-us/articles/217903228-Expanding-disk-space-via-LVM-partitions](https://fdiforms.zendesk.com/hc/en-us/articles/217903228-Expanding-disk-space-via-LVM-partitions)

Hints:

[https://www.linuxquestions.org/questions/linux-general-1/using-parted-command-to-create-lvm-partitions-4175533903/](https://www.linuxquestions.org/questions/linux-general-1/using-parted-command-to-create-lvm-partitions-4175533903/)