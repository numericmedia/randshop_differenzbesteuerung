randshop_differenzbesteuerung
=============================

Workaround für Randshop 2.2 , um einen alternativen Text anzuzeigen, wenn die MwSt. 0% beträgt

Legen Sie eine Steuerklasse mit 0% im Admin-Bereich an.
Wenn diese Steuerklasse einem Produkt zugewiesen wird, erscheint der Text aus der Variable $l_enthMwStaD anstelle von "Enthaltene MwSt." in der Steueraufschlüsselung im Warenkorb, in der Zusammenfassung vorm Absenden der Bestellung und in den E-Mails.

Achtung! Diese Modifikation ist nicht updatesicher und wird bei einem Update der Shop-Software überschrieben!

====
Workaround for Randshop v2.2 to rename tax info text if tax value is 0% 

Declare a new tax-class with 0% in the admin panel.
If that class will be related to a product, the new variable'S $l_enthMwStaD text will appear at the totals tax table in the cart, checkout and emails.

Attention! This mod isn't update safe!
