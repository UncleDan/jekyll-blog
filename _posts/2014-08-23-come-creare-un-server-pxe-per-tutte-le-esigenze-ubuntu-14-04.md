---
id: 1861
title: Come creare un server PXE per tutte le esigenze (Ubuntu 14.04.5)
date: 2014-08-23T09:52:59+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: https://www.uncledan.it/?p=1861
permalink: /2014-08-23-come-creare-un-server-pxe-per-tutte-le-esigenze-ubuntu-14-04.html
dsq_thread_id:
  - "6158418475"
mytory_md_visits_count:
  - "1236"
categories:
  - Linux
  - PC
  - Tech
tags:
  - come creare
  - howto
  - ipxe
  - multiboot
  - net boot local lan
  - netboot rete locale
  - pxe fai da te
  - pxe server
  - ubuntu 14.04
---
<div class="alert alert-warning">
  Aggiornato a nuove release <strong>Ubuntu 14.04.5</strong> e <strong>Webmin 1.850</strong> <em>lunedì 01/07/2017</em>.
</div>

<div style="text-align: justify;">
  Se vi è mai capitato di avere a che fare con un portatile o qualunque altro dispositivo col lettore CD rotto e che non avvia da USB (!), allora avrete capito l’utilità di un server PXE. Per la definizione esatta vi rimando a Wikipedia o chi per lei: quello che tenterò di aiutarvi a capire è come preparare un server PXE che ci consenta di avviare gli strumenti di diagnostica di base e le installazioni di Linux e/o Windows. Ho scelto una installazione con ProxyDHCP perché molto spesso il DHCP delle nostre reti è all’interno del router e non sempre è possibile inibirlo/configurarlo, specialmente se si tratta del modem in comodato d’uso di qualche ISP italiano (mettete un nome a caso, sono tutti della stessa risma!)<br /> Questo non è un lavoro originale: molte idee sono prese da post letti qua e là googleando. Cercherò di citare tutte le fonti, ma se dimenticassi qualcuno e lo notaste non esitate a segnalarlo. In particolare mi preme ricordare questo articolo: <a href="http://ubuntuforums.org/showthread.php?t=1606910" target="_blank" rel="noopener">http://ubuntuforums.org/showthread.php?t=1606910</a>. Anche se alla fine ho preso ben poco da esso, è stato fondamentale per avere una visione di insieme di cosa serviva a fare cosa.
</div>

## Installazione di Ubuntu Server

<div style="text-align: justify;">
  Per questa mia impresa ho deciso di partire da una distribuzione <strong>Ubuntu Server 14.04.5 LTS</strong> (amd64) “minimale” (cioè deselezionando tutti i componenti in fase di installazione, tranne OpenSSH server che è indispensabile per gestire la macchina da remoto) per poi aggiungere solo quello che mi serve. Penso che la cosa non differisca molto su qualunque altra distro Debian based, ma intanto siete avvertiti. L’utente principale è stato chiamato <code>pxe</code>. Per velocità, tutti i comandi sono eseguiti dopo essere diventati superuser (in una Debian &#8220;pura&#8221; basterebbe fare il login come root) con il comando <code>pxe@pxe:~$ sudo -i</code>. Terminata l’installazione mi sono assicurato che il nostro server abbia un IP fisso nella sottorete, nel mio caso <code>192.168.0.246</code>. Per farlo ho editato il file <code>/etc/network/interfaces</code> in questo modo:
</div>

`<strong># /etc/network/inferfaces</strong><br />
# This file describes the network interfaces available on your system<br />
# and how to activate them. For more information, see interfaces(5).<br />
# The loopback network<br />
interface auto lo<br />
iface lo inet loopback<br />
# The primary network<br />
interface auto eth0<br />
iface eth0 inet static<br />
address 192.168.0.246<br />
netmask 255.255.255.0<br />
gateway 192.168.0.254<br />
dns-nameservers 8.8.8.8`

Disattiviamo e riattiviamo l&#8217;interfaccia di rete per rendere operative le modifiche (se siamo collegati in _SSH_ è consigliabile un `reboot` per evitare di rimanere tagliati fuori!):
  
`ifdown eth0<br />
ifup eth0`
  
e aggiorniamo tutto:
  
`apt-get clean<br />
apt-get update<br />
apt-get upgrade`
  
Infine riavviamo e passiamo oltre:
  
`reboot`

## Installazione di webmin per l&#8217;amministrazione remota (facoltativo)

<div>
  Un pacchetto che trovo molto utile per la gestione remota dei server è Webmin: non è presente nei repository ma si installa con pochi comandi.
</div>

`root@pxe:~# wget http://prdownloads.sourceforge.net/webadmin/webmin_1.850_all.deb<br />
root@pxe:~# dpkg -i webmin_1.850_all.deb<br />
root@pxe:~# apt-get -f install<br />
` `root@pxe:~# rm webmin_1.850_all.deb`

<div>
  L’interfaccia sarà ora disponibile all’indirizzo <code>https://192.168.0.246:10000</code>.
</div>

## Creazione struttura delle cartelle

_Liberamente tratto da: <a href="http://blogging.dragon.org.uk/howto-setup-a-pxe-server-with-dnsmasq/" target="_blank" rel="noopener">http://blogging.dragon.org.uk/howto-setup-a-pxe-with-dnsmasq/</a>_

<div>
  Ora creiamo le directory che ospiteranno i nostri file; per mantenere un po’ di organizzazione le metteremo tutte in <code>/pxe</code>:
</div>

`root@pxe:~# mkdir /pxe`

<div>
  Creiamo anche una sottocartella che conterrà le immagini dei sistemi operativi <code>/iso</code>:
</div>

`root@pxe:~# mkdir /pxe/iso`

# PXE Boot: configurare syslinux e dnsmasq

<p style="text-align: left;">
  <em>Originale: <a href="http://danielboca.blogspot.it/2012/02/boot-linux-from-network-using-pxe-and.html?m=1" target="_blank" rel="noopener">http://danielboca.blogspot.it/2012/02/boot-linux-from-network-using-pxe-and.html?m=1</a></em><br /> <em> Adattamento a Ubuntu di UncleDan: <a href="/2014-07-25-howto-boot-linux-from-network-using-pxe-and-dnsmasq-proxy-ubuntu-14-04.html" target="_blank" rel="noopener">/2014-07-25-howto-boot-linux-from-network-using-pxe-and-dnsmasq-proxy-ubuntu-14-04.html</a></em>
</p>

<div>
  Installiamo syslinux e copiamo i file necessari in quella che sarà la cartella di boot del server TFTP:
</div>

`root@pxe:~# apt-get install syslinux<br />
root@pxe:~# cp /usr/lib/syslinux/pxelinux.0 /pxe<br />
root@pxe:~# cp /usr/lib/syslinux/menu.c32 /pxe<br />
root@pxe:~# mkdir /pxe/pxelinux.cfg`

<div style="text-align: justify;">
  Ora predisponiamo il nostro menu di base, con una sola operazione (avvia dal disco locale): ci occuperemo dei sottomenu mano a mano che procederemo nelle sezioni <em>(idea del menu da: <a href="https://help.ubuntu.com/community/PXEInstallMultiDistro" target="_blank" rel="noopener">https://help.ubuntu.com/community/PXEInstallMultiDistro</a></em>)
</div>

`<strong>/pxe/pxelinux.cfg/default</strong><br />
DEFAULT menu.c32<br />
TIMEOUT 600<br />
ONTIMEOUT BootLocal<br />
MENU TITLE PXE MULTIBOOT SERVER<br />
NOESCAPE 1<br />
ALLOWOPTIONS 1<br />
PROMPT 0<br />
LABEL BootToLocalHardDisk<br />
localboot 0<br />
TEXT HELP<br />
Boot to local hard disk<br />
ENDTEXT<br />
MENU END`

<div>
  Installiamo <code>dnsmasq</code>:
</div>

`root@pxe:~# apt-get install dnsmasq`

<div>
  Creiamo il file di configurazione per il servizio PXE <code>/etc/dnsmasq.d/pxe.conf</code> (l’IP 192.168.0.246 è quello che abbiamo assegnato al nostro server):
</div>

`<strong>/etc/dnsmasq.d/pxe.conf</strong><br />
tftp-root=/pxe<br />
enable-tftp<br />
dhcp-boot=pxelinux.0<br />
dhcp-option=vendor:PXEClient,6,2b<br />
dhcp-no-override<br />
pxe-prompt="Press F8 for boot menu", 3<br />
pxe-service=X86PC, "Boot from network", pxelinux<br />
pxe-service=X86PC, "Boot from local hard disk", 0<br />
dhcp-range=192.168.0.246,proxy`

<div>
  Ed assicuriamoci che nel file di configurazione <code>/etc/dnsmasq.conf</code> sia attiva la linea (altrimenti inserirla in coda al file):
</div>

`<strong>/etc/dnsmasq.conf</strong><br />
[…]<br />
conf-dir=/etc/dnsmasq.d<br />
[…]`

<div>
  Riavviamo il servizio <code>dnsmasq</code>:
</div>

`root@pxe:~# sudo service dnsmasq restart`

# Servizio HTTP: configurare lighttpd

_Liberamente tratto da: <a href="http://blogging.dragon.org.uk/howto-setup-a-pxe-server-with-dnsmasq/" target="_blank" rel="noopener">http://blogging.dragon.org.uk/howto-setup-a-pxe-with-dnsmasq/</a>_

<div>
  Ho deciso di utilizzare <code>lighttpd</code> come server web; è un po’ più moderno e performante del tradizionale apache2 che quasi tutti i tutoriali usano. Ma purché ci passi i file in http qualsiasi server andrà bene! Ricordiamoci solo che nel resto della guida supporrò che i file da passare al boot dovranno essere accessibili all’indirizzo:
</div>

`http://192.168.0.246/`

Installiamo il server:
  
`root@pxe:~# apt-get install lighttpd`

<div>
  E apportiamo una piccola modifica al file di configurazione (in grassetto corsivo):
</div>

`<strong>/etc/lighttpd/lighttpd.conf</strong><br />
[…]<br />
<em><strong>dir-listing.activate = "enable"</strong></em>`

<div>
  Riavviamo il server web:
</div>

`root@pxe:~# service lighttpd restart`

<div style="text-align: justify;">
  La modifica consente il <em>directory listing</em> se non è presente un file <em>index.hml</em> e mi consentirà di usare qualsiasi browser web per monitorare il contenuto delle varie cartelle.
</div>

<div>
  Ora elimino il file index e creo un link alla cartella <code>/pxe</code> che mi consentirà di &#8220;vedere&#8221; i file anche all&#8217;indirizzo <code>http://192.168.0.246/pxe/</code>:<br /> <code>root@pxe:~# rm /var/www/index.lighttpd.html&lt;br />
root@pxe:~# ln -s /pxe /var/www/pxe</code></p> 
  
  <div>
  </div>
  
  <div style="text-align: justify;">
    Infine creo per comodità un file che mi rimandi a webmin direttamente dalla cartella principale
  </div>
  
  <div style="text-align: justify;">
    <code>root@pxe:~# mkdir /var/www/webmin</code>
  </div>
  
  <p>
    <code>&lt;strong>/var/www/webmin/index.html&lt;/strong>&lt;br />
&lt;html xmlns="http://www.w3.org/1999/xhtml"&gt;&lt;br />
&lt;head&gt;&lt;br />
&lt;title&gt;Login to Webmin&lt;/title&gt;&lt;br />
&lt;meta http-equiv="refresh" content="0;URL='https://192.168.0.246:10000'" /&gt;&lt;br />
&lt;/head&gt;&lt;br />
&lt;body&gt;&lt;br />
&lt;p&gt;You can find the Login to Webmin&lt;br&gt;&lt;br />
at &lt;a href="https://192.168.0.246:10000"&gt;https://192.168.0.246:10000&lt;/a&gt;.&lt;/p&gt;&lt;br />
&lt;/body&gt;&lt;br />
&lt;/html&gt;</code>
  </p>
  
  <h1>
    Servizio NFS: configurare nfs-kernel-server
  </h1>
  
  <p>
    <em>Fonte: <a href="http://ubuntuforums.org/showthread.php?t=1606910" target="_blank" rel="noopener">http://ubuntuforums.org/showthread.php?t=1606910</a></em>
  </p>
  
  <div>
    Installiamo ora il server NFS che ci servità principalmente per i live CD.<br /> <code>apt-get install nfs-kernel-server</code>
  </div>
  
  <div>
    Modifichiamo il file degli export per rendere disponibile la cartella /srv/install anche con protocollo nfs a tutta la nostra sottorete.
  </div>
  
  <p>
    <code>&lt;strong>/etc/exports&lt;/strong>&lt;br />
/pxe 192.168.0.0/24(ro,async,no_root_squash,no_subtree_check)</code><code></code>
  </p>
  
  <div>
    Ed esportiamo il filesystem.
  </div>
  
  <p>
    <code>exportfs -a</code>
  </p>
  
  <div>
    Ora inizieremo con qualcosa di molto &#8220;basic&#8221;, cioè l&#8217;avvio di un floppy con FreeDOS
  </div>
  
  <h2>
    FreeDOS
  </h2>
  
  <p>
    <em>Fonti:<span style="text-decoration: underline;"> http://possiblelossofprecision.net/?p=491</span> e <a href="https://help.ubuntu.com/community/PXEInstallMultiDistro#DOS" target="_blank" rel="noopener">https://help.ubuntu.com/community/PXEInstallMultiDistro#DOS</a></em>
  </p>
  
  <div style="text-align: justify;">
    Come esempio dei boot di tipo DOS (i buoni vecchi floppy) prenderemo il floppy di boot di FreeDOS, ma lo stesso procedimento si può applicare a qualunque immagine di floppy da 1,44MB, compresi quelli di programmi non-free (Ghost 2003, Emergency Boot Disk Window 98, ecc). Va da se che per utilizzarli è necessario possedere la relativa licenza. Creiamo la cartella e mettiamoci l’immagine del file.
  </div>
  
  <p>
    <code>root@pxe:/pxe# mkdir -p /pxe/dos/freedos-1.0&lt;br />
root@pxe:/pxe# cd /pxe/dos/freedos-1.0&lt;br />
root@pxe:/pxe/dos/freedos-1.0# wget http://www.ibiblio.org/pub/micro/pc-stuff/freedos/files/distributions/1.0/fdboot.img</code><code></code>
  </p>
  
  <div>
    Ora creiamo la parte di menu che ci interessa; basterà aggiungerla in coda a <code>/pxe/pxelinux.cfg/default</code><code>:</code>
  </div>
  
  <p>
    <code>&lt;strong>/pxe/pxelinux.cfg/default&lt;/strong>&lt;br />
LABEL FreeDOS 1.0 Floppy Disk&lt;br />
KERNEL memdisk&lt;br />
APPEND initrd=dos/freedos-1.0/fdboot.img&lt;br />
TEXT HELP&lt;br />
Boot FreeDOS 1.0&lt;br />
ENDTEXT&lt;br />
</code>
  </p>
  
  <h2>
    Predisposizione dei sorgenti per installazioni Linux e altre utility
  </h2>
  
  <div class="alert alert-info" style="text-align: justify;">
    <p>
      <small>In questo aggiornamento 2017 utilizzerò un approccio più lineare. Nelle versioni precedenti avevo utilizzato il <em>mount loop</em> per avere le immagini ISO disponibili sia per un eventuale download che per il boot (idea ispirata da <a style="color: white;" href="http://posix.in-egypt.net/content/how-mount-iso-fstab" target="_blank" rel="noopener">http://posix.in-egypt.net/content/how-mount-iso-fstab</a>). Questo metodo, pur avendo l&#8217;indiscutibile vantaggio di non duplicare l&#8217;occupazione di spazio su disco, comportava una serie di inconvenienti: innanzitutto occorreva mettere le mani di frequente nel file <em>/etc/fstab</em> (col rischio per mani inesperte di creare grossi problemi al sistema); inoltre il numero di filesystem di tipo <em>loop</em> che si possono montare è piuttosto limitato e quindi il metodo non era replicabile a piacimento.</small>
    </p>
  </div>
  
  <div style="text-align: justify;">
    <p>
      In questa nuova revisione dell&#8217;articolo scaricherò le immagini ISO nella cartella <code>/pxe/iso</code> e le estrarrò nella cartella <code>/pxe</code> suddividendole in sottocartelle per tipologia e lasciando poi la possibilità a chi lo desidera di cancellare le immagini originali).
    </p>
    
    <p>
      Inoltre userò <em>7zip</em> per l&#8217;estrazione delle immagini iso, quindi prima di procedere oltre, installiamolo:
    </p>
    
    <p>
      <code>root@pxe:~# apt-get install p7zip-full p7zip-rar</code>
    </p>
    
    <h1>
       Ubuntu (Net Install)
    </h1>
  </div>
  
  <div style="text-align: justify;">
    Pereparerò sia la versione a 32-bit che quella a 64-bit perché per alcune limitazioni nel mio hardware di test ho bisogno anche di quella a 32-bit, anche se ormai obsoleta. Per la maggior parte delle persone però è sufficiente la versione a 64-bit. Scaricherò  le immagini nella cartella <code>/pxe/iso</code> e le estrarrò nella cartella <code>/pxe</code>:
  </div>
  
  <p>
    <code>root@pxe:~# mkdir -p /pxe/iso/linux&lt;br />
root@pxe:~# cd /pxe/iso/linux&lt;br />
root@pxe:/pxe/iso/linux# wget http://archive.ubuntu.com/ubuntu/dists/trusty/main/installer-i386/current/images/netboot/mini.iso&lt;br />
root@pxe:/pxe/iso/linux# mv mini.iso ubuntu-14.04-mini-i386.iso&lt;br />
root@pxe:/pxe/iso/linux# mkdir -p /pxe/linux/ubuntu-14.04-mini-i386&lt;br />
root@pxe:/pxe/iso/linux# 7z x -ssc ubuntu-14.04-mini-i386.iso -o/pxe/linux/ubuntu-14.04-mini-i386/&lt;br />
root@pxe:/pxe/iso/linux# chmod -R 555 /pxe/linux/ubuntu-14.04-mini-i386/&lt;br />
root@pxe:/pxe/iso/linux# wget http://archive.ubuntu.com/ubuntu/dists/trusty/main/installer-amd64/current/images/netboot/mini.iso&lt;br />
root@pxe:/pxe/iso/linux# mv mini.iso ubuntu-14.04-mini-amd64.iso&lt;br />
root@pxe:/pxe/iso/linux# mkdir -p /pxe/linux/ubuntu-14.04-mini-amd64&lt;br />
root@pxe:/pxe/iso/linux# 7z x -ssc ubuntu-14.04-mini-amd64.iso -o/pxe/linux/ubuntu-14.04-mini-amd64/&lt;br />
root@pxe:/pxe/iso/linux# chmod -R 555 /pxe/linux/ubuntu-14.04-mini-amd64/</code>
  </p>
  
  <div>
    Infine è il turno del file di menu: anche in questo caso andiamo in aggiunta al menu di default, seguendo la filosofia <em>keep-it-simple</em>!
  </div>
  
  <p>
    <em>Fonte PXE Ubuntu 14.04: <a href="http://www.unixmen.com/install-configure-pxe-server-ubuntu-14-04-lts/" target="_blank" rel="noopener">http://www.unixmen.com/install-configure-pxe-server-ubuntu-14-04-lts/</a></em>
  </p>
  
  <p>
    <code>&lt;strong>/pxe/pxelinux.cfg/default&lt;/strong>&lt;br />
LABEL Ubuntu 14.04 LTS Netinstall (64-bit)&lt;br />
KERNEL linux/ubuntu-14.04-mini-amd64/linux&lt;br />
APPEND initrd=linux/ubuntu-14.04-mini-amd64/initrd.gz&lt;br />
TEXT HELP&lt;br />
Boot Ubuntu 14.04 LTS Netinstall (64-bit)&lt;br />
ENDTEXTLABEL Ubuntu 14.04 LTS Netinstall (32-bit)&lt;br />
KERNEL linux/ubuntu-14.04-mini-i386/linux&lt;br />
APPEND initrd=linux/ubuntu-14.04-mini-i386/initrd.gz&lt;br />
TEXT HELP&lt;br />
Boot Ubuntu 14.04 LTS Netinstall (32-bit)&lt;br />
ENDTEXT</code> </div>