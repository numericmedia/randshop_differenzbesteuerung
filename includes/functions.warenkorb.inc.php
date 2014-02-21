<?php

/**
 * 
 * Copyright (C) Die Randgruppe GmbH
 * 
 * http://www.randshop.com
 * http://www.dierandgruppe.com
 * 
 * Unter der Lizenz von Die Randgruppe GmbH:
 * http://www.randshop.com/Lizenz
 *  
 * $Author$
 * $Date$
 * $Revision$
 * 
 */

include_once(DATEIPFAD . "includes/functions.zahlungsart.inc.php");
include_once(DATEIPFAD . "includes/functions.versandart.inc.php");
include_once(DATEIPFAD . "includes/functions.rabattstaffeln.inc.php");

if(KUNDENGRUPPEN) {
	include_once(DATEIPFAD . "includes/functions.mod.kundengruppen.inc.php");
} else {
	include_once(DATEIPFAD . "includes/functions.kundengruppen.inc.php");
}

if (GUTSCHEINAKTIONEN) {
	include_once(DATEIPFAD . "includes/functions.mod.gutscheinaktionen.inc.php");
}

if(KONFIGURATOR) {
    include_once(DATEIPFAD . "includes/functions.mod.konfigurator.inc.php");
}


function AddShirtWarenkorb() {
    // Kundendaten einlesen
    $KundenObject = GetKundenDetail($_SESSION["mail"], "", $_SESSION["languageid"]);

    // ********************************************************************************
    // ***** Shirt-Bestellungs-Tabelle füllen                                     *****
    // ********************************************************************************

    // Präfix für die Session setzen
    if ($_SESSION["myshirtid"]) {
        $SessionPraefix = "myshirt_";
    } else {
        $SessionPraefix = "normal_";
    }

    $ShirtProduktID = $_SESSION[$SessionPraefix . "shirtproduktid"];
    $ShirtProduktFarbID = $_SESSION[$SessionPraefix . "shirtproduktfarbid"];
    $ShirtProduktGroesseID = $_SESSION[$SessionPraefix . "shirtproduktgroesseid"];
    $ShirtTyp1 = $_SESSION[$SessionPraefix . "shirttyp1"];
    $ShirtTyp2 = $_SESSION[$SessionPraefix . "shirttyp2"];
    $ShirtDruckbereichID1 = $_SESSION[$SessionPraefix . "druckbereichid1"];
    $ShirtDruckbereichID2 = $_SESSION[$SessionPraefix . "druckbereichid2"];
    $MotivID1 = $_SESSION[$SessionPraefix . "motivid1"];
    $MotivID2 = $_SESSION[$SessionPraefix . "motivid2"];
    $SchriftText1 = $_SESSION[$SessionPraefix . "schrifttext1"];
    $SchriftText2 = $_SESSION[$SessionPraefix . "schrifttext2"];
    $SchriftArtID1 = $_SESSION[$SessionPraefix . "schriftartid1"];
    $SchriftArtID2 = $_SESSION[$SessionPraefix . "schriftartid2"];
    $SchriftFarbeID1 = $_SESSION[$SessionPraefix . "schriftfarbeid1"];
    $SchriftFarbeID2 = $_SESSION[$SessionPraefix . "schriftfarbeid2"];
    $SchriftGroesseID1 = $_SESSION[$SessionPraefix . "schriftgroesseid1"];
    $SchriftGroesseID2 = $_SESSION[$SessionPraefix . "schriftgroesseid2"];

    $ShirtProduktObject = GetProdukt($ShirtProduktID);
    $ShirtProduktFarbeObject = GetProduktFarbe('', $ShirtProduktFarbID);
    $ShirtProduktGroesseObject = GetProduktGroesse('', $ShirtProduktGroesseID);
    $ShirtDruckbereichArray = GetDruckbereich('');
    $MotivArray = GetMotiv();
    $MotivObject = GetMotiv($MotivID1);
    $ShirtSchriftartArray = GetSchriftart();
    $ShirtSchriftfarbeArray = GetSchriftfarbe();
    $ShirtSchriftgroesseArray = GetSchriftgroesse();

    // Daten für den SQL-String vorbereiten
    $sql_produktid = $ShirtProduktID;
    $sql_produktmwst = $ShirtProduktObject->mwst;
    $sql_produktname = $ShirtProduktObject->produktname;

    $sql_produktartikelnummer = $ShirtProduktObject->produktartikelnummer;
    $sql_farbid = $ShirtProduktFarbID;
    $sql_farbname = $ShirtProduktFarbeObject->farbname;
    $sql_groesseid = $ShirtProduktGroesseID;
    $sql_groessename = $ShirtProduktGroesseObject->groessename;
    $sql_vorderseite_type = $ShirtTyp1;

    if ($ShirtTyp1 == 2) {
        $sql_vorderseite_motivid = $MotivID1;
        $sql_vorderseite_motivname = $MotivArray[$MotivID1]["motivname"];
        $sql_vorderseite_motivnummer = $MotivArray[$MotivID1]["motivnummer"];
    }

    $sql_vorderseite_druckbereichid = $ShirtDruckbereichID1;
    $sql_vorderseite_druckbereichname = $ShirtDruckbereichArray[$ShirtDruckbereichID1]["druckbereichname"];

    $sql_rueckseite_type = $ShirtTyp2;

    if ($ShirtTyp2 == 2) {
        $sql_rueckseite_motivid = $MotivID2;
        $sql_rueckseite_motivname = $MotivArray[$MotivID2]["motivname"];
        $sql_rueckseite_motivnummer = $MotivArray[$MotivID2]["motivnummer"];
    }

    $sql_rueckseite_druckbereichid = $ShirtDruckbereichID2;
    $sql_rueckseite_druckbereichname = $ShirtDruckbereichArray[$ShirtDruckbereichID2]["druckbereichname"];

    $SQLString = "INSERT INTO " . TABLE_KONFIGURATOR_BESTELLUNG . " (name, produktid, produktname, produktartikelnummer, mwst, farbid, farbname, groesseid, groessename, vorderseite_type, vorderseite_motivid, vorderseite_motivnummer, vorderseite_motivname, vorderseite_druckbereichid, vorderseite_druckbereichname, rueckseite_type, rueckseite_motivid, rueckseite_motivnummer, rueckseite_motivname, rueckseite_druckbereichid, rueckseite_druckbereichname) VALUES ('" . $sql_name . "', '" . $sql_produktid . "', '" . $sql_produktname . "', '" . $sql_produktartikelnummer . "', '" . $sql_produktmwst . "', '" . $sql_farbid . "', '" . $sql_farbname . "', '" . $sql_groesseid . "', '" . $sql_groessename . "', '" . $sql_vorderseite_type . "', '" . $sql_vorderseite_motivid . "', '" . $sql_vorderseite_motivnummer . "', '" . $sql_vorderseite_motivname . "', '" . $sql_vorderseite_druckbereichid . "', '" . $sql_vorderseite_druckbereichname . "', '" . $sql_rueckseite_type . "', '" . $sql_rueckseite_motivid . "', '" . $sql_rueckseite_motivnummer . "', '" . $sql_rueckseite_motivname . "', '" . $sql_rueckseite_druckbereichid . "', '" . $sql_rueckseite_druckbereichname . "')";
    $MySQLQuery = mysql_query($SQLString);
    $ShirtBestellID = mysql_insert_id();

    if ($ShirtTyp1 == 3) {
        $newMotivFileName = 'uploadmotiv_' . $ShirtBestellID . '_vorn.' . $_SESSION[$SessionPraefix . 'motivupload_ext_1'];
        rename(DATEIPFAD . 'admin/data/konfigurator/uploadmotiv_' . $_SESSION['sessionId'] . '_1.' . $_SESSION[$SessionPraefix . 'motivupload_ext_1'], DATEIPFAD . 'admin/data/konfigurator/' . $newMotivFileName);
        unlink(DATEIPFAD . 'images/konfigurator/uploadmotiv_' . $_SESSION['sessionId'] . '_1.png');
        $SQLString = "UPDATE " . TABLE_KONFIGURATOR_BESTELLUNG . " SET vorderseite_motivname = '" . $newMotivFileName . "', vorderseite_motivid = '" . MOTIV_ID_UPLOAD . "' WHERE konfigbestellungid = '" . $ShirtBestellID . "'";
        mysql_query($SQLString);
    }

    if ($ShirtTyp2 == 3) {
        $newMotivFileName = 'uploadmotiv_' . $ShirtBestellID . '_hinten.' . $_SESSION[$SessionPraefix . 'motivupload_ext_2'];
        rename(DATEIPFAD . 'admin/data/konfigurator/uploadmotiv_' . $_SESSION['sessionId'] . '_2.' . $_SESSION[$SessionPraefix . 'motivupload_ext_2'], DATEIPFAD . 'admin/data/konfigurator/' . $newMotivFileName);
        unlink(DATEIPFAD . 'images/konfigurator/uploadmotiv_' . $_SESSION['sessionId'] . '_2.png');
        $SQLString = "UPDATE " . TABLE_KONFIGURATOR_BESTELLUNG . " SET rueckseite_motivname = '" . $newMotivFileName . "', rueckseite_motivid = '" . MOTIV_ID_UPLOAD . "' WHERE konfigbestellungid = '" . $ShirtBestellID . "'";
        mysql_query($SQLString);
    }

    // Die Text sichern
    if ($ShirtTyp1 == 1) {

        $SchriftTextArray = $SchriftText1;
        $SchriftArtArray = $SchriftArtID1;
        $SchriftFarbeArray = $SchriftFarbeID1;
        $SchriftGroesseArray = $SchriftGroesseID1;

        $SchriftCounter = 0;

        foreach ($SchriftTextArray as $SchriftText) {

            $sql_shirtbestellungid = $ShirtBestellID;
            $sql_vorderseite = 1;
            $sql_rueckseite = 0;
            $sql_schrifttext = $SchriftText;
            $sql_schriftartid = $SchriftArtArray[$SchriftCounter];
            $sql_schriftartname = $ShirtSchriftartArray[$SchriftArtArray[$SchriftCounter]]["schriftartname"];
            $sql_schriftfarbeid = $SchriftFarbeArray[$SchriftCounter];
            $sql_schriftfarbename = $ShirtSchriftfarbeArray[$SchriftFarbeArray[$SchriftCounter]]["schriftfarbename"];
            $sql_schriftgroesseid = $SchriftGroesseArray[$SchriftCounter];
            $sql_schriftgroessename = $ShirtSchriftgroesseArray[$SchriftGroesseArray[$SchriftCounter]]["schriftgroessename"];
            $sql_sortierung = $SchriftCounter + 1;

            $SQLString = "INSERT INTO " . TABLE_KONFIGURATOR_BESTELLUNGTEXT . " (konfigbestellungid, vorderseite, rueckseite, schrifttext, schriftartid, schriftartname, schriftfarbeid, schriftfarbename, schriftgroesseid, schriftgroessename, sortierung) VALUES ('" . $sql_shirtbestellungid . "', '" . $sql_vorderseite . "', '" . $sql_rueckseite . "', '" . $sql_schrifttext . "', '" . $sql_schriftartid . "', '" . $sql_schriftartname . "', '" . $sql_schriftfarbeid . "', '" . $sql_schriftfarbename . "', '" . $sql_schriftgroesseid . "', '" . $sql_schriftgroessename . "', '" . $sql_sortierung . "')";
            $MySQLQueryReference = mysql_query($SQLString);

            $SchriftCounter++;

        }

    }

    if ($ShirtTyp2 == 1) {

        $SchriftTextArray = $SchriftText2;
        $SchriftArtArray = $SchriftArtID2;
        $SchriftFarbeArray = $SchriftFarbeID2;
        $SchriftGroesseArray = $SchriftGroesseID2;

        $SchriftCounter = 0;

        foreach ($SchriftTextArray as $SchriftText) {

            $sql_shirtbestellungid = $ShirtBestellID;
            $sql_vorderseite = 0;
            $sql_rueckseite = 1;
            $sql_schrifttext = $SchriftText;
            $sql_schriftartid = $SchriftArtArray[$SchriftCounter];
            $sql_schriftartname = $ShirtSchriftartArray[$SchriftArtArray[$SchriftCounter]]["schriftartname"];
            $sql_schriftfarbeid = $SchriftFarbeArray[$SchriftCounter];
            $sql_schriftfarbename = $ShirtSchriftfarbeArray[$SchriftFarbeArray[$SchriftCounter]]["schriftfarbename"];
            $sql_schriftgroesseid = $SchriftGroesseArray[$SchriftCounter];
            $sql_schriftgroessename = $ShirtSchriftgroesseArray[$SchriftGroesseArray[$SchriftCounter]]["schriftgroessename"];
            $sql_sortierung = $SchriftCounter + 1;

            $SQLString = "INSERT INTO " . TABLE_KONFIGURATOR_BESTELLUNGTEXT . " (konfigbestellungid, vorderseite, rueckseite, schrifttext, schriftartid, schriftartname, schriftfarbeid, schriftfarbename, schriftgroesseid, schriftgroessename, sortierung) VALUES ('" . $sql_shirtbestellungid . "', '" . $sql_vorderseite . "', '" . $sql_rueckseite . "', '" . $sql_schrifttext . "', '" . $sql_schriftartid . "', '" . $sql_schriftartname . "', '" . $sql_schriftfarbeid . "', '" . $sql_schriftfarbename . "', '" . $sql_schriftgroesseid . "', '" . $sql_schriftgroessename . "', '" . $sql_sortierung . "')";
            $MySQLQueryReference = mysql_query($SQLString);

            $SchriftCounter++;

        }

    }

    // ********************************************************************************
    // ***** Warenkorb-Tabelle füllen                                             *****
    // ********************************************************************************

    $abfrageMW = "SELECT * FROM " . TABLE_MWST . " where id = '" . $ShirtProduktObject->mwst . "'";
    $ergebnisMW = mysql_query($abfrageMW);
    $rowMW = mysql_fetch_object($ergebnisMW);

    // Daten für den SQL-String vorbereiten
    $sql_artikel_id = '';
    $sql_artikel_name = '';
    $sql_kunden_id = $KundenObject->id;
    $sql_menge = 1;
    $sql_preis = $_SESSION[$SessionPraefix . "gesamtpreis"];
    $sql_preis_netto = '';
    $sql_schema = '';
    $sql_schema1 = '';
    $sql_schema2 = '';
    $sql_schema3 = '';
    $sql_session = $sessionId;
    $sql_timestempel = time();
    $sql_artikel_nr = $ShirtProduktObject->produktartikelnummer;
    $sql_pf_transactionid = '';
    $sql_mwst = $rowMW->mwst;
    $sql_shirtbestellungid = $ShirtBestellID;

    //AddWarenkorb("", 1, "", "", "", "", $_SESSION["mail"], $_SESSION["sessionId"], round((($_SESSION[$SessionPraefix . "gesamtpreis"] / (100 + $rowMW->mwst)) * 100), 2), $_SESSION[$SessionPraefix . "gesamtpreis"], $ShirtBestellID, $rowMW->id, $rowMW->mwst, $MotivObject->lizenzid);
    AddWarenkorb("", 1, "", "", "", "", $_SESSION["mail"], $_SESSION["sessionId"], round((($_SESSION[$SessionPraefix . "gesamtpreis"] / (100 + $rowMW->mwst)) * 100), 2), $_SESSION[$SessionPraefix . "gesamtpreis"], $_SESSION["languageid"], $refertype = 0, $referid = 0, $Artikelname = "", $giveaway = false, "", false, $ShirtBestellID, $rowMW->id, $rowMW->mwst);


}

// ********************************************************************************
// ** UpdateWarenkorbPreis
// ********************************************************************************
function UpdateWarenkorbPreis($Session, $KundenMail) {
	
	// Alle Warenkorbeintr�ge durchgehen
	$SQLString = "SELECT ";
	$SQLString .= TABLE_WARENKORB . ".id, ";
	$SQLString .= TABLE_ARTIKEL . ".id AS artikelid, ";
	$SQLString .= TABLE_ARTIKEL . ".kundengruppenpreis ";
	$SQLString .= "FROM " . TABLE_WARENKORB . " ";
	$SQLString .= "LEFT JOIN " . TABLE_ARTIKEL . " ON " . TABLE_WARENKORB . ".artikel_id = " . TABLE_ARTIKEL . ".id ";
	$SQLString .= "WHERE " . TABLE_WARENKORB . ".session = '" . $Session . "'";
	
	$WarenkorbQueryReference = errorlogged_mysql_query($SQLString);
	
	while ($WarenkorbRow = mysql_fetch_array($WarenkorbQueryReference)) {
	
		// Wenn es ein Kundengruppenpreis ist, diesen neu setzen
		if ($WarenkorbRow["kundengruppenpreis"]) {
			
			$ArtikelObject = GetArtikelDetail($WarenkorbRow["artikelid"], $KundenMail);
			
			$SQLString = "UPDATE " . TABLE_WARENKORB . " SET ";
			$SQLString .= TABLE_WARENKORB . ".preis_netto =  '" . $ArtikelObject->kundengruppenpreis_netto . "', ";
			$SQLString .= TABLE_WARENKORB . ".preis_brutto =  '" . $ArtikelObject->kundengruppenpreis_brutto . "' ";
			$SQLString .= "WHERE " . TABLE_WARENKORB . ".id = '" . $WarenkorbRow["id"] . "'";
			
			$MySQLQueryReference = errorlogged_mysql_query($SQLString);
			
		}		
		
	}

}

// ********************************************************************************
// ** AddWarenkorb
// ********************************************************************************
function AddWarenkorb($ArtikelID, $Menge, $Variante1, $Variante2, $Variante3, $Variante4, $KundenEmail, $SessionID, $PreisNetto = "", $PreisBrutto = "",$LanguageID = 0, $refertype = 0, $referid = 0, $Artikelname = "", $giveaway = false, $ArtikelBeschreibung = '', $PreisstaffelBeruecksichtigen = true, $KonfigArtikelBestellID = 0, $MwStID = "", $MwStSatz = "") {

	if($LanguageID == 0) {
		$LanguageID = GetDefaultLanguageID();
	}	
	
	// Kundendaten abfragen
	$KundenObject = GetKundenDetail($KundenEmail);
	
	// Artikeldaten Abfragen
	$ArtikelObject = GetArtikelDetail($ArtikelID, $KundenEmail,0,$LanguageID);
	
	if($Artikelname)
	{
		$ArtikelObject->artikel_name = $Artikelname;
	}
	
	// Preis festlegen
	if (((string)$PreisNetto == "") || ((string)$PreisBrutto == "")) {
		$PreisNetto = $ArtikelObject->preis_netto;
		$PreisBrutto = $ArtikelObject->preis_brutto;
	}
	
	// Varianten bestimmen
	if ($ArtikelObject->merkmalkombinationparentid) {
		$Variante1 = $ArtikelObject->variante1;
		$Variante2 = $ArtikelObject->variante2;
		$Variante3 = $ArtikelObject->variante3;
		$Variante4 = $ArtikelObject->variante4;
	}
	
	$Variante1Object = GetMerkmalDetail($Variante1,$LanguageID);
	$Variante2Object = GetMerkmalDetail($Variante2,$LanguageID);
	$Variante3Object = GetMerkmalDetail($Variante3,$LanguageID);
	$Variante4Object = GetMerkmalDetail($Variante4,$LanguageID);
	
	if($giveaway) // es kann nur ein Giveaway pro Warenkorb geben, daher wird ein evtl. schon vorhandenes hier gelöscht 
	{
		$SQLString = "DELETE FROM " . TABLE_WARENKORB;
		$SQLString .= " WHERE " . TABLE_WARENKORB . ".session = '" . $SessionID . "'";
		$SQLString .= " AND " . TABLE_WARENKORB . ".is_giveaway = 1"; 
		
		mysql_query($SQLString);
	}
	
	// �berpr�fen, ob der Artikel schon im Warnkorb vorhanden ist
	$SQLString = "SELECT id, menge, preis_brutto, preis_netto FROM " . TABLE_WARENKORB . " WHERE ";
	$SQLString .= TABLE_WARENKORB . ".artikel_id = '" . $ArtikelID . "' AND ";
    $SQLString .= TABLE_WARENKORB . ".artikel_name = '" . $ArtikelObject->artikel_name . "' AND ";
    $SQLString .= TABLE_WARENKORB . ".beschreibung = '" . $ArtikelBeschreibung . "' AND ";
    $SQLString .= TABLE_WARENKORB . ".variante1id = '" . $Variante1 . "' AND ";
	$SQLString .= TABLE_WARENKORB . ".variante2id = '" . $Variante2 . "' AND ";
	$SQLString .= TABLE_WARENKORB . ".variante3id = '" . $Variante3 . "' AND ";
	$SQLString .= TABLE_WARENKORB . ".variante4id = '" . $Variante4 . "' AND ";
	$SQLString .= TABLE_WARENKORB . ".session = '" . $SessionID . "'";
	
	$WarenkorbObject = mysql_fetch_object(errorlogged_mysql_query($SQLString));

    if($ArtikelObject->lager_bestellungen < $Menge + $WarenkorbObject->menge && $ArtikelObject->verkaufstop==1) {
        $Menge = $ArtikelObject->lager_bestellungen - $WarenkorbObject->menge;
    }

    // Preis festlegen
    if($PreisNetto == $ArtikelObject->preis_netto && $PreisBrutto == $ArtikelObject->preis_brutto) { // wurde kein abweichender Preis manuell eingegeben, dann Sonderkonditionen checken
        if($ArtikelObject->kundengruppenpreis_brutto > 0) {
            $PreisBrutto = $ArtikelObject->kundengruppenpreis_brutto;
            $PreisNetto = $ArtikelObject->kundengruppenpreis_netto;
        } else if($ArtikelObject->highlight_id && $ArtikelObject->highlight_preis_brutto > 0 && $ArtikelObject->highlight_enddatum_ts > time()) {
            $PreisBrutto = $ArtikelObject->highlight_preis_brutto;
            $PreisNetto = $ArtikelObject->highlight_preis_netto;
        } else {
            if($PreisstaffelBeruecksichtigen && $ArtikelObject->min_staffel_brutto > 0 && !$ArtikelObject->kundengruppenpreis_brutto){

                $SQLString = 'SELECT menge, preis_netto, preis_brutto ';
                $SQLString .= 'FROM ' . TABLE_ARTIKEL_PREISSTAFFEL . ' ';
                $SQLString .= 'WHERE artikel_id =' . $ArtikelID . ' ';
                $SQLString .= 'AND menge <= ' . ($Menge + $WarenkorbObject->menge). ' ';
                $SQLString .= 'ORDER BY menge DESC ';
                $SQLString .= 'LIMIT 0,1';

                $StaffelObject= mysql_fetch_object(mysql_query($SQLString));
            }

            if($StaffelObject){
                $PreisNetto = $StaffelObject->preis_netto;
                $PreisBrutto = $StaffelObject->preis_brutto;
            }else{
                // Preis festlegen
                $PreisNetto = $ArtikelObject->preis_netto;
                $PreisBrutto = $ArtikelObject->preis_brutto;
            }
        }
    }
	
	// Warenkorbmenge erh�hen
	if (($WarenkorbObject) && ($PreisNetto == $WarenkorbObject->preis_netto) && ($PreisBrutto == $WarenkorbObject->preis_brutto)) {
		
		ChangeWarenkorb($WarenkorbObject->id, ($WarenkorbObject->menge + $Menge), '', $KundenEmail);
		
	} elseif(($WarenkorbObject) && ($StaffelObject)){
			
			DeleteWarenkorb($WarenkorbObject->id, $WarenkorbObject->menge);
			AddWarenkorb($ArtikelID, $WarenkorbObject->menge + $Menge, $Variante1, $Variante2, $Variante3, $Variante4, $KundenEmail, $SessionID, $PreisNetto, $PreisBrutto, $LanguageID, $refertype, $referid, $Artikelname, $giveaway, $ArtikelBeschreibung, $PreisstaffelBeruecksichtigen);
		
	} else {

        // MwSt
        if ($MwStID && $MwStSatz) {
            $WarenkorbMwStSatz = $MwStSatz;
            $WarenkorbMwSt = $MwStID;
        } else {
            $WarenkorbMwStSatz = $ArtikelObject->mwstsatz;
            $WarenkorbMwSt = $ArtikelObject->mwst;
        }

        // Warenkorb eintragen
		$SQLString = "INSERT INTO " . TABLE_WARENKORB . " SET ";
		$SQLString .= TABLE_WARENKORB . ".artikel_id = '" . $ArtikelID . "', ";
		$SQLString .= TABLE_WARENKORB . ".artikel_name = '" . $ArtikelObject->artikel_name . "', ";
        $SQLString .= TABLE_WARENKORB . ".beschreibung = '" . $ArtikelBeschreibung . "', ";
		$SQLString .= TABLE_WARENKORB . ".artikel_nr = '" . $ArtikelObject->artikel_nr . "', ";
		$SQLString .= TABLE_WARENKORB . ".kunden_id = '" . $KundenObject->id . "', ";
		$SQLString .= TABLE_WARENKORB . ".menge = '" . $Menge . "', ";
		$SQLString .= TABLE_WARENKORB . ".preis_brutto = '" . $PreisBrutto . "', ";
		$SQLString .= TABLE_WARENKORB . ".preis_netto = '" . $PreisNetto . "', ";
		$SQLString .= TABLE_WARENKORB . ".variante1 = '" . $Variante1Object->merkmalname . "', ";
		$SQLString .= TABLE_WARENKORB . ".variante1id = '" . $Variante1 . "', ";
		$SQLString .= TABLE_WARENKORB . ".variante2 = '" . $Variante2Object->merkmalname . "', ";
		$SQLString .= TABLE_WARENKORB . ".variante2id = '" . $Variante2 . "', ";
		$SQLString .= TABLE_WARENKORB . ".variante3 = '" . $Variante3Object->merkmalname . "', ";
		$SQLString .= TABLE_WARENKORB . ".variante3id = '" . $Variante3 . "', ";
		$SQLString .= TABLE_WARENKORB . ".variante4 = '" . $Variante4Object->merkmalname . "', ";
		$SQLString .= TABLE_WARENKORB . ".variante4id = '" . $Variante4 . "', ";
		$SQLString .= TABLE_WARENKORB . ".session = '" . $SessionID . "', ";
		$SQLString .= TABLE_WARENKORB . ".timestamp = NOW(), ";
		$SQLString .= TABLE_WARENKORB . ".pf_transactionid = '', ";
        $SQLString .= TABLE_WARENKORB . ".mwst = '" . $WarenkorbMwStSatz . "', ";
        $SQLString .= TABLE_WARENKORB . ".mwstid = '" . $WarenkorbMwSt . "', ";
		$SQLString .= TABLE_WARENKORB . ".voe_datum = '" . $ArtikelObject->voe_datum . "', ";
        $SQLString .= TABLE_WARENKORB . ".konfig_artikel = '" . $KonfigArtikelBestellID . "', ";
        $SQLString .= TABLE_WARENKORB . ".gewicht = '" . $ArtikelObject->gewicht . "', ";
		$SQLString .= TABLE_WARENKORB . ".artikel_download = '" . $ArtikelObject->artikel_download . "', ";
		$SQLString .= TABLE_WARENKORB . ".verweis_typ = '" . $refertype . "'";
		if($referid)
			$SQLString .= ", " . TABLE_WARENKORB .  ".verweis_id = '" . $referid . "'";
		if($giveaway)
			$SQLString .= ", " . TABLE_WARENKORB .  ".is_giveaway = '1'";
		
		$MySQLQueryReference = errorlogged_mysql_query($SQLString);
        $WarenkorbObject = new stdClass();
		$WarenkorbObject->id = mysql_insert_id();

        $BestellObject = GetBestellenDetail(false, $SessionID);

        if($BestellObject) {
            if($BestellObject->set_lager) {
                $Lagerbuchbemerkung = 'Artikel manuell zugefügt / Auftragsnummer ' . $BestellObject->auftragsnummer . ' (Kd Nr: ' . $BestellObject->kunden_id . ') vom ' . $BestellObject->auftragsdatum_format;
                RemoveWarenbestand($ArtikelID, $Menge, $Lagerbuchbemerkung, $BestellObject->id, $WarenkorbObject->id);
            }
            if($BestellObject->set_lager_bestellung) {
                RemoveBestellLagerbestand($ArtikelID, $Menge);
            }
        }
	}
	return $WarenkorbObject->id;
}

function AddWarenkorbFreierEintrag($artikel_name, $artikel_nr, $menge, $preis_brutto, $preis_netto, $session, $mwstsatz, $mwstid) {

    $SQLString = "INSERT INTO " . TABLE_WARENKORB . " SET ";
    $SQLString .= TABLE_WARENKORB . ".artikel_name = '" . $artikel_name . "', ";
    $SQLString .= TABLE_WARENKORB . ".artikel_nr = '" . $artikel_nr . "', ";
    $SQLString .= TABLE_WARENKORB . ".menge = '" . $menge . "', ";
    $SQLString .= TABLE_WARENKORB . ".preis_brutto = '" . $preis_brutto . "', ";
    $SQLString .= TABLE_WARENKORB . ".preis_netto = '" . $preis_netto . "', ";
    $SQLString .= TABLE_WARENKORB . ".session = '" . $session . "', ";
    $SQLString .= TABLE_WARENKORB . ".timestamp = NOW(), ";
    $SQLString .= TABLE_WARENKORB . ".mwst = '" . $mwstsatz . "', ";
    $SQLString .= TABLE_WARENKORB . ".mwstid = '" . $mwstid . "' ";
//    echo $SQLString . "<br>";
    $MySQLQueryReference = mysql_query($SQLString);
}

// ********************************************************************************
// ** DeleteWarenkorb
// ********************************************************************************
function DeleteWarenkorb($WarenkorbID, $lagerbestand="", $UserCheck = false) {
	
	// Warenkorb auslesen
	$SQLString = "SELECT ";
	$SQLString .= TABLE_WARENKORB . ".menge, ";
	$SQLString .= TABLE_WARENKORB . ".session, ";
	$SQLString .= TABLE_WARENKORB . ".artikel_id ";
	$SQLString .= "FROM " . TABLE_WARENKORB . " WHERE ";
	$SQLString .= TABLE_WARENKORB . ".id = '" . $WarenkorbID . "' ";
	$MySQLQueryReference = errorlogged_mysql_query($SQLString);
	$WarenkorbRow = mysql_fetch_object($MySQLQueryReference);
	
	if($lagerbestand == 1) {		
		// Lagerbestand updaten
		StorniereWarenbestand($WarenkorbID); 
//		$SQLString = "UPDATE " . TABLE_ARTIKEL . " SET ";
//		$SQLString .= TABLE_ARTIKEL . ".lager = (" . TABLE_ARTIKEL . ".lager + '" . $WarenkorbRow->menge . "'), "; 
//		$SQLString .= TABLE_ARTIKEL . ".wie_oft_bestellt = (" . TABLE_ARTIKEL . ".wie_oft_bestellt - '" . $WarenkorbRow->menge . "') ";
//		$SQLString .= "WHERE " . TABLE_ARTIKEL . ".id = '" . $WarenkorbRow->artikel_id . "'";
//		$MySQLQueryReference = mysql_query($SQLString);
	}
	
	// Warenkorb eintragen l�schen
	if($UserCheck == true) {
		if($_SESSION["sessionId"] == $WarenkorbRow->session) {
			$SQLString = "DELETE FROM " . TABLE_WARENKORB . " WHERE ";
			$SQLString .= TABLE_WARENKORB . ".id = '" . $WarenkorbID . "'";	
		}
	} else {
		$SQLString = "DELETE FROM " . TABLE_WARENKORB . " WHERE ";
		$SQLString .= TABLE_WARENKORB . ".id = '" . $WarenkorbID . "'";
	}
	
	
	$MySQLQueryReference = errorlogged_mysql_query($SQLString);
	
}

// ********************************************************************************
// ** ChangeWarenkorb
// ********************************************************************************
function ChangeWarenkorb($WarenkorbID, $Menge, $UserCheck = false, $KundenEmail = '', $preis = false, $kundengruppentype = 1) {

	// Warenkorb einlesen
	$SQLString = "SELECT ";
	$SQLString .= TABLE_WARENKORB . ".session, ";
	$SQLString .= TABLE_WARENKORB . ".artikel_id, ";
    $SQLString .= TABLE_WARENKORB . ".menge ";
	$SQLString .= "FROM " . TABLE_WARENKORB . " WHERE ";
	$SQLString .= TABLE_WARENKORB . ".id = '" . $WarenkorbID . "' ";
	$MySQLQueryReference = errorlogged_mysql_query($SQLString);
	$WarenkorbRow = mysql_fetch_object($MySQLQueryReference);
	
	// Kundendaten abfragen
	$KundenObject = GetKundenDetail($KundenEmail);
	// Artikeldaten Abfragen
	$ArtikelObject = GetArtikelDetail($WarenkorbRow->artikel_id, $KundenEmail);

    $BestellObject = GetBestellenDetail('', $WarenkorbRow->session);
    if($BestellObject && $BestellObject->set_lager_bestellung) {
        if($ArtikelObject->lager_bestellungen < ($Menge - $WarenkorbRow->menge) && $ArtikelObject->verkaufstop==1) {
            $Menge = $ArtikelObject->lager_bestellungen + $WarenkorbRow->menge;
        }
    } else {
        if($ArtikelObject->lager_bestellungen < $Menge && $ArtikelObject->verkaufstop==1) {
            $Menge = $ArtikelObject->lager_bestellungen;
        }
    }

    //Preissaffeln auslesen
	if($ArtikelObject->min_staffel_brutto > 0){
		
		$SQLString = 'SELECT menge, preis_netto, preis_brutto ';
		$SQLString .= 'FROM ' . TABLE_ARTIKEL_PREISSTAFFEL . ' ';
		$SQLString .= 'WHERE artikel_id =' . $WarenkorbRow->artikel_id . ' ';
		$SQLString .= 'AND menge <= ' . $Menge . ' ';
		$SQLString .= 'ORDER BY menge DESC ';
		$SQLString .= 'LIMIT 0,1';
		
		$StaffelObject= mysql_fetch_object(errorlogged_mysql_query($SQLString));
		
	}


	// Warenkorb �ndern
	if($UserCheck != true || $_SESSION["sessionId"] == $WarenkorbRow->session) {
        $SQLString = "UPDATE " . TABLE_WARENKORB . " SET ";
        $SQLString .= TABLE_WARENKORB . ".menge = '" . $Menge . "' ";
        if($preis) {
            if($kundengruppentype == 1) {
                $preis_brutto = $preis;
                $preis_netto = $preis * 100 / (100 + $ArtikelObject->mwstsatz);
            } else {
                $preis_brutto = $preis * (100 + $ArtikelObject->mwstsatz) / 100;
                $preis_netto = $preis;
            }
        } else if($ArtikelObject->kundengruppenpreis_brutto > 0) {
            $preis_brutto = $ArtikelObject->kundengruppenpreis_brutto;
            $preis_netto = $ArtikelObject->kundengruppenpreis_netto;
        } else if($ArtikelObject->highlight_id && $ArtikelObject->highlight_preis_brutto > 0 && $ArtikelObject->highlight_enddatum_ts > time()) {
            $preis_brutto = $ArtikelObject->highlight_preis_brutto;
            $preis_netto = $ArtikelObject->highlight_preis_netto;
        } else if($StaffelObject) {
            $preis_netto = $StaffelObject->preis_netto;
            $preis_brutto = $StaffelObject->preis_brutto;
        } else {
            $preis_brutto = $ArtikelObject->preis_brutto;
            $preis_netto = $ArtikelObject->preis_netto;
        }

        $SQLString .= ", " . TABLE_WARENKORB . ".preis_netto = '" . $preis_netto . "', ";
        $SQLString .= TABLE_WARENKORB . ".preis_brutto = '" . $preis_brutto . "' ";
        $SQLString .= "WHERE " . TABLE_WARENKORB . ".id = '" . $WarenkorbID . "'";
        $MySQLQueryReference = errorlogged_mysql_query($SQLString);


        $BestellObject = GetBestellenDetail(false, $WarenkorbRow->session);

        if($BestellObject) {
            if($BestellObject->set_lager) {
                $Lagerbuchbemerkung = 'Artikel manuell zugefügt / Auftragsnummer ' . $BestellObject->auftragsnummer . ' (Kd Nr: ' . $BestellObject->kunden_id . ') vom ' . $BestellObject->auftragsdatum_format;
                RemoveWarenbestand($WarenkorbRow->artikel_id, $Menge - $WarenkorbRow->menge, $Lagerbuchbemerkung, $BestellObject->id, $WarenkorbID);
            }
            if($BestellObject->set_lager_bestellung) {
                RemoveBestellLagerbestand($WarenkorbRow->artikel_id, $Menge - $WarenkorbRow->menge);
            }
        }

    }
}

// ********************************************************************************
// ** GetWarenkorbDataArray
// ********************************************************************************
function GetWarenkorbDataArray($Session, $KundenMail, $FilterDetail = 0, $FilterKundengruppenType = 0, $ZahlungsartID = "", $VersandartID = "", $LanguageID = 0, $BestellObject = 0, $GutscheinCode = '', $stornierte = false) {

	global $lang_admin_brutto_summe, $lang_admin_netto_summe;
	global $lang_admin_brutto_warenwert, $lang_admin_netto_warenwert;
	global $l_enthMwSt,	$l_zuzueglMwSt, $l_keinemwst;

	// Sprache ermitteln
	if (!$LanguageID) {
		$LanguageID = GetDefaultLanguageID();
	}
	

	// W�hrung einlesen
	$WaehrungObject = GetWaehrungDetail();

    if($BestellObject) {
        $KundenObject = GetKundenDetail("", $BestellObject->kunden_id);

        $KundenObject->zahlungsart = $BestellObject->zahlungsart_id;
        $KundenObject->versandart = $BestellObject->versandart_id;

        $KundengruppenObject = GetKundengruppenDetail($KundenObject->kundengruppe);
        $KundengruppenObject->type = $BestellObject->kundengruppentype;

    } else {
        // Kundendaten
        $KundenObject = GetKundenDetail($KundenMail);

        // Kundengruppe ermitteln
        if (!$KundenMail) {
            $KundengruppenID = GetDefaultKundengruppe();
            $KundengruppenObject = GetKundengruppenDetail($KundengruppenID);
        } else {
            $KundengruppenObject = GetKundengruppenDetail("", $KundenMail);

        }
    }

	// ZahlungsartID
	if (!$ZahlungsartID) {
		$ZahlungsartID = $KundenObject->zahlungsart;
	}
	
	// VersandartID
	if (!$VersandartID) {
		$VersandartID = $KundenObject->versandart;
	}

	// Kundengruppentype setzen
	if ($FilterKundengruppenType) {
		$KundengruppenType = $FilterKundengruppenType;
	} else {
		$KundengruppenType = $KundengruppenObject->type;
	}
	
	// Warenkorb einlesen
	$SQLString = "SELECT ";
	$SQLString .= TABLE_WARENKORB . ".id, ";
	$SQLString .= TABLE_WARENKORB . ".artikel_id, ";
	$SQLString .= TABLE_WARENKORB . ".artikel_name, ";
	$SQLString .= TABLE_WARENKORB . ".artikel_nr, ";
	$SQLString .= TABLE_WARENKORB . ".kunden_id, ";
    if($stornierte) {
        $SQLString .= TABLE_WARENKORB . ".menge_storniert as menge, ";
    } else {
	    $SQLString .= TABLE_WARENKORB . ".menge, ";
    }
	$SQLString .= TABLE_WARENKORB . ".preis_brutto, ";
	$SQLString .= TABLE_WARENKORB . ".preis_netto, ";
	$SQLString .= TABLE_WARENKORB . ".variante1, ";
	$SQLString .= TABLE_WARENKORB . ".variante2, ";
	$SQLString .= TABLE_WARENKORB . ".variante3, ";
	$SQLString .= TABLE_WARENKORB . ".variante4, ";
	$SQLString .= TABLE_WARENKORB . ".session, ";
	$SQLString .= TABLE_WARENKORB . ".timestamp, ";
	$SQLString .= TABLE_WARENKORB . ".pf_transactionid, ";
	$SQLString .= TABLE_WARENKORB . ".mwst, ";
	$SQLString .= TABLE_WARENKORB . ".mwstid, ";
	$SQLString .= TABLE_WARENKORB . ".voe_datum, ";
    $SQLString .= TABLE_WARENKORB . ".konfig_artikel, ";
    $SQLString .= TABLE_WARENKORB . ".gewicht, ";
	$SQLString .= TABLE_WARENKORB . ".artikel_download ";
	$SQLString .= ", " . TABLE_WARENKORB . ".verweis_typ";
	$SQLString .= ", " . TABLE_WARENKORB . ".verweis_id, ";
    $SQLString .= TABLE_WARENKORB . ".beschreibung ";
    if($stornierte) {
        $SQLString .= ', ' . TABLE_WARENKORB . '.stornierungsgrund ';
    }
    $SQLString .= ', ' . TABLE_WARENKORB . '.konfig_artikel ';
    $SQLString .= ', ' . TABLE_WARENKORB . '.lieferstatus ';
	$SQLString .= "FROM " . TABLE_WARENKORB . " WHERE ";
	$SQLString .= TABLE_WARENKORB . ".session = '" . $Session . "' ";
    if($stornierte) {
        $SQLString .= ' AND ' . TABLE_WARENKORB . '.menge_storniert > 0 ';
    } else {
        $SQLString .= ' AND ' . TABLE_WARENKORB . '.menge > 0 ';
    }
	$SQLString .= "ORDER BY " . TABLE_WARENKORB . ".timestamp";
	
	$MySQLQueryReference = errorlogged_mysql_query($SQLString);


	$WarenkorbCounter = 0;
	$WarenkorbDataArray = Array();
    $WarenkorbDataArray['warenkorbarray'] = array();
	$GesamtSummenNettoArray = Array();
	$GesamtSummenBruttoArray = Array();
	
	while ($WarenkorbRow = mysql_fetch_array($MySQLQueryReference)) {
		
		$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter] = $WarenkorbRow;

        // Indishirt
        if ($WarenkorbRow["konfig_artikel"]) {
            $SQLString = "SELECT * FROM " . TABLE_KONFIGURATOR_BESTELLUNG . " WHERE konfigbestellungid = '" . $WarenkorbRow["konfig_artikel"] . "'";
            $ShirtBestellungObject = mysql_fetch_object(mysql_query($SQLString));

            $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_nr"] = $ShirtBestellungObject->produktartikelnummer;
            $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] = $ShirtBestellungObject->produktname . " (" . $ShirtBestellungObject->farbname . "/" . $ShirtBestellungObject->groessename . ")<br>";

            // Vorderseite mit Text
            if ($ShirtBestellungObject->vorderseite_type == 1) {

                $SQLString = "SELECT * FROM " . TABLE_KONFIGURATOR_BESTELLUNGTEXT . " WHERE konfigbestellungid  = '" . $WarenkorbRow["konfig_artikel"] . "' AND vorderseite = 1";
                $ShirtTextQueryReference = mysql_query($SQLString);

                if (mysql_affected_rows()) {

                    $ShirtText = "";

                    while ($ShirtTextRow = mysql_fetch_array($ShirtTextQueryReference)) {

                        if ($ShirtTextRow["schrifttext"]) {
                            $ShirtText .= "\"" . $ShirtTextRow["schrifttext"] . "\" (" . $ShirtTextRow["schriftartname"] . "/" . $ShirtTextRow["schriftfarbename"] . "/" . $ShirtTextRow["schriftgroessename"] . ")<br>";
                        }

                    }

                    if ($ShirtText) {
                        $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= "Vorderseite: (Text/" . $ShirtBestellungObject->vorderseite_druckbereichname . "):<br>";
                        $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= $ShirtText;
                    }

                }

            }

            // Vorderseite mit Motiv
            if (($ShirtBestellungObject->vorderseite_type == 2) && ($ShirtBestellungObject->vorderseite_motivid)) {

                $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= "Vorderseite: (Motiv):<br>";
                $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= "\"" . $ShirtBestellungObject->vorderseite_motivname . "\" (" . $ShirtBestellungObject->vorderseite_motivnummer . ")<br>";

            }

            if ($ShirtBestellungObject->vorderseite_type == 3) {
                $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= "Vorderseite: eigenes Motiv<br>";
            }

            // Rückseite mit Text
            if ($ShirtBestellungObject->rueckseite_type == 1) {

                $SQLString = "SELECT * FROM " . TABLE_KONFIGURATOR_BESTELLUNGTEXT . " WHERE konfigbestellungid  = '" . $WarenkorbRow["konfig_artikel"] . "' AND rueckseite = 1";
                $ShirtTextQueryReference = mysql_query($SQLString);

                if (mysql_affected_rows()) {

                    $ShirtText = "";

                    while ($ShirtTextRow = mysql_fetch_array($ShirtTextQueryReference)) {

                        if ($ShirtTextRow["schrifttext"]) {
                            $ShirtText .= "\"" . $ShirtTextRow["schrifttext"] . "\" (" . $ShirtTextRow["schriftartname"] . "/" . $ShirtTextRow["schriftfarbename"] . "/" . $ShirtTextRow["schriftgroessename"] . ")<br>";
                        }

                    }

                    if ($ShirtText) {
                        $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= "Rückseite: (Text/" . $ShirtBestellungObject->rueckseite_druckbereichname . "):<br>";
                        $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= $ShirtText;
                    }

                }

            }

            // Vorderseite mit Motiv
            if (($ShirtBestellungObject->rueckseite_type == 2) && ($ShirtBestellungObject->rueckseite_motivid)) {

                $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= "Rückseite: (Motiv):<br>";
                $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= "\"" . $ShirtBestellungObject->rueckseite_motivname . "\" (" . $ShirtBestellungObject->rueckseite_motivnummer . ")<br>";

            }

            if ($ShirtBestellungObject->rueckseite_type == 3) {
                $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["artikel_name"] .= "Rückseite: eigenes Motiv<br>";
            }

        }
		
		// Check, ob Bestellstop ausgeloest werden soll
		if(!isset($KundenEmail)) { 
			$ArtikelObject = GetArtikelDetail($WarenkorbRow["artikel_id"], "", "", $LanguageID);
		} else {
			$ArtikelObject = GetArtikelDetail($WarenkorbRow["artikel_id"], $KundenEmail, "", $LanguageID);
		}

        $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["imagestring_format"] = $ArtikelObject->image_klein_format;
        $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["lager"] = $ArtikelObject->lager;
		$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["verkaufstop"] = $ArtikelObject->verkaufstop;
		$WarenkorbDataArray['warenkorbarray'][$WarenkorbCounter]['merkmalkombinationparentid'] = $ArtikelObject->merkmalkombinationparentid;
		
		// formatiertes Veröffentlichungsdatum
		if ($WarenkorbRow["voe_datum"] != 0) {
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["voe_datum"] = $WarenkorbRow["voe_datum"];
			if (strtotime($WarenkorbRow["voe_datum"]) > time()) {
				$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["voe_datum_format"] = date("d.m.Y", strtotime($WarenkorbRow["voe_datum"]));
			}
		}
		
		// MwSt
		if (($KundengruppenType == 1) || ($KundengruppenType == 2)) {
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["mwst_format"] = number_format($WarenkorbRow["mwst"], 2, ",", ".");
		} else {
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["mwst_format"] = "-";
		}
		
		// Gewicht
		if(!isset($GesamtGewicht)) { $GesamtGewicht = null; }
		$GesamtGewicht += $WarenkorbRow["menge"] * $WarenkorbRow["gewicht"];
		

		// Bruttopreis
		if ($KundengruppenType == 1) {
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["preis"] = $WarenkorbRow["preis_brutto"];
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["preis_format_einfach"] = number_format($WarenkorbRow["preis_brutto"], 2, ",", ".");
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["preis_format"] = number_format($WarenkorbRow["preis_brutto"], 2, ",", ".") . " " . $WaehrungObject->symbol;
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe"] = $WarenkorbRow["preis_brutto"] * $WarenkorbRow["menge"];
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe_format_einfach"] = number_format($WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe"], 2, ",", ".");
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe_format"] = number_format($WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe"], 2, ",", ".") . " " . $WaehrungObject->symbol;
			
			$GesamtSummenBruttoArray[$WarenkorbRow["mwst"]]["mwstsatz"] = $WarenkorbRow["mwst"];
			if(!isset($GesamtSummenBruttoArray[$WarenkorbRow["mwst"]]["summe"])) { $GesamtSummenBruttoArray[$WarenkorbRow["mwst"]]["summe"] = null; }
			$GesamtSummenBruttoArray[$WarenkorbRow["mwst"]]["summe"] += $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe"];
			
		// Nettopreis
		} else {
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["preis"] = $WarenkorbRow["preis_netto"];
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["preis_format_einfach"] = number_format($WarenkorbRow["preis_netto"], 2, ",", ".");
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["preis_format"] = number_format($WarenkorbRow["preis_netto"], 2, ",", ".") . " " . $WaehrungObject->symbol;
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe"] = $WarenkorbRow["preis_netto"] * $WarenkorbRow["menge"];
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe_format_einfach"] = number_format($WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe"], 2, ",", ".");
			$WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe_format"] = number_format($WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe"], 2, ",", ".") . " " . $WaehrungObject->symbol;

			$GesamtSummenNettoArray[$WarenkorbRow["mwst"]]["mwstsatz"] = $WarenkorbRow["mwst"];
			$GesamtSummenNettoArray[$WarenkorbRow["mwst"]]["summe"] += $WarenkorbDataArray["warenkorbarray"][$WarenkorbCounter]["summe"];

		}
		
		// Downloadartikel
		if ($WarenkorbRow["artikel_download"]) {
			$WarenkorbDataArray["artikel_download"] = true;
		}
		
		$WarenkorbCounter++;
		
	}	
	
	if ($WarenkorbDataArray) {

		// Nettosummen bilden
		if ($KundengruppenType == 1) {
			
			$MwStCounter = 0;
			
			foreach ($GesamtSummenBruttoArray as $GesamtSummenBruttoKey => $GesamtSummenBrutto) {
				$GesamtSummenNettoArray[$GesamtSummenBruttoKey]["mwstsatz"] = $GesamtSummenBrutto["mwstsatz"];
				$GesamtSummenNettoArray[$GesamtSummenBruttoKey]["summe"] = ($GesamtSummenBrutto["summe"] / (100 + $GesamtSummenBrutto["mwstsatz"])) * 100;
				if(!isset($GesamtSummenNetto["mwstsatz"])) { $GesamtSummenNetto["mwstsatz"] = null; }
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["mwstsatz"] = $GesamtSummenNetto["mwstsatz"];
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["mwstsatz_format"] = number_format($GesamtSummenNetto["mwstsatz"], 2, ",", ".");
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"] = $GesamtSummenBruttoArray[$GesamtSummenBruttoKey]["summe"] - $GesamtSummenNettoArray[$GesamtSummenBruttoKey]["summe"];
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe_format_einfach"] = number_format($WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"], 2, ",", ".");
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe_format"] = number_format($WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"], 2, ",", ".") . " " . $WaehrungObject->symbol;
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["text"] = $l_enthMwSt;
				$MwStCounter++;
			}
			
	
		// Bruttosummen bilden
		} else {
			
			$MwStCounter = 0;

			foreach ($GesamtSummenNettoArray as $GesamtSummenNettoKey => $GesamtSummenNetto) {
				$GesamtSummenBruttoArray[$GesamtSummenNettoKey]["mwstsatz"] = $GesamtSummenNetto["mwstsatz"];
				$GesamtSummenBruttoArray[$GesamtSummenNettoKey]["summe"] = ($GesamtSummenNetto["summe"] / 100) * (100 + $GesamtSummenNetto["mwstsatz"]);
				
				if ($KundengruppenType == 2) {
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["mwstsatz"] = $GesamtSummenNetto["mwstsatz"];
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["mwstsatz_format"] = number_format($GesamtSummenNetto["mwstsatz"], 2, ",", ".");
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"] = $GesamtSummenBruttoArray[$GesamtSummenNettoKey]["summe"] - $GesamtSummenNettoArray[$GesamtSummenNettoKey]["summe"];
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe_format_einfach"] = number_format($WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"], 2, ",", ".");
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe_format"] = number_format($WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"], 2, ",", ".") . " " . $WaehrungObject->symbol;
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["text"] = $l_zuzueglMwSt;
					$MwStCounter++;
				}
				
			}
			
	
		}
		
		// Gesamtsummen bilden
		foreach ($GesamtSummenBruttoArray as $GesamtSummenBrutto) {
			if(!isset($WarenkorbDataArray["gesamtsumme_brutto"])) { $WarenkorbDataArray["gesamtsumme_brutto"] = null; }
			$WarenkorbDataArray["gesamtsumme_brutto"] += $GesamtSummenBrutto["summe"];
		}
		$WarenkorbDataArray["gesamtsumme_brutto_format"] = number_format($WarenkorbDataArray["gesamtsumme_brutto"], 2, ",", ".") . " " . $WaehrungObject->symbol;
	
		foreach ($GesamtSummenNettoArray as $GesamtSummenNetto) {
			if(!isset($WarenkorbDataArray["gesamtsumme_netto"])) { $WarenkorbDataArray["gesamtsumme_netto"] = null; }
			$WarenkorbDataArray["gesamtsumme_netto"] += $GesamtSummenNetto["summe"];
		}
		$WarenkorbDataArray["gesamtsumme_netto_format"] = number_format($WarenkorbDataArray["gesamtsumme_netto"], 2, ",", ".") . " " . $WaehrungObject->symbol;
		
		if ($KundengruppenType == 1) {
			$WarenkorbDataArray["gesamtsumme"] = $WarenkorbDataArray["gesamtsumme_brutto"];
			$WarenkorbDataArray["gesamtsumme_format"] = $WarenkorbDataArray["gesamtsumme_brutto_format"];
			$WarenkorbDataArray["gesamtsumme_text"] = $lang_admin_brutto_summe;
			$WarenkorbDataArray["gesamtsumme_alternativ"] = $WarenkorbDataArray["gesamtsumme_netto"];
			$WarenkorbDataArray["gesamtsumme_alternativ_format"] = $WarenkorbDataArray["gesamtsumme_netto_format"];
			$WarenkorbDataArray["gesamtsumme_alternativ_text"] = $lang_admin_netto_summe;
		} elseif ($KundengruppenType == 2) {
			$WarenkorbDataArray["gesamtsumme"] = $WarenkorbDataArray["gesamtsumme_netto"];
			$WarenkorbDataArray["gesamtsumme_format"] = $WarenkorbDataArray["gesamtsumme_netto_format"];
			$WarenkorbDataArray["gesamtsumme_text"] = $lang_admin_netto_summe;
			$WarenkorbDataArray["gesamtsumme_alternativ"] = $WarenkorbDataArray["gesamtsumme_brutto"];
			$WarenkorbDataArray["gesamtsumme_alternativ_format"] = $WarenkorbDataArray["gesamtsumme_brutto_format"];
			$WarenkorbDataArray["gesamtsumme_alternativ_text"] = $lang_admin_brutto_summe;
		} else {
			$WarenkorbDataArray["gesamtsumme"] = $WarenkorbDataArray["gesamtsumme_netto"];
			$WarenkorbDataArray["gesamtsumme_format"] = $WarenkorbDataArray["gesamtsumme_netto_format"];
			$WarenkorbDataArray["gesamtsumme_text"] = $lang_admin_netto_summe;
			$WarenkorbDataArray["gesamtsumme_alternativ"] = "";
			$WarenkorbDataArray["gesamtsumme_alternativ_format"] = "";
			$WarenkorbDataArray["gesamtsumme_alternativ_text"] = "";
			$WarenkorbDataArray["keinemwst_text"] = $l_keinemwst;
		}
		
	}
	
	// ********************************************************************************
	// ** Detailierte Ausgabe des Warenkorbs mit Versandkosten/Zahlungsart und Rabatt
	// ********************************************************************************

	if ($FilterDetail) {

		// Shopeinstellungen einlesen
		$ShopeinstellungenObject = GetShopeinstellungDetail();

		// Warenwert sichern
		$WarenkorbDataArray["warenwert_brutto"] = $WarenkorbDataArray["gesamtsumme_brutto"];
		$WarenkorbDataArray["warenwert_brutto_format"] = $WarenkorbDataArray["gesamtsumme_brutto_format"];
		$WarenkorbDataArray["warenwert_netto"] = $WarenkorbDataArray["gesamtsumme_netto"];
		$WarenkorbDataArray["warenwert_netto_format"] = $WarenkorbDataArray["gesamtsumme_netto_format"];

		if ($KundengruppenType == 1) {
			$WarenkorbDataArray["warenwert"] = $WarenkorbDataArray["gesamtsumme_brutto"];
			$WarenkorbDataArray["warenwert_format"] = $WarenkorbDataArray["gesamtsumme_brutto_format"];
			$WarenkorbDataArray["warenwert_text"] = $lang_admin_brutto_warenwert;
		} else {
			$WarenkorbDataArray["warenwert"] = $WarenkorbDataArray["gesamtsumme_netto"];
			$WarenkorbDataArray["warenwert_format"] = $WarenkorbDataArray["gesamtsumme_netto_format"];
			$WarenkorbDataArray["warenwert_text"] = $lang_admin_netto_warenwert;
		}

		
		// Zahlungsart
        $ZahlungsartObject = GetZahlungsartDetail($ZahlungsartID, $KundenMail, $WarenkorbDataArray["gesamtsumme"], $LanguageID);

        if (!$ZahlungsartObject->name) {
            $WarenkorbDataArray["zahlungsart_name"] = $ZahlungsartObject->standard_name;
        } else {
            $WarenkorbDataArray["zahlungsart_name"] = $ZahlungsartObject->name;
        }

        $WarenkorbDataArray["zahlungsart_beschreibung"] = $ZahlungsartObject->beschreibung;
        $WarenkorbDataArray["zahlungsart_id"] = $ZahlungsartObject->id;
        if($BestellObject->stornierdatum != 0 && $stornierte || $BestellObject->stornierdatum == 0 && !$stornierte) {

            if($BestellObject && !$ZahlungsartObject) {
                $WarenkorbDataArray["zahlungsart_preis_netto"] = $BestellObject->zahlungsart_netto;
                $WarenkorbDataArray["zahlungsart_preis_brutto"] = $BestellObject->zahlungsart_brutto;
                if ($KundengruppenObject->type == 1) {
                    $WarenkorbDataArray["zahlungsart_preis"] = $BestellObject->zahlungsart_brutto;
                } else {
                    $WarenkorbDataArray["zahlungsart_preis"] = $BestellObject->zahlungsart_netto;
                }
                $WarenkorbDataArray["zahlungsart_preis_format"] = number_format($WarenkorbDataArray["zahlungsart_preis"], 2, ', ', '.') . " " . $WaehrungObject->symbol;;
            } elseif ($ZahlungsartObject->preis) {
                $WarenkorbDataArray["zahlungsart_preis_netto"] = $ZahlungsartObject->preis_netto;
                $WarenkorbDataArray["zahlungsart_preis_brutto"] = $ZahlungsartObject->preis_brutto;
                $WarenkorbDataArray["zahlungsart_preis"] = $ZahlungsartObject->preis;
                $WarenkorbDataArray["zahlungsart_preis_format"] = $ZahlungsartObject->preis_format;
            } else {
                $WarenkorbDataArray["zahlungsart_preis_netto"] = 0;
                $WarenkorbDataArray["zahlungsart_preis_brutto"] = 0;
                $WarenkorbDataArray["zahlungsart_preis"] = $ZahlungsartObject->preis;
                $WarenkorbDataArray["zahlungsart_preis_format"] = $ZahlungsartObject->preis_format;
            }
        }

		// Versandart

        $VersandartObject = GetVersandart($VersandartID, $GesamtGewicht, $KundenMail, $LanguageID,$WarenkorbDataArray["gesamtsumme"]);

        $WarenkorbDataArray["gesamtgewicht"] = $GesamtGewicht;

        if (!$VersandartObject->name) {
            $WarenkorbDataArray["versandart_name"] = $VersandartObject->standard_name;
        } else {
            $WarenkorbDataArray["versandart_name"] = $VersandartObject->name;
        }

        $WarenkorbDataArray["versandart_id"] = $VersandartObject->versandartid;
        $WarenkorbDataArray["versandart_gewicht"] = $GesamtGewicht;

        if($BestellObject->stornierdatum != 0 && $stornierte || $BestellObject->stornierdatum == 0 && !$stornierte) {
            if($BestellObject && !$VersandartObject) {
                $WarenkorbDataArray["versandart_preis_netto"] = $BestellObject->versandart_netto;
                $WarenkorbDataArray["versandart_preis_brutto"] = $BestellObject->versandart_brutto;
            } else {
                $WarenkorbDataArray["versandart_preis_netto"] = $VersandartObject->preis_netto;
                $WarenkorbDataArray["versandart_preis_brutto"] = $VersandartObject->preis_brutto;
            }

            if ($KundengruppenType == 1) {
                $WarenkorbDataArray["versandart_preis"] = $VersandartObject->preis_brutto;
                $WarenkorbDataArray["versandart_preis_format"] = number_format($WarenkorbDataArray["versandart_preis"], 2, ",", ".") . " " . $WaehrungObject->symbol;
            } else {
                $WarenkorbDataArray["versandart_preis"] = $VersandartObject->preis_netto;
                $WarenkorbDataArray["versandart_preis_format"] = number_format($WarenkorbDataArray["versandart_preis"], 2, ",", ".") . " " . $WaehrungObject->symbol;
            }
        }

		// Rabatt
		if ($BestellObject && $BestellObject->rechnungsdatum != 0) {

            if($BestellObject->rabatt_name) {



				$WarenkorbDataArray["rabatt_name"] = $BestellObject->rabatt_name;
				$WarenkorbDataArray["rabatt_prozent"] = $BestellObject->rabatt_prozent;

				// Bruttosumme neu bilden
				if ($KundengruppenObject->type == 1) {

					foreach ($GesamtSummenBruttoArray as $GesamtSummenBruttoKey => $GesamtSummenBrutto) {
						$SummeNachRabatt = round(($GesamtSummenBrutto["summe"] / 100) * (100 - $WarenkorbDataArray["rabatt_prozent"]), 2);
						$RabattSumme += $GesamtSummenBruttoArray[$GesamtSummenBruttoKey]["summe"] - $SummeNachRabatt;
						$GesamtSummenBruttoArray[$GesamtSummenBruttoKey]["summe"] = $SummeNachRabatt;
					}


				// Nettosumme neu bilden
				} else {

					foreach ($GesamtSummenNettoArray as $GesamtSummenNettoKey => $GesamtSummenNetto) {
						$SummeNachRabatt = round(($GesamtSummenNetto["summe"] / 100) * (100 - $WarenkorbDataArray["rabatt_prozent"]), 2);
						$RabattSumme += $GesamtSummenNettoArray[$GesamtSummenNettoKey]["summe"] - $SummeNachRabatt;
						$GesamtSummenNettoArray[$GesamtSummenNettoKey]["summe"] = $SummeNachRabatt;
					}

				}

				$WarenkorbDataArray["rabatt_betrag"] = $RabattSumme;
				$WarenkorbDataArray["rabatt_betrag_format"] = number_format($WarenkorbDataArray["rabatt_betrag"], 2, ",", ".") . " " . $WaehrungObject->symbol;;
            }
		} else {

			$RabattArray = GetRabatt($KundenObject->id, $KundenObject->kundengruppe, $WarenkorbDataArray["warenwert"], $LanguageID, $ZahlungsartID);
			
			if ($RabattArray) {
	
				$WarenkorbDataArray["rabatt_name"] = $RabattArray["rabattstaffel_name"];
				$WarenkorbDataArray["rabatt_prozent"] = $RabattArray["rabatt_prozent"];
				
				// Bruttosumme neu bilden
				if ($KundengruppenObject->type == 1) {
					
					foreach ($GesamtSummenBruttoArray as $GesamtSummenBruttoKey => $GesamtSummenBrutto) {
						$SummeNachRabatt = round(($GesamtSummenBrutto["summe"] / 100) * (100 - $WarenkorbDataArray["rabatt_prozent"]), 2);
						$RabattSumme += $GesamtSummenBruttoArray[$GesamtSummenBruttoKey]["summe"] - $SummeNachRabatt;
						$GesamtSummenBruttoArray[$GesamtSummenBruttoKey]["summe"] = $SummeNachRabatt;
					}
	
			
				// Nettosumme neu bilden
				} else {
					
					foreach ($GesamtSummenNettoArray as $GesamtSummenNettoKey => $GesamtSummenNetto) {
						$SummeNachRabatt = round(($GesamtSummenNetto["summe"] / 100) * (100 - $WarenkorbDataArray["rabatt_prozent"]), 2);
						$RabattSumme += $GesamtSummenNettoArray[$GesamtSummenNettoKey]["summe"] - $SummeNachRabatt;
						$GesamtSummenNettoArray[$GesamtSummenNettoKey]["summe"] = $SummeNachRabatt;
					}
			
				}
				
				$WarenkorbDataArray["rabatt_betrag"] = $RabattSumme;
				$WarenkorbDataArray["rabatt_betrag_format"] = number_format($WarenkorbDataArray["rabatt_betrag"], 2, ",", ".") . " " . $WaehrungObject->symbol;;
	
			}
			
		}
		
		// "id" der allgemeinen MwSt suchen
		$MwStDataArray = GetMwStDataArray();
		
		foreach ($MwStDataArray as $MwStData) {
			if ($MwStData["mwst"] == $ShopeinstellungenObject->mwst) {
				$AllgemeinMwStID = 	$MwStData["id"];
				$AllgemeinMwStSatz = $MwStData["mwst"];
			}
		}
		
		if (!$AllgemeinMwStID) {
			$AllgemeinMwStID = 0;
			$AllgemeinMwStSatz = $ShopeinstellungenObject->mwst;
		}
		
		// Gesamtsumme berechnen
		if ($KundengruppenType == 1) {
			$GesamtSummenBruttoArray[$AllgemeinMwStSatz]["mwstsatz"] = $AllgemeinMwStSatz;
			$GesamtSummenBruttoArray[$AllgemeinMwStSatz]["summe"] += $WarenkorbDataArray["zahlungsart_preis_brutto"] + $WarenkorbDataArray["versandart_preis_brutto"];
		} else {
			$GesamtSummenNettoArray[$AllgemeinMwStSatz]["mwstsatz"] = $AllgemeinMwStSatz;
			$GesamtSummenNettoArray[$AllgemeinMwStSatz]["summe"] += $WarenkorbDataArray["zahlungsart_preis_netto"] + $WarenkorbDataArray["versandart_preis_netto"];
		}

		// Mindestbestellwert
		if ($KundengruppenType == 1) {
			
			$WarenkorbDataArray["mindestbestellwert"] = $KundengruppenObject->mindestbestellwert_brutto;
			$WarenkorbDataArray["mindestbestellwert_format"] = number_format($WarenkorbDataArray["mindestbestellwert"], 2, ",", ".") . " " . $WaehrungObject->symbol;
			
			if ($WarenkorbDataArray["warenwert_brutto"] >= $WarenkorbDataArray["mindestbestellwert"]) {
				$WarenkorbDataArray["mindestbestellwert_erfuellt"] = true;
			} else {
				$WarenkorbDataArray["mindestbestellwert_erfuellt"] = false;
			}
			
		} else {
			
			$WarenkorbDataArray["mindestbestellwert"] = $KundengruppenObject->mindestbestellwert_netto;
			$WarenkorbDataArray["mindestbestellwert_format"] = number_format($WarenkorbDataArray["mindestbestellwert"], 2, ",", ".") . " " . $WaehrungObject->symbol;
			
			if ($WarenkorbDataArray["warenwert_netto"] >= $WarenkorbDataArray["mindestbestellwert"]) {
				$WarenkorbDataArray["mindestbestellwert_erfuellt"] = true;
			} else {
				$WarenkorbDataArray["mindestbestellwert_erfuellt"] = false;
			}

		}
		
		// Gutscheincode

		if (GUTSCHEINAKTIONEN) {

			if ($GutscheinCode && !$BestellObject) {
				
				$MoeglicherGutscheinbetrag = 0;
				
				$GutscheinError = CheckGutschein($GutscheinCode, $_SESSION["sessionId"]);
				
				if (!$GutscheinError) {
					
					$WarenkorbDataArray["gutscheincode"] = $GutscheinCode;
					
					$SQLString = "SELECT ";
					$SQLString .= TABLE_GUTSCHEIN . ".gutscheinaktion_id ";
					$SQLString .= "FROM ";
					$SQLString .= TABLE_GUTSCHEIN . " ";
					$SQLString .= "WHERE ";
					$SQLString .= TABLE_GUTSCHEIN . ".gutscheincode = '" . $GutscheinCode .  "'";
					
					$GutscheinObject = mysql_fetch_object(errorlogged_mysql_query($SQLString));
					
					$GutscheinaktionObject = GetGutscheinaktionDetail($GutscheinObject->gutscheinaktion_id);
					
	//				echo '<pre>';
	//				var_dump($GutscheinaktionObject);
	//				echo '</pre>';

					$ungueltigeElementeFuerGutschein = false;
					foreach ($WarenkorbDataArray["warenkorbarray"] as $WarenkorbElement) {
	
	//					echo '<pre>';
	//					var_dump($WarenkorbElement);
	//					echo '</pre>';
											
						$WarenkorbElementGueltig = false;
						
						// Kategorie
						if ($GutscheinaktionObject->gueltigkeitsbereich == 1) {
							
							// Kategorien aus dem Warenkorb
							$SQLString = "SELECT ";		
							$SQLString .= TABLE_KATEGORIERELATION . ".kategorieid ";		
							$SQLString .= "FROM ";		
							$SQLString .= TABLE_KATEGORIERELATION . " ";		
							$SQLString .= "WHERE ";		
							$SQLString .= TABLE_KATEGORIERELATION . ".artikelid = '" . $WarenkorbElement["artikel_id"] . "' ";		
							
							$MySQLQueryReference = errorlogged_mysql_query($SQLString);
	
							unset($WarenkorbElementKategorieIDArray);
							$WarenkorbElementKategorieIDArray = array();
							
							while ($WarenkorbElementKategorieRow = mysql_fetch_array($MySQLQueryReference)) {
								
								$WarenkorbElementKategorieIDArray[] = $WarenkorbElementKategorieRow["kategorieid"]; 
								
							}
							
							// Kategorien der Gutscheinaktion
							$SQLString = "SELECT ";		
							$SQLString .= TABLE_GUTSCHEINAKTION_RELATION . ".relation_id ";		
							$SQLString .= "FROM ";		
							$SQLString .= TABLE_GUTSCHEINAKTION_RELATION . " ";		
							$SQLString .= "WHERE ";		
							$SQLString .= TABLE_GUTSCHEINAKTION_RELATION . ".gutscheinaktion_id = '" . $GutscheinObject->gutscheinaktion_id . "' ";		
							
							$MySQLQueryReference = errorlogged_mysql_query($SQLString);
							
							$GutscheinaktionKategorieFound = false;
							
							while ($GutscheinaktionKategorieRow = mysql_fetch_array($MySQLQueryReference)) {
								
								if (in_array($GutscheinaktionKategorieRow["relation_id"], $WarenkorbElementKategorieIDArray)) {
									$WarenkorbElementGueltig = true;
								}
								
							}
			
						// Artikel
						} elseif ($GutscheinaktionObject->gueltigkeitsbereich == 2) {
							
							$SQLString = "SELECT ";		
							$SQLString .= TABLE_GUTSCHEINAKTION_RELATION . ".relation_id ";		
							$SQLString .= "FROM ";		
							$SQLString .= TABLE_GUTSCHEINAKTION_RELATION . " ";		
							$SQLString .= "WHERE ";		
							$SQLString .= TABLE_GUTSCHEINAKTION_RELATION . ".gutscheinaktion_id = '" . $GutscheinaktionObject->gutscheinaktion_id . "' ";		
							
							$MySQLQueryReference = errorlogged_mysql_query($SQLString);
							
							while ($GutscheinaktionArtikelRow = mysql_fetch_array($MySQLQueryReference)) {
								
								if ($GutscheinaktionArtikelRow["relation_id"] == $WarenkorbElement["artikel_id"] || $GutscheinaktionArtikelRow["relation_id"] == $WarenkorbElement["merkmalkombinationparentid"]) {
									$WarenkorbElementGueltig = true;
								}
								
							}
							
						} else {
							
							$WarenkorbElementGueltig = true;
							
						}
						
						if ($WarenkorbElementGueltig) {
							
							// Obergrenze des Gutscheins
							$SQLString = "SELECT ";
//							$SQLString .= TABLE_ARTIKEL . ".evk_netto, ";
//							$SQLString .= TABLE_ARTIKEL . ".evk_brutto, ";
							$SQLString .= TABLE_MWST . ".mwst AS mwstsatz ";
							$SQLString .= "FROM ";
							$SQLString .= TABLE_ARTIKEL . " ";
							$SQLString .= "LEFT JOIN " . TABLE_MWST . " ON  " . TABLE_ARTIKEL . ".mwst = " . TABLE_MWST . ".id ";
							$SQLString .= "WHERE ";
							$SQLString .= TABLE_ARTIKEL . ".id = '" . $WarenkorbElement["artikel_id"] . "' ";
							
							$ArtikelWarenkorbObject = mysql_fetch_object(errorlogged_mysql_query($SQLString));
//							
//							if ($GutscheinaktionObject->min_vk && $ArtikelWarenkorbObject->evk_netto) {
//								
//								if ($KundengruppenType == 1) {
//									$ArtikelObergrenzeBrutto = round(($ArtikelWarenkorbObject->evk_brutto / 100) * $GutscheinaktionObject->min_vk, 2);
//									$ArtikelObergrenzeNetto = round(($ArtikelObergrenzeBrutto / (100 + $ArtikelWarenkorbObject->mwstsatz)) * 100, 2);  
//								} else {
//									$ArtikelObergrenzeNetto = round(($ArtikelWarenkorbObject->evk_netto / 100) * $GutscheinaktionObject->min_vk, 2);
//									$ArtikelObergrenzeBrutto = round($ArtikelObergrenzeNetto / 100 * (100 + $ArtikelWarenkorbObject->mwstsatz), 2);
//								}
//								
//							} else {
//	
//								$ArtikelObergrenzeNetto = 0;
//								$ArtikelObergrenzeBrutto = 0;
//	
//							}
							
							$ArtikelObergrenzeNetto = 0;
							$ArtikelObergrenzeBrutto = 0;
							
							if ($KundengruppenType == 1) {
	
								if (($WarenkorbElement["menge"] * $WarenkorbElement["preis_brutto"]) > $ArtikelObergrenzeBrutto && ($ArtikelObergrenzeBrutto > 0)) {
//									$WarenkorbDataArray["gutscheinaktion_verfall_minvk"] = true;
									$MoeglicherGutscheinbetrag = $ArtikelObergrenzeBrutto;
								} else {
									$MoeglicherGutscheinbetrag = $WarenkorbElement["menge"] * $WarenkorbElement["preis_brutto"];
								}
	
							} else {
	
								if (($WarenkorbElement["menge"] * $WarenkorbElement["preis_netto"]) > $ArtikelObergrenzeNetto && ($ArtikelObergrenzeNetto > 0)) {
//									$WarenkorbDataArray["gutscheinaktion_verfall_minvk"] = true;
									$MoeglicherGutscheinbetrag = $ArtikelObergrenzeNetto;
								} else {
									$MoeglicherGutscheinbetrag = $WarenkorbElement["menge"] * $WarenkorbElement["preis_netto"];
								}
								
							}
							
							// Gutscheinwert
							if ($GutscheinaktionObject->gutscheintyp == 2) {
	
								if ($KundengruppenType == 1) {
									$Gutscheinbetrag = round($WarenkorbElement["menge"] * (($WarenkorbElement["preis_brutto"] / 100) * $GutscheinaktionObject->gutschein_prozent), 2);
								} else {
									$Gutscheinbetrag = round($WarenkorbElement["menge"] * (($WarenkorbElement["preis_netto"] / 100) * $GutscheinaktionObject->gutschein_prozent), 2);
								}
								
							} else { 
							
								$Gutscheinbetrag = ($GutscheinaktionObject->gutschein_festbetrag - $WarenkorbDataArray["gutscheinbetrag"]);
	
							}
						
							
							if ($Gutscheinbetrag > $MoeglicherGutscheinbetrag) {
								$Gutscheinbetrag = $MoeglicherGutscheinbetrag;
							}
						
							if ($KundengruppenType == 1) {
								$GesamtSummenBruttoArray[$ArtikelWarenkorbObject->mwstsatz]["summe"] -= $Gutscheinbetrag;
							} else {
								$GesamtSummenNettoArray[$ArtikelWarenkorbObject->mwstsatz]["summe"] -= $Gutscheinbetrag;
							}
						
							$WarenkorbDataArray["gutscheinbetrag"] += $Gutscheinbetrag;
						
						}
						else
                        {
                            $ungueltigeElementeFuerGutschein = true;
                        }
					}
                    
                    if($GutscheinaktionObject->gutscheintyp == 1 && $GutscheinaktionObject->gutschein_festbetrag > $WarenkorbDataArray["gutscheinbetrag"])
                    {
                        if($ungueltigeElementeFuerGutschein)
                            $WarenkorbDataArray["gutscheinaktion_verfall_minvk"] = true;
                        else
                            $WarenkorbDataArray["gutscheinaktion_verfall"] = true;
                    }
				}
				
				$WarenkorbDataArray["gutscheinaktion_name"] = $GutscheinaktionObject->gutscheinaktion_name;
				$WarenkorbDataArray["gutscheinbetrag_format"] = number_format($WarenkorbDataArray["gutscheinbetrag"], 2, ",", ".") . " " . $WaehrungObject->symbol;;
			
			} elseif ($GutscheinCode && $BestellObject) {
				
				$WarenkorbDataArray["gutscheincode"] = $GutscheinCode;
				
				$SQLString = "SELECT ";
				$SQLString .= TABLE_GUTSCHEIN . ".gutscheinaktion_id ";
				$SQLString .= "FROM ";
				$SQLString .= TABLE_GUTSCHEIN . " ";
				$SQLString .= "WHERE ";
				$SQLString .= TABLE_GUTSCHEIN . ".gutscheincode = '" . $GutscheinCode .  "'";
				
				$GutscheinObject = mysql_fetch_object(errorlogged_mysql_query($SQLString));
				
				$GutscheinaktionObject = GetGutscheinaktionDetail($GutscheinObject->gutscheinaktion_id);

                if(!$stornierte || $GutscheinaktionObject->gutschein_prozent) {

                    $ungueltigeElementeFuerGutschein = false;
                    foreach ($WarenkorbDataArray["warenkorbarray"] as $WarenkorbElement) {

                        $WarenkorbElementGueltig = false;

                        // Kategorie
                        if ($GutscheinaktionObject->gueltigkeitsbereich == 1) {

                            // Kategorien aus dem Warenkorb
                            $SQLString = "SELECT ";
                            $SQLString .= TABLE_KATEGORIERELATION . ".kategorieid ";
                            $SQLString .= "FROM ";
                            $SQLString .= TABLE_KATEGORIERELATION . " ";
                            $SQLString .= "WHERE ";
                            $SQLString .= TABLE_KATEGORIERELATION . ".artikelid = '" . $WarenkorbElement["artikel_id"] . "' ";

                            $MySQLQueryReference = errorlogged_mysql_query($SQLString);

                            unset($WarenkorbElementKategorieIDArray);
                            $WarenkorbElementKategorieIDArray = array();

                            while ($WarenkorbElementKategorieRow = mysql_fetch_array($MySQLQueryReference)) {

                                $WarenkorbElementKategorieIDArray[] = $WarenkorbElementKategorieRow["kategorieid"];

                            }

                            // Kategorien der Gutscheinaktion
                            $SQLString = "SELECT ";
                            $SQLString .= TABLE_GUTSCHEINAKTION_RELATION . ".relation_id ";
                            $SQLString .= "FROM ";
                            $SQLString .= TABLE_GUTSCHEINAKTION_RELATION . " ";
                            $SQLString .= "WHERE ";
                            $SQLString .= TABLE_GUTSCHEINAKTION_RELATION . ".gutscheinaktion_id = '" . $GutscheinObject->gutscheinaktion_id . "' ";

                            $MySQLQueryReference = errorlogged_mysql_query($SQLString);

                            $GutscheinaktionKategorieFound = false;

                            while ($GutscheinaktionKategorieRow = mysql_fetch_array($MySQLQueryReference)) {

                                if (in_array($GutscheinaktionKategorieRow["relation_id"], $WarenkorbElementKategorieIDArray)) {
                                    $WarenkorbElementGueltig = true;
                                }

                            }

                        // Artikel
                        } elseif ($GutscheinaktionObject->gueltigkeitsbereich == 2) {

                            $SQLString = "SELECT ";
                            $SQLString .= TABLE_GUTSCHEINAKTION_RELATION . ".relation_id ";
                            $SQLString .= "FROM ";
                            $SQLString .= TABLE_GUTSCHEINAKTION_RELATION . " ";
                            $SQLString .= "WHERE ";
                            $SQLString .= TABLE_GUTSCHEINAKTION_RELATION . ".gutscheinaktion_id = '" . $GutscheinaktionObject->gutscheinaktion_id . "' ";

                            $MySQLQueryReference = errorlogged_mysql_query($SQLString);

                            while ($GutscheinaktionArtikelRow = mysql_fetch_array($MySQLQueryReference)) {

                                if ($GutscheinaktionArtikelRow["relation_id"] == $WarenkorbElement["artikel_id"]) {
                                    $WarenkorbElementGueltig = true;
                                }

                            }

                        } else {

                            $WarenkorbElementGueltig = true;

                        }

                        if ($WarenkorbElementGueltig) {

                            // Obergrenze des Gutscheins
                            $SQLString = "SELECT ";
    //						$SQLString .= TABLE_ARTIKEL . ".evk_netto, ";
    //						$SQLString .= TABLE_ARTIKEL . ".evk_brutto, ";
                            $SQLString .= TABLE_MWST . ".mwst AS mwstsatz ";
                            $SQLString .= "FROM ";
                            $SQLString .= TABLE_ARTIKEL . " ";
                            $SQLString .= "LEFT JOIN " . TABLE_MWST . " ON  " . TABLE_ARTIKEL . ".mwst = " . TABLE_MWST . ".id ";
                            $SQLString .= "WHERE ";
                            $SQLString .= TABLE_ARTIKEL . ".id = '" . $WarenkorbElement["artikel_id"] . "' ";

                            $ArtikelWarenkorbObject = mysql_fetch_object(errorlogged_mysql_query($SQLString));

    //						if ($GutscheinaktionObject->min_vk && $ArtikelWarenkorbObject->evk_netto) {
    //
    //							if ($KundengruppenType == 1) {
    //								$ArtikelObergrenzeBrutto = round(($ArtikelWarenkorbObject->evk_brutto / 100) * $GutscheinaktionObject->min_vk, 2);
    //								$ArtikelObergrenzeNetto = round(($ArtikelObergrenzeBrutto / (100 + $ArtikelWarenkorbObject->mwstsatz)) * 100, 2);
    //							} else {
    //								$ArtikelObergrenzeNetto = round(($ArtikelWarenkorbObject->evk_netto / 100) * $GutscheinaktionObject->min_vk, 2);
    //								$ArtikelObergrenzeBrutto = round($ArtikelObergrenzeNetto / 100 * (100 + $ArtikelWarenkorbObject->mwstsatz), 2);
    //							}
    //
    //						} else {
    //
    //							$ArtikelObergrenzeNetto = 0;
    //							$ArtikelObergrenzeBrutto = 0;
    //
    //						}

                            $ArtikelObergrenzeNetto = 0;
                            $ArtikelObergrenzeBrutto = 0;

                            if ($KundengruppenType == 1) {

                                if (($WarenkorbElement["menge"] * $WarenkorbElement["preis_brutto"]) > $ArtikelObergrenzeBrutto && ($ArtikelObergrenzeBrutto > 0)) {
    //								$WarenkorbDataArray["gutscheinaktion_verfall_minvk"] = true;
                                    $MoeglicherGutscheinbetrag = $ArtikelObergrenzeBrutto;
                                } else {
                                    $MoeglicherGutscheinbetrag = $WarenkorbElement["menge"] * $WarenkorbElement["preis_brutto"];
                                }

                            } else {

                                if (($WarenkorbElement["menge"] * $WarenkorbElement["preis_netto"]) > $ArtikelObergrenzeNetto && ($ArtikelObergrenzeNetto > 0)) {
    //								$WarenkorbDataArray["gutscheinaktion_verfall_minvk"] = true;
                                    $MoeglicherGutscheinbetrag = $ArtikelObergrenzeNetto;
                                } else {
                                    $MoeglicherGutscheinbetrag = $WarenkorbElement["menge"] * $WarenkorbElement["preis_netto"];
                                }

                            }

                            // Gutscheinwert
                            if ($GutscheinaktionObject->gutscheintyp == 2) {

                                if ($KundengruppenType == 1) {
                                    $Gutscheinbetrag = round($WarenkorbElement["menge"] * (($WarenkorbElement["preis_brutto"] / 100) * $GutscheinaktionObject->gutschein_prozent), 2);
                                } else {
                                    $Gutscheinbetrag = round($WarenkorbElement["menge"] * (($WarenkorbElement["preis_netto"] / 100) * $GutscheinaktionObject->gutschein_prozent), 2);
                                }

                            } else {

                                $Gutscheinbetrag = ($GutscheinaktionObject->gutschein_festbetrag - $WarenkorbDataArray["gutscheinbetrag"]);

                            }


                            if ($Gutscheinbetrag > $MoeglicherGutscheinbetrag) {
                                $Gutscheinbetrag = $MoeglicherGutscheinbetrag;

    //							$WarenkorbDataArray["gutscheinaktion_verfall"] = true;
                            }

                            if ($KundengruppenType == 1) {
                                $GesamtSummenBruttoArray[$ArtikelWarenkorbObject->mwstsatz]["summe"] -= $Gutscheinbetrag;
                            } else {
                                $GesamtSummenNettoArray[$ArtikelWarenkorbObject->mwstsatz]["summe"] -= $Gutscheinbetrag;
                            }

                            $WarenkorbDataArray["gutscheinbetrag"] += $Gutscheinbetrag;

                        }
                        else
                        {
                            $ungueltigeElementeFuerGutschein = true;
                        }
                    }

                    if($GutscheinaktionObject->gutscheintyp == 1 && $GutscheinaktionObject->gutschein_festbetrag > $WarenkorbDataArray["gutscheinbetrag"])
                    {
                        if($ungueltigeElementeFuerGutschein)
                            $WarenkorbDataArray["gutscheinaktion_verfall_minvk"] = true;
                        else
                            $WarenkorbDataArray["gutscheinaktion_verfall"] = true;
                    }


                    $WarenkorbDataArray["gutscheinaktion_name"] = $GutscheinaktionObject->gutscheinaktion_name;
                    $WarenkorbDataArray["gutscheinbetrag_format"] = number_format($WarenkorbDataArray["gutscheinbetrag"], 2, ",", ".") . " " . $WaehrungObject->symbol;;
                }
			}
			
		}
	
	}
	
	if ($WarenkorbDataArray) {

		// Nettosummen bilden
		if ($KundengruppenType == 1) {
			
			$MwStCounter = 0;
			
			foreach ($GesamtSummenBruttoArray as $GesamtSummenBruttoKey => $GesamtSummenBrutto) {

				$GesamtSummenNettoArray[$GesamtSummenBruttoKey]["mwstsatz"] = $GesamtSummenBrutto["mwstsatz"];
				$GesamtSummenNettoArray[$GesamtSummenBruttoKey]["summe"] = ($GesamtSummenBrutto["summe"] / (100 + $GesamtSummenBrutto["mwstsatz"])) * 100;

				$WarenkorbDataArray["mwstarray"][$MwStCounter]["mwstsatz"] = $GesamtSummenBrutto["mwstsatz"];
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["mwstsatz_format"] = number_format($GesamtSummenBrutto["mwstsatz"], 2, ",", ".");
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"] = $GesamtSummenBruttoArray[$GesamtSummenBruttoKey]["summe"] - $GesamtSummenNettoArray[$GesamtSummenBruttoKey]["summe"];
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe_format_einfach"] = number_format($WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"], 2, ",", ".");
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe_format"] = number_format($WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"], 2, ",", ".") . " " . $WaehrungObject->symbol;
				$WarenkorbDataArray["mwstarray"][$MwStCounter]["text"] = $l_enthMwSt;

				$MwStCounter++;

			}
			
	
		// Bruttosummen bilden
		} else {
			
			$MwStCounter = 0;

			foreach ($GesamtSummenNettoArray as $GesamtSummenNettoKey => $GesamtSummenNetto) {

				$GesamtSummenBruttoArray[$GesamtSummenNettoKey]["mwstsatz"] = $GesamtSummenNetto["mwstsatz"];
				$GesamtSummenBruttoArray[$GesamtSummenNettoKey]["summe"] = ($GesamtSummenNetto["summe"] / 100) * (100 + $GesamtSummenNetto["mwstsatz"]);
				
				if ($KundengruppenType == 2) {
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["mwstsatz"] = $GesamtSummenNetto["mwstsatz"];
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["mwstsatz_format"] = number_format($GesamtSummenNetto["mwstsatz"], 2, ",", ".");
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"] = $GesamtSummenBruttoArray[$GesamtSummenNettoKey]["summe"] - $GesamtSummenNettoArray[$GesamtSummenNettoKey]["summe"];
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe_format_einfach"] = number_format($WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"], 2, ",", ".");
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["summe_format"] = number_format($WarenkorbDataArray["mwstarray"][$MwStCounter]["summe"], 2, ",", ".") . " " . $WaehrungObject->symbol;
					$WarenkorbDataArray["mwstarray"][$MwStCounter]["text"] = $l_zuzueglMwSt;
					$MwStCounter++;
				}
				
			}
			
	
		}
		
		// Gesamtsummen bilden
		$WarenkorbDataArray["gesamtsumme_brutto"] = 0;
		foreach ($GesamtSummenBruttoArray as $GesamtSummenBrutto) {
			$WarenkorbDataArray["gesamtsumme_brutto"] += $GesamtSummenBrutto["summe"];
		}

		$WarenkorbDataArray["gesamtsumme_brutto"] = round($WarenkorbDataArray["gesamtsumme_brutto"], 2);
		$WarenkorbDataArray["gesamtsumme_brutto_format"] = number_format($WarenkorbDataArray["gesamtsumme_brutto"], 2, ",", ".") . " " . $WaehrungObject->symbol;
	
		$WarenkorbDataArray["gesamtsumme_netto"] = 0;
		foreach ($GesamtSummenNettoArray as $GesamtSummenNetto) {
			$WarenkorbDataArray["gesamtsumme_netto"] += $GesamtSummenNetto["summe"];
		}
		$WarenkorbDataArray["gesamtsumme_netto"] = round($WarenkorbDataArray["gesamtsumme_netto"], 2);
		$WarenkorbDataArray["gesamtsumme_netto_format"] = number_format($WarenkorbDataArray["gesamtsumme_netto"], 2, ",", ".") . " " . $WaehrungObject->symbol;
		
		if ($KundengruppenType == 1) {
			$WarenkorbDataArray["gesamtsumme"] = $WarenkorbDataArray["gesamtsumme_brutto"];
			$WarenkorbDataArray["gesamtsumme_format"] = $WarenkorbDataArray["gesamtsumme_brutto_format"];
			$WarenkorbDataArray["gesamtsumme_text"] = $lang_admin_brutto_summe;
			$WarenkorbDataArray["gesamtsumme_alternativ"] = "";
			$WarenkorbDataArray["gesamtsumme_alternativ_format"] = "";
			$WarenkorbDataArray["gesamtsumme_alternativ_text"] = "";
		} elseif ($KundengruppenType == 2) {
			$WarenkorbDataArray["gesamtsumme"] = $WarenkorbDataArray["gesamtsumme_netto"];
			$WarenkorbDataArray["gesamtsumme_format"] = $WarenkorbDataArray["gesamtsumme_netto_format"];
			$WarenkorbDataArray["gesamtsumme_text"] = $lang_admin_netto_summe;
			$WarenkorbDataArray["gesamtsumme_alternativ"] = $WarenkorbDataArray["gesamtsumme_brutto"];
			$WarenkorbDataArray["gesamtsumme_alternativ_format"] = $WarenkorbDataArray["gesamtsumme_brutto_format"];
			$WarenkorbDataArray["gesamtsumme_alternativ_text"] = $lang_admin_brutto_summe;
		} else {
			$WarenkorbDataArray["gesamtsumme"] = $WarenkorbDataArray["gesamtsumme_netto"];
			$WarenkorbDataArray["gesamtsumme_format"] = $WarenkorbDataArray["gesamtsumme_netto_format"];
			$WarenkorbDataArray["gesamtsumme_text"] = $lang_admin_netto_summe;
			$WarenkorbDataArray["gesamtsumme_alternativ"] = "";
			$WarenkorbDataArray["gesamtsumme_alternativ_format"] = "";
			$WarenkorbDataArray["gesamtsumme_alternativ_text"] = "";
			$WarenkorbDataArray["keinemwst_text"] = $l_keinemwst;

		}
		
	}
	
	return $WarenkorbDataArray;
	
}

function GetWarenkorbGesamtgewicht($SessionID) {

	// SQ-String zusammensetzen um das Gesamtgewicht des Warenkoebs zu ermitteln
	$WarenkorbSQLString = "SELECT " . TABLE_WARENKORB . ".*, " . TABLE_ARTIKEL . ".gewicht FROM " . TABLE_WARENKORB . " LEFT JOIN " . TABLE_ARTIKEL . " ON " . TABLE_WARENKORB . ".artikel_id = " . TABLE_ARTIKEL . ".id  WHERE session = '" . $SessionID . "'";
	$WarenkorbMySQLQueryReference = errorlogged_mysql_query($WarenkorbSQLString);
	
	while($WarenkorbObject = mysql_fetch_object($WarenkorbMySQLQueryReference)) {

		$GesamtGewicht += $WarenkorbObject->gewicht * $WarenkorbObject->menge;
	
	}
	
	return $GesamtGewicht;
	
}

function GetWarenkorbAnzahl($SessionID) {
	
	// die Anzahl der EIntr�ge im Warenkorb ermitteln
	$SQLString = "SELECT COUNT(*) AS WarenkobAnzahl FROM " . TABLE_WARENKORB . " WHERE session = '" . $SessionID . "'";
	$WarenkoebAnzahlObject = mysql_fetch_object(errorlogged_mysql_query($SQLString));
	
	return $WarenkoebAnzahlObject->WarenkobAnzahl;
	
}

function StorniereArtikel($warenkorbid, $menge, $stornierungsgrund, $storniereBestellungBeiNullArtikeln = true) {
    $SQLString = 'SELECT menge, session FROM ' . TABLE_WARENKORB . ' WHERE id=' . $warenkorbid;
    $row = mysql_fetch_row(errorlogged_mysql_query($SQLString));

    $session = $row[1];
    if($menge > $row[0]) {
        $menge = $row[0];
    }

    StorniereWarenbestand($warenkorbid, $menge);

    $SQLString = 'UPDATE ' . TABLE_WARENKORB . ' SET ';
    $SQLString .= 'menge = menge - ' . $menge;
    $SQLString .= ', menge_storniert = menge_storniert + ' . $menge;
    $SQLString .= ', stornierungsgrund = \'' . $stornierungsgrund . '\'';
    $SQLString .= ' WHERE id = ' . $warenkorbid;
    errorlogged_mysql_query($SQLString);

    // Test ob alle Artikel der Bestellung storniert wurden, dann wird auch die Bestellung in den Status storniert versetzt
    if($storniereBestellungBeiNullArtikeln) {
        $SQLString = 'SELECT SUM(menge) FROM ' . TABLE_WARENKORB . ' WHERE session = \'' . $session . '\'';
        $row = mysql_fetch_row(errorlogged_mysql_query($SQLString));
        if($row[0] == 0) {
            $SQLString = "SELECT status FROM " . TABLE_BESTELLEN_STATUS_AKTIONEN . ' WHERE aktion = ' . STATUSAKTION_STORNIEREN;
            $row = mysql_fetch_row(errorlogged_mysql_query($SQLString));
            $StornierBestellstatus = $row[0];
            $SQLString = 'SELECT id FROM ' . TABLE_BESTELLEN . ' WHERE session = \'' . $session . '\'';
            $row = mysql_fetch_row(errorlogged_mysql_query($SQLString));
            $BestellID = $row[0];
            BestellStatusWechseln($BestellID, $StornierBestellstatus);
        }
    }
}

function GetWarenkorbGesamtMenge($sessionId) {
    $SQLString = 'SELECT sum(menge) FROM ' . TABLE_WARENKORB . ' WHERE session = \'' . $sessionId .'\'';
    $row = mysql_fetch_row(mysql_query($SQLString));
    return $row[0];
}

function CopyArtikelLieferstatus($wkSession) {
    $SQLString = 'UPDATE ' . TABLE_WARENKORB . ' INNER JOIN ' . TABLE_ARTIKEL . ' ON ' . TABLE_WARENKORB . '.artikel_id = ' . TABLE_ARTIKEL . '.id SET ';
    $SQLString .= TABLE_WARENKORB . '.lieferstatus = ' . TABLE_ARTIKEL . '.lieferstatus WHERE ' . TABLE_WARENKORB . '.session = \'' . $wkSession . '\'';
    errorlogged_mysql_query($SQLString);
}