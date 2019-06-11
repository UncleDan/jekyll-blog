---
title: 'Nethserver 6.x - Expanding capacity by moving ibay to two new disks in mirror (TESTING)'
date: 2019-06-11T20:00:00+00:00
author: Daniele Lolli (UncleDan)
layout: post
permalink: /2019-06-11-nethserver-6-x-expanding-capacity-by-moving-ibay-to-two-new-disks-in-mirror.html
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
data loss or any other occurrence. It is HIGHLY RECOMMENDED to make backup copy of crucial
configuration files, such as /etc/mdadm.conf and /etc/fstab
```
# Nethserver 6.x - Expanding capacity by moving ibay to two new disks in mirror

Let's assume that you intalled Nethserver on two disks in mirror and later in use you realize you lack of space in them.

The intent of this guide is to add two disks, also in mirror, ang move the *ibay* folder on these disks.

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
parted -s -a optimal /dev/sdc mklabel msdos
parted -s -a optimal /dev/sdc mkpart primary 1 100%
parted -s -a optimal /dev/sdd mklabel msdos
parted -s -a optimal /dev/sdd mkpart primary 1 100%
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
## Create new LVM volume group *VolGroup01*
```
vgcreate VolGroup01 /dev/md3
```
The output should be something like:
```
  Volume group "VolGroup01" successfully created
```
## Create new LVM logical volume *lv_ibay*
```
lvcreate -l 100%FREE -n lv_ibay VolGroup01
```
The output should be something like:
```
  Logical volume "lv_ibay" created.
```
Now we must create the filesysten on the new LVM logical volume *lv_ibay*:
```
mkfs.ext4 /dev/VolGroup01/lv_ibay
```
Sample output:
```
mke2fs 1.41.12 (17-May-2010)
Filesystem label=
OS type: Linux
Block size=4096 (log=2)
Fragment size=4096 (log=2)
Stride=0 blocks, Stripe width=0 blocks
6553600 inodes, 26196992 blocks
1309849 blocks (5.00%) reserved for the super user
First data block=0
Maximum filesystem blocks=4294967296
800 block groups
32768 blocks per group, 32768 fragments per group
8192 inodes per group
Superblock backups stored on blocks:
        32768, 98304, 163840, 229376, 294912, 819200, 884736, 1605632, 2654208,
        4096000, 7962624, 11239424, 20480000, 23887872

Writing inode tables: done
Creating journal (32768 blocks): done
Writing superblocks and filesystem accounting information: done

This filesystem will be automatically checked every 23 mounts or
180 days, whichever comes first.  Use tune2fs -c or -i to override.
```

## Create temporary folder and sync with actual *ibay*
```
mkdir /var/lib/nethserver/ibay.TEMP
chown --reference=/var/lib/nethserver/ibay /var/lib/nethserver/ibay.TEMP
chmod --reference=/var/lib/nethserver/ibay /var/lib/nethserver/ibay.TEMP
mount /dev/VolGroup01/lv_ibay /var/lib/nethserver/ibay.TEMP
rsync -avz /var/lib/nethserver/ibay/ /var/lib/nethserver/ibay.TEMP/
umount /var/lib/nethserver/ibay.TEMP
```

## Switch *ibay* folder and make new mapping persistent
```
mv /var/lib/nethserver/ibay /var/lib/nethserver/ibay.OLD
mv /var/lib/nethserver/ibay.TEMP /var/lib/nethserver/ibay
echo /dev/mapper/VolGroup01-lv_ibay    /var/lib/nethserver/ibay/       ext4    defaults,acl,user_xattr 1 1>> /etc/fstab
mount -a
```
## Reboot the system
```
reboot
```
Enjoy.
### Note
When you are sure that everithing is up and running you could free some space in the original disks by deleting the original *ibay* folder:
```
rm -rf /var/lib/nethserver/ibay.OLD
```
### BEFORE
```
[root@localhost ~]# cat /etc/fstab
#------------------------------------------------------------
# BE CAREFUL WHEN MODIFYING THIS FILE! It is updated automatically
# by the NethServer software. A few entries are updated during
# the template processing of the file and white space is removed,
# but otherwise changes to the file are preserved.
#------------------------------------------------------------
/dev/mapper/VolGroup-lv_root    /       ext4    defaults,acl,user_xattr 1 1
UUID=82416343-93a0-44e5-ba6b-5dc0791b5e62       /boot   ext3    defaults        1 2
/dev/mapper/VolGroup-lv_swap    swap    swap    defaults        0 0
tmpfs   /dev/shm        tmpfs   defaults        0 0
devpts  /dev/pts        devpts  gid=5,mode=620  0 0
sysfs   /sys    sysfs   defaults        0 0
proc    /proc   proc    defaults        0 0
[root@localhost ~]# fdisk -l

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

[root@localhost ~]# cat /proc/mdstat
Personalities : [raid1]
md1 : active raid1 sda1[0] sdb1[1]
      524224 blocks super 1.0 [2/2] [UU]

md2 : active raid1 sdb2[1] sda2[0]
      51870720 blocks super 1.1 [2/2] [UU]
      bitmap: 1/1 pages [4KB], 65536KB chunk

unused devices: <none>
[root@localhost ~]# cat /etc/mdadm.conf
# mdadm.conf written out by anaconda
MAILADDR root
AUTO +imsm +1.x -all
ARRAY /dev/md1 level=raid1 num-devices=2 UUID=44110dab:705d1842:07064f76:702a2c72
ARRAY /dev/md2 level=raid1 num-devices=2 UUID=2f878ec9:7b884fd2:ae073b96:6953a0c5
[root@localhost ~]# pvdisplay
  --- Physical volume ---
  PV Name               /dev/md2
  VG Name               VolGroup
  PV Size               49.47 GiB / not usable 31.00 MiB
  Allocatable           yes (but full)
  PE Size               32.00 MiB
  Total PE              1582
  Free PE               0
  Allocated PE          1582
  PV UUID               xFPeSP-FoYO-e2ye-JKh0-NxlN-4Se9-f6QJvV

[root@localhost ~]# vgdisplay
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
  VG UUID               Boeaty-XVQQ-ftjU-PrK8-p8QL-Nnn6-2IthZ2

[root@localhost ~]# lvdisplay
  --- Logical volume ---
  LV Path                /dev/VolGroup/lv_swap
  LV Name                lv_swap
  VG Name                VolGroup
  LV UUID                8fbo72-lQdo-UsTK-m86t-qJaT-mxmN-B9kmXG
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-06-11 11:03:20 +0200
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
  LV UUID                3vudZ4-HN9L-WFcf-80g1-Y3cC-dB1x-V1AVlD
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-06-11 11:03:21 +0200
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
[root@localhost ~]# cat /etc/fstab
#------------------------------------------------------------
# BE CAREFUL WHEN MODIFYING THIS FILE! It is updated automatically
# by the NethServer software. A few entries are updated during
# the template processing of the file and white space is removed,
# but otherwise changes to the file are preserved.
#------------------------------------------------------------
/dev/mapper/VolGroup-lv_root    /       ext4    defaults,acl,user_xattr 1 1
UUID=82416343-93a0-44e5-ba6b-5dc0791b5e62       /boot   ext3    defaults        1 2
/dev/mapper/VolGroup-lv_swap    swap    swap    defaults        0 0
tmpfs   /dev/shm        tmpfs   defaults        0 0
devpts  /dev/pts        devpts  gid=5,mode=620  0 0
sysfs   /sys    sysfs   defaults        0 0
proc    /proc   proc    defaults        0 0
/dev/mapper/VolGroup01-lv_ibay /var/lib/nethserver/ibay/ ext4 defaults,acl,user_xattr 1
[root@localhost ~]# fdisk -l

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
Disk identifier: 0x0001cbf2

   Device Boot      Start         End      Blocks   Id  System
/dev/sdc1               1       13055   104856576   83  Linux

Disk /dev/sdd: 107.4 GB, 107374182400 bytes
255 heads, 63 sectors/track, 13054 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x0008446a

   Device Boot      Start         End      Blocks   Id  System
/dev/sdd1               1       13055   104856576   83  Linux

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


Disk /dev/md3: 107.3 GB, 107306024960 bytes
2 heads, 4 sectors/track, 26197760 cylinders
Units = cylinders of 8 * 512 = 4096 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000


Disk /dev/mapper/VolGroup01-lv_ibay: 107.3 GB, 107302879232 bytes
255 heads, 63 sectors/track, 13045 cylinders
Units = cylinders of 16065 * 512 = 8225280 bytes
Sector size (logical/physical): 512 bytes / 512 bytes
I/O size (minimum/optimal): 512 bytes / 512 bytes
Disk identifier: 0x00000000

[root@localhost ~]# cat /proc/mdstat
Personalities : [raid1]
md3 : active raid1 sdd1[1] sdc1[0]
      104791040 blocks super 1.2 [2/2] [UU]

md1 : active raid1 sda1[0] sdb1[1]
      524224 blocks super 1.0 [2/2] [UU]

md2 : active raid1 sdb2[1] sda2[0]
      51870720 blocks super 1.1 [2/2] [UU]
      bitmap: 1/1 pages [4KB], 65536KB chunk

unused devices: <none>
[root@localhost ~]# cat /etc/mdadm.conf
MAILADDR root
AUTO +imsm +1.x -all
ARRAY /dev/md2 metadata=1.1 name=localhost.localdomain:2 UUID=2f878ec9:7b884fd2:ae073b96:6953a0c5
ARRAY /dev/md1 metadata=1.0 name=localhost.localdomain:1 UUID=44110dab:705d1842:07064f76:702a2c72
ARRAY /dev/md3 metadata=1.2 name=localhost.localdomain:3 UUID=ecc8ed5f:716cdcde:807fcbc2:5201ec10
[root@localhost ~]# pvdisplay
  --- Physical volume ---
  PV Name               /dev/md3
  VG Name               VolGroup01
  PV Size               99.94 GiB / not usable 3.00 MiB
  Allocatable           yes (but full)
  PE Size               4.00 MiB
  Total PE              25583
  Free PE               0
  Allocated PE          25583
  PV UUID               YRiPlq-x6wu-YLAt-6NGc-RNmW-NdNL-3RxdjX

  --- Physical volume ---
  PV Name               /dev/md2
  VG Name               VolGroup
  PV Size               49.47 GiB / not usable 31.00 MiB
  Allocatable           yes (but full)
  PE Size               32.00 MiB
  Total PE              1582
  Free PE               0
  Allocated PE          1582
  PV UUID               xFPeSP-FoYO-e2ye-JKh0-NxlN-4Se9-f6QJvV

[root@localhost ~]# vgdisplay
  --- Volume group ---
  VG Name               VolGroup01
  System ID
  Format                lvm2
  Metadata Areas        1
  Metadata Sequence No  2
  VG Access             read/write
  VG Status             resizable
  MAX LV                0
  Cur LV                1
  Open LV               1
  Max PV                0
  Cur PV                1
  Act PV                1
  VG Size               99.93 GiB
  PE Size               4.00 MiB
  Total PE              25583
  Alloc PE / Size       25583 / 99.93 GiB
  Free  PE / Size       0 / 0
  VG UUID               K80sMY-YsAh-aPXd-NTnA-yLjW-mp5N-xAvob7

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
  VG UUID               Boeaty-XVQQ-ftjU-PrK8-p8QL-Nnn6-2IthZ2

[root@localhost ~]# lvdisplay
  --- Logical volume ---
  LV Path                /dev/VolGroup01/lv_ibay
  LV Name                lv_ibay
  VG Name                VolGroup01
  LV UUID                MHDSkY-yMQC-hdRr-q6r4-QpX0-qHx9-eOciO3
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-06-11 16:11:26 +0200
  LV Status              available
  # open                 1
  LV Size                99.93 GiB
  Current LE             25583
  Segments               1
  Allocation             inherit
  Read ahead sectors     auto
  - currently set to     256
  Block device           253:2

  --- Logical volume ---
  LV Path                /dev/VolGroup/lv_swap
  LV Name                lv_swap
  VG Name                VolGroup
  LV UUID                8fbo72-lQdo-UsTK-m86t-qJaT-mxmN-B9kmXG
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-06-11 11:03:20 +0200
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
  LV UUID                3vudZ4-HN9L-WFcf-80g1-Y3cC-dB1x-V1AVlD
  LV Write Access        read/write
  LV Creation host, time localhost.localdomain, 2019-06-11 11:03:21 +0200
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
*Source for mirror creation:*

*[https://wiki.nethserver.org/doku.php?id=howto_manually_create_raid1](https://wiki.nethserver.org/doku.php?id=howto_manually_create_raid1)*

*Source for LVM expansion:*

*[https://fdiforms.zendesk.com/hc/en-us/articles/217903228-Expanding-disk-space-via-LVM-partitions](https://fdiforms.zendesk.com/hc/en-us/articles/217903228-Expanding-disk-space-via-LVM-partitions)*

*Hints:*

*[https://www.linuxquestions.org/questions/linux-general-1/using-parted-command-to-create-lvm-partitions-4175533903/](https://www.linuxquestions.org/questions/linux-general-1/using-parted-command-to-create-lvm-partitions-4175533903/)*

***[Download this article in PDF](pdf/2019-06-11-nethserver-6-x-expanding-capacity-by-moving-ibay-to-two-new-disks-in-mirror.pdf) - [Complete console log](logs/2019-06-11-nethserver-6-x-expanding-capacity-by-moving-ibay-to-two-new-disks-in-mirror.console.txt)***
