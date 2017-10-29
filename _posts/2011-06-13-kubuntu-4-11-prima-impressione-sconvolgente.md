---
id: 1374
title: 'Kubuntu 4.11: prima impressione&#8230; sconvolgente!'
date: 2011-06-13T18:26:58+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: http://www.danielelolli.it/?p=1374
permalink: /kubuntu-4-11-prima-impressione-sconvolgente-06-2011.html
categories:
  - PC
tags:
  - "4.11"
  - G20
  - kubuntu
  - linux
  - qosmio
  - toshiba
---
<p style="text-align: justify;">
  <a title="Kubuntu 4.11 on Toshiba Qosmio G20 - Screenshot" href="http://www.danielelolli.it/wp-content/uploads/2011/06/kubuntu-4-11-on-toshiba-qosmio-g20.png" target="_blank"><img class="alignright size-medium wp-image-1382" title="kubuntu-4-11-on-toshiba-qosmio-g20" src="http://www.danielelolli.it/wp-content/uploads/2011/06/kubuntu-4-11-on-toshiba-qosmio-g20-300x187.png" alt="" width="300" height="187" srcset="https://www.danielelolli.it/wp-content/uploads/2011/06/kubuntu-4-11-on-toshiba-qosmio-g20-300x187.png 300w, https://www.danielelolli.it/wp-content/uploads/2011/06/kubuntu-4-11-on-toshiba-qosmio-g20-1024x640.png 1024w, https://www.danielelolli.it/wp-content/uploads/2011/06/kubuntu-4-11-on-toshiba-qosmio-g20.png 1440w" sizes="(max-width: 300px) 100vw, 300px" /></a>Quando il <a title="Kubuntu - Download" href="http://www.kubuntu.org/getkubuntu/" target="_blank">LiveCD</a> di <a title="Kubuntu - Home Page" href="http://www.kubuntu.org/" target="_blank">Kubuntu 4.11</a> si è bloccato per la terza volta sul mio vecchio ma glorioso <a title="Toshiba Qosmio G20" href="http://it.computers.toshiba-europe.com/innovation/jsp/SUPPORTSECTION/discontinuedProductPage.do?service=IT&PRODUCT_ID=102997&DISC_MODEL=1" target="_blank">Toshiba Qosmio G20</a> confesso che ero scoraggiato. Ho scaricato l&#8217;<a title="Kubuntu - About Alternate CD" href="http://www.kubuntu.org/getkubuntu/" target="_blank">Alternate CD</a> senza troppo entusiasmo: della serie &#8220;proviamo&#8221; anche quella. Parto con l&#8217;installazione preparandomi ad impazzire per sistemare le partizioni dei due dischi fissi, ricordandomi che secoli fa per installare Linux avevo dovuto farlo sul disco secondario switchando il boot drive a mano dal BIOS. Lancio l&#8217;installazione testuale, seleziono la lingua e procedo fino al partizionamento. Sorpresa avevo riservato lo spazio per Linux (root+swap) e non me ne ricordavo. Un segno del destino? Configuro manualmente per utilizzare la root in ext4 e lo swap, confermo e lascio andare. Dopo un paio d&#8217;ore mi ricordo che l&#8217;installazione è in corso e vado a vedere: siamo alle scelte relative a GRUB, il boot loader. Mi chiede se installarlo sull&#8217;MBR: domanda difficile. Sono tentato, ma se mi sovrascrive il boot loader attuale (<a title="Smart Boot Manager" href="http://btmgr.sourceforge.net/" target="_blank">SBM</a>) e non funziona so già che impazzirò per poter avviare di nuovo le due installazioni di Windows indipendenti. Vabbé, proviamo; se va male ci sono le copie. Un paio di invii e il CD viene espulso. Riavvio e mi preparo al peggio. Sorpresa: funziona tutto! Guardandoci meglio non proprio tutto, ma sono comunque impressionato; funzionano anche la rotella del volume, le combinazioni con il tasto Fn ed il touchpad. Unica pecca rilevante: la tastiera è settata in americano e non c&#8217;è verso di farle cambiare idea. Pazienza, ci studierò su. Primo <em>todo</em>: sistemare GRUB; vede tutte le installazioni ma Windows non si avvia (nessuno dei due, a dire il vero). Credo che sia solo un problema di partizioni attive e nascoste. Stay tuned.
</p>

<p style="text-align: justify;">
  <strong>Funziona:</strong>
</p>

  * touchpad
  * rotella volume
  * combinazioni con tasto Fn (mute, ecc.)
  * rete _wired_
  * audio
  * tastiera e mouse aggiuntivi con adattatore USB-PS2 (!)

<p style="text-align: justify;">
  <strong>Non funziona:</strong>
</p>

  * <span style="text-decoration: line-through;">tastiera settata con mappatura US invece che IT (@ sopra il 2, accentate non funzionanti, ecc.)</span> <small><strong>RISOLTO</strong> <a title="Kubuntu 4.11: mappatura tastiera italiana" href="http://www.danielelolli.it/2011/06/kubuntu-4-11-mappatura-tastiera-italiana/">-link-</a></small>
  * <span style="text-decoration: line-through;">avvio di Windows da GRUB (dual boot o meglio trial boot)</span> <small><strong>RISOLTO</strong> <a title="Kubuntu 4.11: GRUB2 e installazioni Windows multiple" href="http://www.danielelolli.it/2011/06/kubuntu-4-11-grub2-e-installazioni-windows-multiple/">-link-</a></small>
  * <span style="text-decoration: line-through;">le due partizioni Windows non sono montate automaticamente (erano nascoste all&#8217;installazione)</span> <small><strong>RISOLTO</strong> <a title="Kubuntu 4.11: GRUB2 e installazioni Windows multiple" href="http://www.danielelolli.it/2011/06/kubuntu-4-11-grub2-e-installazioni-windows-multiple#unhide_windows">-link-</a></small>
  * il secondo hard disk non contiene partizioni riconosciute come valide (ce ne sono due invece)
  * montaggio automatico chiavette USB

<p style="text-align: justify;">
  <strong>Da verificare:</strong>
</p>

  * rete _wireless_

<div class="container_share">
  <a href="http://www.facebook.com/sharer.php?u=http://www.danielelolli.it/kubuntu-4-11-prima-impressione-sconvolgente-06-2011.html&t=Kubuntu 4.11: prima impressione&#8230; sconvolgente!" target="_blank" class="button_purab_share facebook"><span><i class="icon-facebook"></i></span>
  
  <p>
    Facebook
  </p></a> 
  
  <a href="http://twitter.com/share?url=http://www.danielelolli.it/kubuntu-4-11-prima-impressione-sconvolgente-06-2011.html&text=Kubuntu 4.11: prima impressione&#8230; sconvolgente!" target="_blank" class="button_purab_share twitter"><span><i class="icon-twitter"></i></span>
  
  <p>
    Twitter
  </p></a> 
  
  <a href="https://plus.google.com/share?url=http://www.danielelolli.it/kubuntu-4-11-prima-impressione-sconvolgente-06-2011.html" target="_blank" class="button_purab_share google-plus"><span><i class="icon-google-plus"></i></span>
  
  <p>
    Google +
  </p></a> 
  
  <a href="http://www.linkedin.com/shareArticle?mini=true&url=http://www.danielelolli.it/kubuntu-4-11-prima-impressione-sconvolgente-06-2011.html&title=Kubuntu 4.11: prima impressione&#8230; sconvolgente!" target="_blank" class="button_purab_share linkedin"><span><i class="icon-linkedin"></i></span>
  
  <p>
    Linkedin
  </p></a>
</div>