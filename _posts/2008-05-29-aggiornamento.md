---
id: 103
title: Aggiornamento WordPress
date: 2008-05-29T12:28:19+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: old-wordpress-guid=103
permalink: /2008-05-29-aggiornamento.html
mytory_md_visits_count:
  - "53"
categories:
  - Miscellanea
tags:
  - aggiornamento
  - problemi
  - wordpress
---
<p style="text-align: justify;">
  Dopo varie peripezie, sono finalmente riuscito ad aggiornare il blog alla versione <strong>2.5.1</strong> di <strong>WordPress</strong> e a riorganizzare un po&#8217; le cartelle del sito. Questa volta sono partito dalla <a title="Download WordPress English" href="http://wordpress.org/download/" target="_blank">versione originale in inglese</a>, aggiungendo poi i language pack <a title="Download WordPress Italiano (Language Pack)" href="http://www.wordpress-it.it/wordpress-in-italiano/" target="_blank">italiano</a> e <a title="Download WordPress Français (Language Pack)" href="http://www.wordpress-fr.net/telechargements" target="_blank">francese</a>. Ciò per avere in futuro la possibilità di inserire un opzione multilingua. A parte i bug fix, la nuova interfaccia ha veramente un sacco di nuove funzioni. Ora si lavora su una migliore integrazione col forum e uno stile comune tra i vari componenti. Per la cronaca (e per chi avesse lo stesso problema), ho dovuto procedere così:
</p>

<li style="text-align: justify;">
  ho fatto una nuova installazione della nuova versione in una cartella diversa (avevo comunque intenzione di cambiare cartella per razionalizzare la struttura del sito);
</li>
<li style="text-align: justify;">
  scaricato il database (molto piccolo nel mio caso, e poi mi serviva per il passo successivo): una copia di backup non fa mai male!;
</li>
<li style="text-align: justify;">
  modificato (con un cerca e sostituisci) il percorso degli upload ed altri percorsi, per adattarli alla nuova collocazione (<em>non necessario se la cartella finale è la stessa della prima installazione)</em>;
</li>
<li style="text-align: justify;">
  ri-uppato il database e modificato il wp-config.php per collegarsi al database della versione precedente;
</li>
<li style="text-align: justify;">
  entrato nel sito e&#8230; errore totale! Dopo un attimo di panico e con l&#8217;aiuto di zio Google ho scoperto che il problema era un text-widget con caratteri non riconosciuti. L&#8217;ho dovuto rimuovere manualmente dal database, così come consigliato qui;
</li>
<li style="text-align: justify;">
  a questo punto, loggandomi come amministratore, WordPress mi ha detto che doveva aggiornare il database, cosa che ha fatto automaticamente senza bisogno di intervento da parte mia;
</li>
<li style="text-align: justify;">
  ultima fase, aggiornamento dei plugin; ho rimosso alcuni vecchi plugin inutilizzati e aggiornato senza grosse difficoltà i rimanenti. L&#8217;unico che ha avuto qualche difficoltà è stato Custom Smilies 1.2 (prima noto come <strong>Custom Smileys 2.x</strong>). Una volta installata la nuova versione, che l&#8217;autore stesso consiglia in quanto la precedente è parecchio &#8220;vecchiotta&#8221;, ho scoperto con disappunto che gli smilies e la loro tabella di decodifica non risiedono più nel database, ma in un file php; inoltre, pur avendo seguito il consiglio di disattivare senza disinstallare la vecchia versione, la tabella è stata cancellata. Fortunatamente, dopo aver ripescato dal backup (vedete che a volte serve [;)] ) la tabella ho potuto agevolmente ricostruire i codici mancanti: non so se il plugin lo farebbe in automatico, in quanto il mio provider non dà permessi di scrittura, se non su una particolare cartella. Comunque, questo pacchetto contiene gli smilies che mi interessavano (e a cui sono troppo affezionato [:D] ) ed il relativo file php, da sostituire a quelle originale: <a href="/uploads/2008/05/snitzplus_smilies_pack.zip">SnitzPlus Smilies Pack</a>. Dato che il sito dell&#8217;autore, ogni tanto fa le bizze, ecco il pacchetto che ho utilizzato per l&#8217;installazione di <a href="/uploads/2008/05/custom-smilies12.zip">Custom Smilies 1.2</a>.
</li>

Direi che questo è tutto, in caso di necessità &#8220;mailatemi&#8221; pure: per quello che posso sarò lieto di aiutare.