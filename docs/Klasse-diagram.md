# Klasse-diagram – Wijzigen leverancier

```mermaid
classDiagram
    direction LR

    class LeverancierBeheerController {
        +index(Request) View
        +show(int) View
        +edit(int) View
        +update(UpdateLeverancierRequest, int) RedirectResponse
    }
    class UpdateLeverancierRequest {
        +rules() array
        +attributes() array
        +messages() array
    }
    class FormRequest
    class LeverancierBeheer {
        +int PER_PAGINA
        +pagina(int) LengthAwarePaginator
        +details(int) object
        +wijzig(int, array, bool) void
    }
    class LengthAwarePaginator
    class PDO

    FormRequest <|-- UpdateLeverancierRequest
    LeverancierBeheerController ..> UpdateLeverancierRequest : valideert
    LeverancierBeheerController --> LeverancierBeheer : vraagt gegevens op en slaat op
    LeverancierBeheer ..> LengthAwarePaginator : maakt
    LeverancierBeheer ..> PDO : roept MySQL-procedures aan via Laravel
```

De routes gebruiken de gate `wijzig-leveranciers`. Het beheer accepteert de rol
`manager` en de bestaande beheerdersrol `admin`.

MySQL beperkt het overzicht tot vier records per aanroep. De detailprocedure
koppelt `Leverancier` en `Contact` via `ContactId`. Bij opslaan vergrendelt de
updateprocedure de leverancier, verwerkt zij de wijzigingen in beide tabellen en
wordt bij een SQL-fout de hele transactie teruggedraaid.
