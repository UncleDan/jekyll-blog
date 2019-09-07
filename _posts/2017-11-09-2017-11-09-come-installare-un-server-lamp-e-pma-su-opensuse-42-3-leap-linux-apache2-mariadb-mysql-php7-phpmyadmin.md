---
id: 2528
title: Come installare un server LAMP e pMA su openSUSE 42.3 Leap (Linux, Apache2, MariaDB MySQL, PHP7, phpMyAdmin)
date: 2017-11-09T18:35:35+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: https://www.danielelolli.it/?p=2528
permalink: /2017-11-09-2017-11-09-come-installare-un-server-lamp-e-pma-su-opensuse-42-3-leap-linux-apache2-mariadb-mysql-php7-phpmyadmin.html
mytory_md_path:
  - https://raw.githubusercontent.com/UncleDan/uncledan.github.io/master/_posts/2017-11-09-come-installare-un-server-lamp-e-pma-su-opensuse-42-3-leap-linux-apache2-mariadb-mysql-php7-phpmyadmin.md
mytory_md_text:
  - ""
mytory_md_mode:
  - url
mytory_md_visits_count:
  - "453"
categories:
  - Linux
  - PC
  - Tech
  - Web
tags:
  - "42.3"
  - Apache2
  - LAMP
  - Leap
  - linux
  - MariaDB
  - mysql
  - openSUSE
  - PC
  - phpMyAdmin
  - Web
---
Ho di recente iniziato a sperimentare una nuova (per me) distribuzione Linux: [openSUSE 42.3 Leap](https://it.opensuse.org/). E&#8217; basata su _RedHat_, quindi pacchetti .rpm e non .deb e soprattutto un certo orientamento all&#8217;area _business_. Me l&#8217;ha consigliata un professionista di Linux e devo dire che il feeling è quello di un sistema più &#8220;rifinito&#8221; di Kubuntu e Xubuntu. Il centro di controllo **YaST** consente di fare graficamente molte impostazioni che su Ubuntu sono riservate alla linea di comando (che io amo moltissimo, ma non essendo un guru mi lascia spesso in _panne_ perché non ricordo a memoria i comandi che mi servono).

Comunque in questo articolo userò in larga parte comandi da console, che trovo molto più veloci per eseguire operazioni di installazione e prima configurazione.

Una delle prime cose che ho avuto necessità di fare è configurare un server web per i miei test e quindi&#8230; ecco la mia prima guida di openSUSE.

Che poi è in larga parte una traduzione di [questo articolo](https://en.opensuse.org/SDB:LAMP_setup).

**NOTA BENE:** Tutti i comandi di questa guida sono pensati per fare copia e incolla in una shell (io uso Putty) solitamente, ma riflettete un attimo prima di dare conferma!

Apriamo _Konsole_ o la nostra shell preferita e cominciamo con installare Apache2:

    sudo zypper install apache2

Per avviare Apache2 digitiamo:

    sudo systemctl start apache2

Se, per qualunque motivo, dobbiamo riavviarlo lo possiamo fare con il comando:

    sudo systemctl restart apache2

E per farlo partire automaticamente all&#8217;avvio, **cosa altamente consigliata**, digitiamo una volta sola il comando:

    sudo systemctl enable apache2

Ora creiamo un file di test per controllare che tutto funzioni:

    sudo sh -c 'cat > /srv/www/htdocs/index.html <<EOF
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <html>
    
    <head>
        <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
        <title>Welcome to Apache2 on openSUSE</title>
    </head>
    
    <body>
        <h1>Welcome to <i>Apache2</i> on <i>openSUSE</i>.</h1>
    </body>
    
    </html>
    EOF'

E verifichiamo andando con il nostro _browser_ preferito all&#8217;indirizzo <http://localhost/>.

Il server è pronto per l&#8217;utilizzo come test, ma non aperto &#8220;al mondo&#8221;: se vogliamo renderlo visibile occorre aprire il firewall per il servizio _http_. Ci sono molti modi per farlo, la il più veloce è da YaST | Firewall.

[<img class="alignnone size-full wp-image-2530" src="/wp-content/uploads/2017/11/Apache2.png" alt="" width="800" height="600" srcset="/wp-content/uploads/2017/11/Apache2.png 800w, /wp-content/uploads/2017/11/Apache2-300x225.png 300w, /wp-content/uploads/2017/11/Apache2-768x576.png 768w" sizes="(max-width: 800px) 100vw, 800px" />](/wp-content/uploads/2017/11/Apache2.png)

# PHP7

Ora occupiamoci di PHP.

    sudo zypper install php7 php7-mysql apache2-mod_php7

Abilitamo il modulo con il comando:

    sudo a2enmod php7

E riavviamo il server:

    sudo systemctl restart apache2

Anche in questo caso creiamo un piccolo file di test:

    sudo sh -c 'cat > /srv/www/htdocs/phpinfo.php <<EOF
    <?php phpinfo(); ?>
    EOF'

Ora, sempre con il browser che preferiamo, colleghiamoci a <http://localhost/phpinfo.php>.

[<img class="alignnone size-full wp-image-2532" src="/wp-content/uploads/2017/11/PHP7.png" alt="" width="800" height="600" srcset="/wp-content/uploads/2017/11/PHP7.png 800w, /wp-content/uploads/2017/11/PHP7-300x225.png 300w, /wp-content/uploads/2017/11/PHP7-768x576.png 768w" sizes="(max-width: 800px) 100vw, 800px" />](/wp-content/uploads/2017/11/PHP7.png)

&nbsp;

# MariaDB (MySQL)

_openSUSE_ usa un pacchetto alternativo a _MySQL_ di nome **MariaDB**, più completo, che comunque viene chiamato dal sistema _mysql_ (e così lo chiameremo anche noi). Nelle ultime release il pacchetto base è installato per _default_, ma c&#8217;è da installare un pacchetto di utilità aggiuntive per l&#8217;amministrazione.

Quindi procediamo:

    sudo zypper install mariadb mariadb-tools

Avviamo il server:

    sudo systemctl start mysql

E assicuriamoci che sia in avvio automatico:

    sudo systemctl enable mysql

Anche in questo caso, se per qualunque motivo fosse necessario un riavvio del servizio, basterà digitare:

    sudo systemctl restart mysql

**Messa in sicurezza** Prima di utilizzare _mysql_ in una ambiente di produzione sono necessarie alcune modifiche per la sicurezza, che fortunatamente in _openSUSE_ sono raggruppate da uno script. Lo andremo ad eseguire e confermeremo con **y** (_yes_) **_TUTTE LE RICHIESTE_**.

    sudo mysql_secure_installation

Verrà anche richiesto di inserire una password di **root**: è ovviamente indispensabile appuntarsela per usi futuri.

Ecco la lista delle richieste, per confronto:

<pre><code class="language-bash">NOTE: RUNNING ALL PARTS OF THIS SCRIPT IS RECOMMENDED FOR ALL MariaDB
      SERVERS IN PRODUCTION USE!  PLEASE READ EACH STEP CAREFULLY!

In order to log into MariaDB to secure it, we'll need the current
password for the root user.  If you've just installed MariaDB, and
you haven't set the root password yet, the password will be blank,
so you should just press enter here.

Enter current password for root (enter for none):
OK, successfully used password, moving on...

Setting the root password ensures that nobody can log into the MariaDB
root user without the proper authorisation.

Set root password? [Y/n] y
New password:
Re-enter new password:
Password updated successfully!
Reloading privilege tables..
 ... Success!

By default, a MariaDB installation has an anonymous user, allowing anyone
to log into MariaDB without having to have a user account created for
them.  This is intended only for testing, and to make the installation
go a bit smoother.  You should remove them before moving into a
production environment.

Remove anonymous users? [Y/n] y
 ... Success!

Normally, root should only be allowed to connect from 'localhost'.  This
ensures that someone cannot guess at the root password from the network.

Disallow root login remotely? [Y/n] y
 ... Success!

By default, MariaDB comes with a database named 'test' that anyone can
access.  This is also intended only for testing, and should be removed
before moving into a production environment.

Remove test database and access to it? [Y/n] y
 - Dropping test database...
 ... Success!
 - Removing privileges on test database...
 ... Success!

Reloading the privilege tables will ensure that all changes made so far
will take effect immediately.

Reload privilege tables now? [Y/n] y
 ... Success!

Cleaning up...

All done!  If you've completed all of the above steps, your MariaDB
installation should now be secure.

Thanks for using MariaDB!</code></pre>

# phpMyAdmin

**phpMyAdmin** (a volte abbreviato in _pma_) è uno strumento che consente di amministrare il database tramite una interfaccia _web_.

**ATTENZIONE!** in tutti i comandi successivi la grafìa corretta è &#8220;phpMyAdmin&#8221; che in _openSUSE_ è **CASE SENSITIVE**: se lo scriviamo minuscolo, otterremo messaggi di errore.

    sudo zypper install phpMyAdmin

Nel mio caso è stato necessario abilitare una delle estensione che erano state installate automaticamente:

    sudo a2enmod php7-mbscript

e riavviare il server _web_:

    sudo systemctl restart apache2

Ora per accedere all&#8217;interfaccia colleghiamoci, sempre con il browser preferito, all&#8217;indirizzo <http://localhost/phpMyAdmin/> da locale oppure a **http://_indirizzo_ip_/phpMyAdmin/** da remoto.

[<img class="alignnone size-full wp-image-2531" src="/wp-content/uploads/2017/11/phpMyAdmin.png" alt="" width="800" height="600" srcset="/wp-content/uploads/2017/11/phpMyAdmin.png 800w, /wp-content/uploads/2017/11/phpMyAdmin-300x225.png 300w, /wp-content/uploads/2017/11/phpMyAdmin-768x576.png 768w" sizes="(max-width: 800px) 100vw, 800px" />](/wp-content/uploads/2017/11/phpMyAdmin.png)

Ecco pronto il nostro server **LAMP** (anzi _LAMPpma_!)