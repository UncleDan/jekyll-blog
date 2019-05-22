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
Use the informations at AT YOUR OWN RISK. I am not responsible of any damage to you system,
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
```
fdisk -l
```

You can list all configured software raid using this command:
```
cat /proc/mdstat
```
We are going to create a new md3 raid on `sdc1` and `sdd1`.

## Install required packages
Login to shell using with root, then install parted:
```
yum -y install parted
```
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
```
mdadm --create --verbose /dev/md3 --level=1 --raid-devices=2 /dev/sdc1 /dev/sdd1
```
The system will output something like this:
```
mdadm: Note: this array has metadata at the start and
    may not be suitable as a boot device.  If you plan to
    store '/boot' on this device please ensure that
    your boot-loader understands md/v1.x metadata, or use
    --metadata=0.90
mdadm: size set to 104790016K
Continue creating array? y
```
Answer **y** to the question, then the system will proceed to start the new array.
## Configure the system for automount
Save mdadm configuration to make changes persistent:
```
cat << EOF > /etc/mdadm.conf
MAILADDR root
AUTO +imsm +1.x -all
EOF
mdadm --detail --scan >> /etc/mdadm.conf
```
## Create new LVM physical volume
Execute:
```
pvcreate /dev/md3
```
The output should be something like:
```
  Physical volume "/dev/md3" successfully created
```
## Extend LVM logical volume *lv_root*

First, add the new physical volume to the volume group, executing:
```
vgextend /dev/VolGroup /dev/md3
```
The output should be something like:
```
  Volume group "VolGroup" successfully extended
```
Second, extend the volume group to use the new physical volume, executing:
```
lvextend -l +100%FREE /dev/VolGroup/lv_root
```
The output should be something like:
```
  Size of logical volume VolGroup/lv_root changed from 47.47 GiB (1519 extents) to 147.38 GiB (4716 extents).
  Logical volume lv_root successfully resized.
```
Finally, extend the file system (this may take a while), executing:
```
resize2fs /dev/VolGroup/lv_root
```
The output should be something like:
```
The filesystem on /dev/VolGroup/lv_root is now 38633472 blocks long.
```
Enjoy.
### BEFORE
```
[root@ns6-extend ~]# cat /etc/fstab
#------------------------------------------------------------
# BE CAREFUL WHEN MODIFYING THIS FILE! It is updated automatically
# by the NethServer software. A few entries are updated during
# the template processing of the file and white space is removed,
# but otherwise changes to the file are preserved.
#------------------------------------------------------------
/dev/mapper/VolGroup-lv_root    /       ext4    defaults,acl,user_xattr 1 1
UUID=9baac90a-1683-47c6-96b4-61d91974e3ef       /boot   ext3    defaults        1 2
/dev/mapper/VolGroup-lv_swap    swap    swap    defaults        0 0
tmpfs   /dev/shm        tmpfs   defaults        0 0
devpts  /dev/pts        devpts  gid=5,mode=620  0 0
sysfs   /sys    sysfs   defaults        0 0
proc    /proc   proc    defaults        0 0
[root@ns6-extend ~]# fdisk -l

Disk /dev/sda: 53.7 GB, 53687091200 bytes
255 heads, 63 sectors/track, 6527 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x000d06c4

   Device Boot      Start         End      Blocks   Id  System
/dev/sda1   *           1          66      524288   fd  Linux raid autodetect
Partition 1 does not end on cylinder boundary.
/dev/sda2              66        6528    51903488   fd  Linux raid autodetect

Disk /dev/sdb: 53.7 GB, 53687091200 bytes
255 heads, 63 sectors/track, 6527 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x000f1f56

   Device Boot      Start         End      Blocks   Id  System
/dev/sdb1   *           1          66      524288   fd  Linux raid autodetect
Partition 1 does not end on cylinder boundary.
/dev/sdb2              66        6528    51903488   fd  Linux raid autodetect

Disk /dev/sdc: 107.4 GB, 107374182400 bytes
255 heads, 63 sectors/track, 13054 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/sdd: 107.4 GB, 107374182400 bytes
255 heads, 63 sectors/track, 13054 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/md2: 53.1 GB, 53115617280 bytes
2 heads, 4 sectors/track, 12967680 cylinders
Units = cylinders of 8 * 512 = 4096 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/mapper/VolGroup-lv_swap: 2113 MB, 2113929216 bytes
255 heads, 63 sectors/track, 257 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/mapper/VolGroup-lv_root: 51.0 GB, 50969182208 bytes
255 heads, 63 sectors/track, 6196 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/md1: 536 MB, 536805376 bytes
2 heads, 4 sectors/track, 131056 cylinders
Units = cylinders of 8 * 512 = 4096 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000

[root@ns6-extend ~]# cat /proc/mdstat
Personalities : [raid1]
md1 : active raid1 sda1[0] sdb1[1]
      524224 blocks super 1.0 [2/2] [UU]

md2 : active raid1 sdb2[1] sda2[0]
      51870720 blocks super 1.1 [2/2] [UU]
      bitmap: 1/1 pages [4KB], 65536KB chunk

unused devices: <none>
[root@ns6-extend ~]# cat /etc/mdadm.conf
# mdadm.conf written out by anaconda
MAILADDR root
AUTO +imsm +1.x -all
ARRAY /dev/md1 level=raid1 num-devices=2 UUID=bc4842ad:edf14f2a:c0a51a01:69a36f1d
ARRAY /dev/md2 level=raid1 num-devices=2 UUID=f10240ed:53a59773:6a28bb8f:c3910006
[root@ns6-extend ~]# pvdisplay
  --- Physical volume ---
  PV Name               /dev/md2
  VG Name               VolGroup
  PV Size               49.47 GiB / not usable 31.00 MiB
  Allocatable           yes (but full)
  PE Size               32.00 MiB
  Total PE              1582
  Free PE               0
  Allocated PE          1582
  PV UUID               YagK22-RPpp-Vv9t-ZqcH-w8Bf-3cC3-9SzziS

[root@ns6-extend ~]# vgdisplay
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
  VG UUID               F0zUVL-JWzi-vSry-oFUn-1Qq3-E7tA-mNjdyv

[root@ns6-extend ~]# lvdisplay
  --- Logical volume ---
  LV Path                /dev/VolGroup/lv_swap
  LV Name                lv_swap
  VG Name                VolGroup
  LV UUID                T7tDyf-gR6H-lAas-B8f1-7y4x-5zxq-uNJjjL
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-05-15 18:18:17 +0200
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
  LV UUID                bejl2n-2R4l-n3ZG-uznX-4E7l-WUW2-4OLXgn
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-05-15 18:18:18 +0200
  LV Status              available
  # open                 1
  LV Size                47.47 GiB
  Current LE             1519
  Segments               1
  Allocation             inherit
  Read ahead sectors     auto
  - currently set to     256
  Block device           253:1
```
### AFTER
```
[root@ns6-extend ~]# cat /etc/fstab
#------------------------------------------------------------
# BE CAREFUL WHEN MODIFYING THIS FILE! It is updated automatically
# by the NethServer software. A few entries are updated during
# the template processing of the file and white space is removed,
# but otherwise changes to the file are preserved.
#------------------------------------------------------------
/dev/mapper/VolGroup-lv_root    /       ext4    defaults,acl,user_xattr 1 1
UUID=9baac90a-1683-47c6-96b4-61d91974e3ef       /boot   ext3    defaults        1 2
/dev/mapper/VolGroup-lv_swap    swap    swap    defaults        0 0
tmpfs   /dev/shm        tmpfs   defaults        0 0
devpts  /dev/pts        devpts  gid=5,mode=620  0 0
sysfs   /sys    sysfs   defaults        0 0
proc    /proc   proc    defaults        0 0
[root@ns6-extend ~]# fdisk -l

Disk /dev/sda: 53.7 GB, 53687091200 bytes
255 heads, 63 sectors/track, 6527 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x000d06c4

   Device Boot      Start         End      Blocks   Id  System
/dev/sda1   *           1          66      524288   fd  Linux raid autodetect
Partition 1 does not end on cylinder boundary.
/dev/sda2              66        6528    51903488   fd  Linux raid autodetect

Disk /dev/sdb: 53.7 GB, 53687091200 bytes
255 heads, 63 sectors/track, 6527 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x000f1f56

   Device Boot      Start         End      Blocks   Id  System
/dev/sdb1   *           1          66      524288   fd  Linux raid autodetect
Partition 1 does not end on cylinder boundary.
/dev/sdb2              66        6528    51903488   fd  Linux raid autodetect

WARNING: GPT (GUID Partition Table) detected on '/dev/sdc'! The util fdisk doesn't support GPT. Use GNU Parted.


Disk /dev/sdc: 107.4 GB, 107374182400 bytes
255 heads, 63 sectors/track, 13054 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000

   Device Boot      Start         End      Blocks   Id  System
/dev/sdc1               1       13055   104857599+  ee  GPT

WARNING: GPT (GUID Partition Table) detected on '/dev/sdd'! The util fdisk doesn't support GPT. Use GNU Parted.


Disk /dev/sdd: 107.4 GB, 107374182400 bytes
255 heads, 63 sectors/track, 13054 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000

   Device Boot      Start         End      Blocks   Id  System
/dev/sdd1               1       13055   104857599+  ee  GPT

Disk /dev/md2: 53.1 GB, 53115617280 bytes
2 heads, 4 sectors/track, 12967680 cylinders
Units = cylinders of 8 * 512 = 4096 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/mapper/VolGroup-lv_swap: 2113 MB, 2113929216 bytes
255 heads, 63 sectors/track, 257 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/mapper/VolGroup-lv_root: 158.2 GB, 158242701312 bytes
255 heads, 63 sectors/track, 19238 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/md1: 536 MB, 536805376 bytes
2 heads, 4 sectors/track, 131056 cylinders
Units = cylinders of 8 * 512 = 4096 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/md3: 107.3 GB, 107304976384 bytes
2 heads, 4 sectors/track, 26197504 cylinders
Units = cylinders of 8 * 512 = 4096 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000

[root@ns6-extend ~]# cat /proc/mdstat
Personalities : [raid1]
md3 : active raid1 sdd1[1] sdc1[0]
      104790016 blocks super 1.2 [2/2] [UU]
      [========>............]  resync = 40.5% (42496256/104790016) finish=5.2min speed=196334K/sec

md1 : active raid1 sda1[0] sdb1[1]
      524224 blocks super 1.0 [2/2] [UU]

md2 : active raid1 sdb2[1] sda2[0]
      51870720 blocks super 1.1 [2/2] [UU]
      bitmap: 0/1 pages [0KB], 65536KB chunk

unused devices: <none>
[root@ns6-extend ~]# cat /etc/mdadm.conf
MAILADDR root
AUTO +imsm +1.x -all
ARRAY /dev/md2 metadata=1.1 name=localhost.localdomain:2 UUID=f10240ed:53a59773:6a28bb8f:c3910006
ARRAY /dev/md1 metadata=1.0 name=localhost.localdomain:1 UUID=bc4842ad:edf14f2a:c0a51a01:69a36f1d
ARRAY /dev/md3 metadata=1.2 name=ns6-extend.danielelolli.it:3 UUID=0711509f:7bf8a53f:dcacee90:af1d73dd
[root@ns6-extend ~]# pvdisplay
  --- Physical volume ---
  PV Name               /dev/md2
  VG Name               VolGroup
  PV Size               49.47 GiB / not usable 31.00 MiB
  Allocatable           yes (but full)
  PE Size               32.00 MiB
  Total PE              1582
  Free PE               0
  Allocated PE          1582
  PV UUID               YagK22-RPpp-Vv9t-ZqcH-w8Bf-3cC3-9SzziS

  --- Physical volume ---
  PV Name               /dev/md3
  VG Name               VolGroup
  PV Size               99.94 GiB / not usable 30.00 MiB
  Allocatable           yes (but full)
  PE Size               32.00 MiB
  Total PE              3197
  Free PE               0
  Allocated PE          3197
  PV UUID               whvLth-CxyH-2NDn-WEMF-q33B-uYsZ-99rsz1

[root@ns6-extend ~]# vgdisplay
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
  VG UUID               F0zUVL-JWzi-vSry-oFUn-1Qq3-E7tA-mNjdyv

[root@ns6-extend ~]# lvdisplay
  --- Logical volume ---
  LV Path                /dev/VolGroup/lv_swap
  LV Name                lv_swap
  VG Name                VolGroup
  LV UUID                T7tDyf-gR6H-lAas-B8f1-7y4x-5zxq-uNJjjL
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-05-15 18:18:17 +0200
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
  LV UUID                bejl2n-2R4l-n3ZG-uznX-4E7l-WUW2-4OLXgn
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-05-15 18:18:18 +0200
  LV Status              available
  # open                 1
  LV Size                147.38 GiB
  Current LE             4716
  Segments               2
  Allocation             inherit
  Read ahead sectors     auto
  - currently set to     256
  Block device           253:1
```
*Source for mirror creation:*

*[https://wiki.nethserver.org/doku.php?id=howto_manually_create_raid1](https://wiki.nethserver.org/doku.php?id=howto_manually_create_raid1)*

*Source for LVM expansion:*

*[https://fdiforms.zendesk.com/hc/en-us/articles/217903228-Expanding-disk-space-via-LVM-partitions](https://fdiforms.zendesk.com/hc/en-us/articles/217903228-Expanding-disk-space-via-LVM-partitions)*

*Hints:*

*[https://www.linuxquestions.org/questions/linux-general-1/using-parted-command-to-create-lvm-partitions-4175533903/](https://www.linuxquestions.org/questions/linux-general-1/using-parted-command-to-create-lvm-partitions-4175533903/)*

***[Download this article in PDF](pdf/2019-05-05-nethserver-6-x-expanding-capacity-by-adding-two-new-disks-in-mirror.pdf)***
