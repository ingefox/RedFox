/**
 * CONSTANTS
 */
//STYLE USE
const uraStyleDefault = "GS";
 
const btn = [];
btn['minus'] = "<i class='fas fa-trash-alt'></i>";
btn['plus'] = "<i class='fas fa-plus'></i>&nbsp; Ajouter un filtre";
btn['reset'] = "<i class='fas fa-undo-alt'></i>&nbsp; Réinitialiser les filtres";
btn['collapse-down'] = "<i class='fas fa-chevron-down'></i>";
btn['collapse-up'] = "<i class='fas fa-chevron-up'></i>";
const date = 
{
    "search": 
    {
        "format":"yyyy-mm-dd",
        "delimiter":"-"
    },
    "array": 
    {
        "format":"dd/mm/yyyy",
        "delimiter":"/"
    }
};
const formatSortDate = [
    "DD/MM/YYYY"
]
/**
 * EXPORT
 */
export {uraStyleDefault,btn,date,formatSortDate};