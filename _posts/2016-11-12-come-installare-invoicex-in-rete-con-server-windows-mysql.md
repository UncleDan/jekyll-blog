---
id: 2346
title: Come installare Invoicex in rete con server Windows MySQL
date: 2016-11-12T16:07:29+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: https://www.uncledan.it/?p=2346
permalink: /2016-11-12-come-installare-invoicex-in-rete-con-server-windows-mysql.html
dsq_thread_id:
  - "6161912748"
mytory_md_visits_count:
  - "1458"
mytory_md_path:
  - ""
mytory_md_text:
  - ""
mytory_md_mode:
  - url
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
  
`</p>
<pre>C:\mysql</pre>
<p>`

Da prompt di MS-DOS come amministratore:
  
`</p>
<pre>C:\mysql\bin>mysqld --install
C:\mysql\bin>net start mysql
C:\mysql\bin>mysql -u root</pre>
<p>`

Da console MySQL:
  
`</p>
<pre>USE mysql;
UPDATE user SET password = PASSWORD('123') WHERE user = 'root';
SELECT user, host FROM user;
DELETE FROM user WHERE user = '';
SELECT user, host FROM user;
FLUSH PRIVILEGES;
SHOW DATABASES;
DROP DATABASE test;
SHOW DATABASES;
QUIT</pre>
<p>`

Da prompt di MS-DOS come amministratore:
  
`</p>
<pre>C:\mysql\bin>mysql -u root -p</pre>
<p>`

Da console MySQL:
  
`</p>
<pre>CREATE DATABASE invoicex_db DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
CREATE USER 'invoicex_user'@'%' IDENTIFIED BY '123';
GRANT ALL ON invoicex_db.* TO 'invoicex_user'@'%';
QUIT</pre>
<p>`

Da prompt di MS-DOS come amministratore:
  
`</p>
<pre>C:\mysql\bin>mysql -u invoicex_user -p</pre>
<p>`

Da console MySQL:
  
`</p>
<pre>USE invoicex_db;</pre>
<p>`

``</p>
<pre>CREATE TABLE `test` (
`field` varchar(255)
);</pre>
<p>``

`</p>
<pre>SHOW TABLES;
DROP TABLE test;
SHOW TABLES;
QUIT</pre>
<p>`

Ricorsarsi di creare eccezione per il firewall in ingresso e in uscita per la porta 3306.

### Parte 2: Invoicex

_(operazioni da eseguire su tutti PC che fungeranno da client)_

Scaricare **Invoicex versione base** da:
  
<http://www.invoicex.it/>

Effettuare l&#8217;installazione standard

Al primo accesso, quando viene chiesto se utilizzare in locale o in rete, scegliere **in rete** e indicare l&#8217;indirizzo IP del PC su cui si è installato MySQL, nome del database (nell&#8217;esempio `invoicex_db`), username e password del database (nell&#8217;esempio `invoicex_user` e `123`).

### FINITO.

<div class="alert alert-success">
  <big><b>Aggiornamento 07/05/2018:</b> ho fatto un piccolo video su questa installazione. Lo trovate a questo indirizzo <a href="https://youtu.be/fd6HUHJBfuY" target="_blank" style="color:white;">https://youtu.be/fd6HUHJBfuY</a>.</big>
</div>