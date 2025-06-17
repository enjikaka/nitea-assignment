## 2025-06-17 19:30
- Front-end ska göra AJAX-anrop och är i en helt annan stack (PHP) än front-end (JS) så vi delar in dem i olika mappar/tjänster och gör ett smutt REST API för kommunicationen. (Vi skippar väl X i AJAX och gör ett JSON-API - AJAJ!)
- Ser framför mig att vi gör en docker compose-setup för att snurra igång allt smidigt. (tiden för WAMP/LAMP är långt förbi...)
- Ett tag sedan jag pillade på PHP - senast lite WordPress-konkande (gud bevara mig väl...). Laravel vet jag är hippt men kanske overkill? Vi ska bara spotta ut lite data från en DB i JSON-format så det gör vi väl enkelt utan ramverk.
- Angåend front-end-biten har jag pillat med Sass förr, men nu finns i princip allt man ville ha Sass för (variabler och nesting) i vanlig CSS så den bitar skippar jag - så vi helt och hållet slipper byggsteg för front-end i detta lilla projekt. (Hade jag valt ett hade jag förmodligen kört på Vite idag, eller göra något eget med Gulp och/eller Rollup om Vites begränsningar slår mig på fingrarna)

## 2025-06-17 20:39

- Har nu en scaffold till REST API i PHP baserat på https://github.com/FarrelAD/Basic-PHP-RESTful-API
- Konfigurerade docker-compose att starta databasen med lite exempeldata som lever i test/products.sql
