# verkkokauppa
koulutehtävään verkkokauppa sivusto

Sivusto on tehty HTML/CSS ja PHP, sivussa käytetään Bootstrappia ja myös Custom CSS.

Tuotteita voi lisätä lisäämällä tuotetiedot tietokantaan, kun tuote lisätään ostoskoriin, se luo session idn ja tallentaa ostoskorin sen kanssa SQL tietokantaan.

Kun menet ostoskoriin, voit valita monta tuotetta haluat ja poistaa tuotteita, kun painat siirry kassalle,
idea on että se vie sinut erilliselle sivustolle maksamaan ja kirjoittamaan osoitteesi yms. esim. Shopify Checkout. 

Sen jälkeen ostoskori nollataan ja ostoksen jälkeen ohjaa käyttäjän takaisin verkkokauppaan 10 sekunnin jälkeen.
Sillä tämä ei ole oikea verkkokauppa, erillistä maksutapaa ei ole ja se vie vaan suoraan kiitos ostoksestasi sivulle ja nollaa ostoskorin.

Sivustossa on myös käytetty hieman JavaScriptiä, esim. kun valitsee tuotemäärän, hinta päivittyy.
