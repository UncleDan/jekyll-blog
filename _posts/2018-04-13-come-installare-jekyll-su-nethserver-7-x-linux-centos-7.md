---
title: Come installare Jekyll su NethServer 7.x (Linux Centos 7)
date: 2018-04-13T18:35:00+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=180413183500
permalink: /2018-04-13-come-installare-jekyll-su-nethserver-7-x-linux-centos-7.html
categories:
  - PC
  - Tech
  - Linux
  - Web
---

Per chi non lo sapesse **Jekyll** è un “compilatore” di pagine HTML statiche che consente di creare siti “effetto blog” di grande impatto grafico e che non necessitano di alcun database.

Certo è uno strumento più tecnico e meno fruibile ai più rispetto, ad esempio, a WordPress, ma si sta ritagliando una sua nicchia.

In questo articolo vedremo come installare un piccolo server (nel mio caso una macchina virtuale) che ci consente di approcciare Jekyll.
# Configurazione cartella per sorgenti

Per prima cosa installiamo **NethServer 7** con le opzioni che preferiamo: io ho usato la Unattended Installation per ridurre al minimo i tempi di configurazione.

Una volta installato, colleghiamoci con un browser all'interfaccia di configurazione server all'indirizzo:
```
https://<indirizzoip>:980
```
L'utente è ```root``` mentre la password di default è ```Nethesis,1234```: al primo accesso saremo obbligati a cambiare la password e a fornire pochi parametri basilari (fuso orario, ecc.). Inoltre, se vogliamo, possiamo impostare un IP statico per la scheda di rete principale.

Terminate le operazioni di base entriamo nel **Software Center** e selezioniamo la linguetta **Updates** e poi il bottone **Download and install**.

Finito l'aggiornamento, sempre in **Software Center** selezioniamo la linguetta **Available**, poi spuntiamo la casella di controllo **File server** e premiamo il bottone **Add**.

Ora creiamo una cartella condivisa (sarà accessibile a tutti in rete, perché la configurazione dei permessi esula dagli intenti di questo articolo: nel pannello di sinistra scegliamo **Shared folders**, bottone **Create new**, inseriamo sia come nome che descrizione ```jekyll``` e assicuriamoci che rimanga spuntata la casella *browseable*.

La cartella sarà ora disponibile all'indirizzo (*Windows style*) ```\\<indirizzoip>\jekyll``` . 

# Installare RVM, Ruby e Jekyll

Per funzionare **Jekyll** ha bisogno di **Ruby**, quindi per prima cosa installiamo RVM, che è il modo più veloce di installare Ruby e successivamente Ruby stesso. Da qui in poi lavoreremo da *console*: io, ad esempio, solitamente mi collego in SSH con **Putty**.

```
gpg --keyserver hkp://keys.gnupg.net --recv-keys 409B6B1796C275462A1703113804BB82D39DC0E3
curl -sSL https://get.rvm.io | bash -s stable
```
Quasi sempre per rendere operativo Ruby occorre disconnettersi, riconnettersi e fare tutta una serie di operazione che in realtà vendono eseguite automaticamente al primo riavvio, quindi trovo molto più pratico lanciare un bel ```reboot``` e riprendere da lì.

Dopo il riavvio ricolleghiamoci e installiamo Ruby:
```
rvm install ruby
rvm --default use ruby
```

Se tutto è andato bene, digitando questo comando dovremmo vedere la versione di Ruby installata:
```
ruby -v
```

Ed infine possiamo installare Jekyll:
```
gem install jekyll bundler
```

# Il nostro primo sito con *Jekyll*

Siamo pronti per il nostro primo sito! Posizioniamoci nella cartella precedentemente creata (ovviamente questo è il percorso all'interno del file system Linux):
```
cd /var/lib/nethserver/ibay/jekyll/
```

Creiamo ora un sito con il tema di default:
```
jekyll new .
```

E compilamolo nella root di Apache **(ATTENZIONE! Questo cancellerà ogni volta che lanciamo il comando tutto il contenuto della cartella ```/var/www/html``` e lo sostituirà con il nostro sito Jekyll!)**

```
jekyll build -d /var/www/html/
```

Ora collegandoci al nostro server con qualsiasi browser **NethServer** vedremo...
```
http://<indirizzoip>/
```
**...il nostro primo sito Jekyll!**

Ora basterà editare i file nella cartella condivisa (anche da Windows) e lanciare il comando:
```
jekyll build -d /var/www/html/
```
per continuare a sperimentare e vedere gli effetti delle modifiche.
*Tradotto e adattato da: HostPresto "How to Install Jekyll on CentOS 7", 2nd February 2017 - https://hostpresto.com/community/tutorials/how-to-install-jekyll-on-centos-7/*
