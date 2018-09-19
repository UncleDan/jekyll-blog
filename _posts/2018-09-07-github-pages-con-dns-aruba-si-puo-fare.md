---
id: 2613
title: 'GitHub Pages con DNS Aruba: SI PUO&#8217; FARE!!!'
date: 2018-09-07T18:22:53+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: https://www.danielelolli.it/?p=2613
permalink: /2018-09-07-github-pages-con-dns-aruba-si-puo-fare.html
categories:
  - Tech
  - Web
tags:
  - aruba
  - dns
  - github
  - pages
  - sito
---
<!-- wp:paragraph -->

Confesso che la cosa mi ha fatto penare non poco, e anche una sommaria ricerca su Internet non mi lasciava molte speranze. E invece alle volte anche Internet &#8220;sbaglia&#8221;.

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

Non mi dilungo a spiegare cosa sono le [GitHub Pages](https://pages.github.com/):  in estrema sintesi sono qualcosa che ci dà la possibilità di pubblicare un sito composto da pagine HTML statiche (inserite direttamente o generate con vari motori, come [Jekyll](https://jekyllrb.com/)), **gratuitamente** e sfruttando lo stesso meccanismo delle **revisioni** che usano i programmatori.

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

Dove sta l&#8217;inghippo? nel fatto che una pagina GitHub ha un indirizzo di questo tipo:  
<https://lolli-cloud.github.io/>   
Mentre io, che ho appena comprato il mio bel dominio con [Aruba](https://www.aruba.it/), voglio che sia visibile a questo indirizzo:  
<http://lolli.cloud/>  
Quello che ci manca per fare la connessione è la gestione dei DNS, che GitHub spiega dettagliatamente (in inglese) [qui](https://help.github.com/articles/setting-up-an-apex-domain-and-www-subdomain/). Magari voi siete più bravi di me, ma io mi sono scervellato non poco, anche perché l&#8217;indirizzamento di un dominio _apex_ (cioè l&#8217;indirizzo senza &#8220;www&#8221; davanti!) si ottiene con una chiocciola (@) in tutti i gestori DNS&#8230; tranne Aruba! Nella gestione dei DNS di aruba quel risultato si ottiene **lasciando vuoto** il campo del sottodominio da indirizzare.

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

Ora tagliamo corto e vi posto le schermate. Da fare su GitHub nel repository della pagina:

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

[<img class="alignnone size-medium wp-image-2614" src="https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_00_12-Options-300x152.png" alt="" width="300" height="152" srcset="https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_00_12-Options-300x152.png 300w, https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_00_12-Options-768x388.png 768w, https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_00_12-Options-1024x518.png 1024w, https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_00_12-Options.png 1060w" sizes="(max-width: 300px) 100vw, 300px" />](https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_00_12-Options.png)

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

[<img class="alignnone size-medium wp-image-2615" src="https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_02_48-Options-300x176.png" alt="" width="300" height="176" srcset="https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_02_48-Options-300x176.png 300w, https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_02_48-Options-768x451.png 768w, https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_02_48-Options-1024x601.png 1024w, https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_02_48-Options.png 1059w" sizes="(max-width: 300px) 100vw, 300px" />](https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_02_48-Options.png)

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

Da fare nella Gestione DNS di Aruba:

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

[<img class="alignnone size-medium wp-image-2616" src="https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_07_21-Visualizza-impostazioni-e-salva-300x257.png" alt="" width="300" height="257" srcset="https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_07_21-Visualizza-impostazioni-e-salva-300x257.png 300w, https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_07_21-Visualizza-impostazioni-e-salva-768x659.png 768w, https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_07_21-Visualizza-impostazioni-e-salva-1024x879.png 1024w, https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_07_21-Visualizza-impostazioni-e-salva.png 1077w" sizes="(max-width: 300px) 100vw, 300px" />](https://www.danielelolli.it/wp-content/uploads/2018/09/2018-09-07-18_07_21-Visualizza-impostazioni-e-salva.png)

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

Ovviamente _lolli.cloud_ e _lolli-cloud.github.io_ vanno sostituiti, rispettivamente, con il vostro dominio e la vostra _GitHub_ page, ma fate attenzione al punto (.) alla fine del record CNAME. Il record CNAME non è indispensabile, ma serve perché il sito venga raggiunto sia con il &#8220;www&#8221; davanti che senza, perché ho scoperto che noi italiani tendiamo a pensare che i siti senza il &#8220;www&#8221; non esistano! In particolare: se su GitHub Pages specifico il dominio _lolli.cloud_ (come ho fatto io) anche chi digita _www.lolli.cloud_ viene reindirizzato a _lolli.cloud_; se invece su GitHub Pages specifico il dominio _www.lolli.cloud_ anche chi digita _lolli.cloud_ viene reindirizzato a _www.__lolli.cloud_.

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

_**Contenuto del file CNAME (o Settings di GitHub Pages)**_

<!-- /wp:paragraph -->

<!-- wp:table -->

<table class="wp-block-table">
  <tr>
    <td>
      <em>(tuodominio).(tuotld)</em>
    </td>
  </tr>
</table>

<!-- /wp:table -->

<!-- wp:paragraph -->

**_Riassunto dei record da creare (Aruba)_**

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

**Record di tipo A**

<!-- /wp:paragraph -->

<!-- wp:table -->

<table class="wp-block-table">
  <tr>
    <td>
      <em>(vuoto)</em>
    </td>
    
    <td>
      185.199.108.153
    </td>
  </tr>
  
  <tr>
    <td>
      <em>(vuoto)</em>
    </td>
    
    <td>
      185.199.109.153
    </td>
  </tr>
  
  <tr>
    <td>
      <em>(vuoto)</em>
    </td>
    
    <td>
      185.199.110.153
    </td>
  </tr>
  
  <tr>
    <td>
      <em>(vuoto)</em>
    </td>
    
    <td>
      185.199.111.153
    </td>
  </tr>
</table>

<!-- /wp:table -->

<!-- wp:paragraph -->

**Record di tipo CNAME**

<!-- /wp:paragraph -->

<!-- wp:table -->

<table class="wp-block-table">
  <tr>
    <td>
      www
    </td>
    
    <td>
      <em>(tuousername)</em>.github.io<strong>.</strong>
    </td>
  </tr>
</table>

<!-- /wp:table -->

<!-- wp:paragraph -->

Unica pecca riscontrata: il reindirizzamento funziona sia per _http_ che _https_ ma il certificato installato automaticamente da _GitHub_ ([novità di Maggio 2018](https://blog.github.com/2018-05-01-github-pages-custom-domains-https/)) non viene riconosciuto come sicuro da Chrome. Poco male, indagherò!

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

**AGGIORNAMENTO:** da qualche giorno il sito è anche riconosciuto come sicuro da Chrome e altri browser. Contando che non sono intervenuto in nessuna maniera, forse bastava dare tempo al tempo.

<!-- /wp:paragraph -->