---
id: 1958
title: Aggiungere driver di rete Realtek e Marvell a ESXi 5.5 Update 2
date: 2015-03-16T21:55:50+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: old-wordpress-guid=1958
permalink: /2015-03-16-aggiungere-driver-di-rete-realtek-e-marvell-a-esxi-5-5-update-2.html
dsq_thread_id:
  - "6155964386"
mytory_md_visits_count:
  - "406"
categories:
  - PC
  - Tech
tags:
  - "5.5"
  - add
  - driver
  - esxi
  - marvell
  - network card
  - nic
  - realtek
  - unsupported
  - update 2
  - vmware
---
<p style="text-align: justify;">
  Chi, come il sottoscritto, si cimenta per le prime volte con VMWare, si renderà conto presto o tardi che non sempre l&#8217;equazione più nuovo uguale migliore è corretta.
</p>

<p style="text-align: justify;">
  Nel nostro caso lo sarà in assoluto (sicuramente dalle versione 5.0 e 5.1 alla 5.5 le migliorie sono tante), ma ci creerà qualche problema per il setup su sistemi non &#8220;professionali&#8221;.
</p>

<p style="text-align: justify;">
  Infatti se la limitazione di memoria (minimo 4GB) ormai è abbastanza facilmente aggirabile visti i prezzi non spropositati della ram, lo stesso non si può dire di quella delle schede di rete.
</p>

<p style="text-align: justify;">
  Con l&#8217;avvento della release 5.5 di ESXi VMWare ha deciso di estromettere dalla release tutti i driver non supportati direttamente dalla casa madre. Questo ha portato ad escludere praticamente tutte le schede di rete con chipset Realtek e Marvell a favore di quelle con chipset Intel.
</p>

<p style="text-align: justify;">
  Il problema è che la stragrande maggioranza delle schede di rete integrate nelle motherboard di fascia consumer e di quelle PXI express economiche sono a chipset Realtek!
</p>

<p style="text-align: justify;">
  Leggendo <a title="Fixing broken Realtek and Marvell NICs in ESXi 5.5" href="http://www.ryanbirk.com/fixing-broken-realtek-and-marvell-nics-in-esxi-5-5/" target="_blank">qua</a> e <a title="How to add the missing ESXi 5.0 drivers to the ESXi 5.5 installation ISO" href="http://www.v-front.de/2013/09/how-to-add-missing-esxi-50-drivers-to.html" target="_blank">là</a> su Internet ho scoperto che il driver presente fino alla versione 5.1 è in realtà ancora perfettamente funzionante ed è stato escluso solo per il discorso di licensing sopra citato (non è sviluppato in collaborazione con VMware e quindi rimarrà sempre nello status di &#8220;unsupported&#8221;).
</p>

<p style="text-align: justify;">
  Integrarlo non è semplicissimo, ma nemmeno impossibile.
</p>

<p style="text-align: justify;">
  L&#8217;unico problema è che tutti i tutorial che ho trovato si riferivano alle prime versioni della release 5.5, che invece è già arrivata all&#8217;update 2. Per creare ISO personalizzate è necessario conoscere il nome del profilo da clonare per poi aggiungere, informazione che appunto non trovavo da nessuna parte per la versione 5.5u2. Come spesso accade la cosa è più semplice del previsto: navigando all&#8217;interno dell&#8217;ISO scaricata da VMware ho trovato un file dal nome eloquente e cioè PROFILE.XML. Bingo! Il ramo da clonare (a quanto ho capito il meccanismo è simile al merge di GitHub per clonare e aggiornare i sorgenti) si chiama <strong>ESXi-5.5.0-20140902001-standard</strong>.
</p>

<p style="text-align: justify;">
  Veniamo al dunque.
</p>

<p style="text-align: justify;">
  Guida <strong>quick-and-dirty</strong> sulle operazioni da effettuare.
</p>

<h3 style="text-align: justify;">
  STEP 1
</h3>

<p style="text-align: justify;">
  Scaricare ed installare il tool di VMware <a title="PowerCLI" href="http://www.vmware.com/go/powercli" target="_blank">PowerCLI</a> da qui:
</p>

<p style="text-align: justify;">
  <a title="http://vmware.com/downloads/download.do?downloadGroup=PCLI501" href="http://vmware.com/downloads/download.do?downloadGroup=PCLI501" target="_blank">http://vmware.com/downloads/download.do?downloadGroup=PCLI501</a>
</p>

<h3 style="text-align: justify;">
  STEP 2
</h3>

<p style="text-align: justify;">
  Creare nella cartella di installazione un nuovo file di testo, chiamarlo ad esempio <strong>fixnic.ps1</strong> (l&#8217;unica cosa realmente importante è l&#8217;estensione) ed incollarci lo script sottostante:
</p>

`# Original script: http://www.ryanbirk.com/fixing-broken-realtek-and-marvell-nics-in-esxi-5-5/<br />
# Some hints from script: http://www.v-front.de/2013/09/how-to-add-missing-esxi-50-drivers-to.html<br />
# I found the profile name in VMware-VMvisor-Installer-5.5.0.update02-2068190.x86_64.iso\UPGRADE\PROFILE.XML</p>
<p># If you've never used PowerCLI before, set the ExecutionPolicy to RemoteSigned. Skip this step if you have already.<br />
Set-ExecutionPolicy RemoteSigned</p>
<p># Makes sure the ImageBuilder snapin is added.<br />
Add-PSSnapin VMware.ImageBuilder</p>
<p># Connects to the software depot. Takes a few seconds to connect.<br />
Add-EsxSoftwareDepot https://hostupdate.vmware.com/software/VUM/PRODUCTION/main/vmw-depot-index.xml</p>
<p># Takes the standard ESXi 5.5 update 2 iso and clones it so we can essentially slipstream in the missing drivers.<br />
$OriginalProfile = Get-EsxImageProfile ESXi-5.5.0-20140902001-standard<br />
$CustomizedProfile = New-EsxImageProfile -CloneProfile $OriginalProfile -Vendor $OriginalProfile.Vendor -Name (($OriginalProfile.Name) + "-w-realtek-marvell-NICs") -Description (($OriginalProfile.Description) + " with Realtek and Marvell NICs drivers")</p>
<p># Use these two for broken Realtek adapters.<br />
Add-EsxSoftwarePackage -SoftwarePackage "net-r8168" -ImageProfile $CustomizedProfile<br />
Add-EsxSoftwarePackage -SoftwarePackage "net-r8169" -ImageProfile $CustomizedProfile</p>
<p># Use these two for broken Marvell adapters.<br />
Add-EsxSoftwarePackage -SoftwarePackage "net-sky2" -ImageProfile $CustomizedProfile<br />
Add-EsxSoftwarePackage -SoftwarePackage "net-s2io" -ImageProfile $CustomizedProfile</p>
<p># Take our newly modified profile and spit out an iso to use. This will take a few minutes. Be patient.<br />
Export-EsxImageProfile -ImageProfile $CustomizedProfile -ExportToISO -FilePath ("D:\TEMPESXi\" + ($OriginalProfile.Name) + "-w-realtek-marvell-NICs" + ".iso")`

### STEP 3

Fare click col destro sul file appena salvato e scegliere Modifica: se avete fatto correttamente i passaggi precedenti il fle si aprirà in PowerCLI.

### STEP 4

Cliccare l&#8217;icona con il tasto &#8220;Play&#8221; verde, confermare con Sì alla richiesta se realmente di desidera eseguire gli script non firmati e&#8230; Pazientare!

### FINITO

Se tutto è corretto troverete la iso customizzata nella cartella desiderata: nel mio script di esempio si tratta di D:\TEMPESXi.