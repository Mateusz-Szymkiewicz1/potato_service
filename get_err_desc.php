<?php
function get_err_desc($error){
    switch($error){
        case 1045:
             $message = "Użytkownik odrzucony!";
            break;
        case 1049:
            $message = "Nie udało się połączyć z bazą danych!";
            break;
        case 1044:
            $message = "Użytkownik nie posiada uprawnień do bazy!";
            break;
        case 1062:
            $message = "Klucz unikalny nie może się powtarzać!";
            break;
         case 1068:
            $message = "Może istnieć tylko jeden klucz główny!";
            break;
        case 1075:
            $message = "Może istnieć tylko jedna kolumna z inkrementacją (i musi być ona kluczem)!";
            break;
        case 1064:
            $message = "Błąd składniowy!";
            break;
        case 1142:
            $message = "Brak uprawnień!";
            break;
        case 1146:
            $message = "Podana tabela nie istnieje!";
            break;
        case 1452:
            $message = "Wprowadzone klucze obce są niepoprawne!";
            break;
        default:
            $message = "Nieprzewidziany błąd!";
            break;
    }
    return $message;
}
?>
