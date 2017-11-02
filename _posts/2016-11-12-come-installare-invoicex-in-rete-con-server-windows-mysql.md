---
id: 2346
title: Come installare Invoicex in rete con server Windows MySQL
date: 2016-11-12T16:07:29+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=2346
permalink: /come-installare-invoicex-in-rete-con-server-windows-mysql-11-2016.html
dsq_thread_id:
  - "6161912748"
categories:
  - PC
  - Windows
tags:
  - fatturazione
  - invoicex
  - lan
  - mysql
  - opensource
  - rete
---
<div class="alert alert-info">
  Questo articolo è una <b><u>bozza</u></b>.
</div>

### Parte 1: MySQL

_(operazioni da eseguire solo sul PC che fungerà da server)_

Download del file **Windows (x86, 64-bit), ZIP Archive** da:
  
<http://dev.mysql.com/downloads/mysql/>

Scompattare in:
  
`C:\mysql`

Da prompt di MS-DOS come amministratore:
  
`C:\mysql\bin>mysqld --install<br />
C:\mysql\bin>net start mysql<br />
C:\mysql\bin>mysql -u root`

Da console MySQL:
  
`USE mysql;<br />
UPDATE user SET password = PASSWORD('123') WHERE user = 'root';<br />
SELECT user, host FROM user;<br />
DELETE FROM user WHERE user = '';<br />
SELECT user, host FROM user;<br />
FLUSH PRIVILEGES;<br />
SHOW DATABASES;<br />
DROP DATABASE test;<br />
SHOW DATABASES;<br />
QUIT`

Da prompt di MS-DOS come amministratore:
  
`C:\mysql\bin>mysql -u root -p`

Da console MySQL:
  
`CREATE DATABASE invoicex_db DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;<br />
CREATE USER 'invoicex_user'@'%' IDENTIFIED BY '123';<br />
GRANT ALL ON invoicex_db.* TO 'invoicex_user'@'%';<br />
QUIT`

Da prompt di MS-DOS come amministratore:
  
`C:\mysql\bin>mysql -u invoicex_user -p`

Da console MySQL:
  
``USE invoicex_db;</p>
<p>CREATE TABLE `test` (<br />
  `field` varchar(255)<br />
);</p>
<p>SHOW TABLES;<br />
DROP TABLE test;<br />
SHOW TABLES;<br />
QUIT``

Ricorsarsi di creare eccezione per il firewall in ingresso e in uscita per la porta 3306.

### Parte 2: Invoicex

_(operazioni da eseguire su tutti PC che fungeranno da client)_

Scaricare **Invoicex versione base** da:
  
<http://www.invoicex.it/>

Effettuare l&#8217;installazione standard

Al primo accesso, quando viene chiesto se utilizzare in locale o in rete, scegliere **in rete** e indicare l&#8217;indirizzo IP del PC su cui si è installato MySQL, nome del database (nell&#8217;esempio `invoicex_db`), username e password del database (nell&#8217;esempio `invoicex_user` e `123`).

### FINITO.