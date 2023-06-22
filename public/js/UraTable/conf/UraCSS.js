/**
 * CONSTANTS
 */
//DEFINE DIFFERENT URATABLE STYLE ADD CSS CLASS IN URATABLE ELEMENT
const uraStyles = 
{
   B4:
   {
        select: 
        {
           default: "custom-select custom-select-sm form-control form-control-sm"
        },
        input: 
        {
           default: "form-control form-control-sm",
           number: "form-control form-control-sm",
           date: "form-control form-control-sm"
         },
        button: 
        {
           delete: "btn btn-danger btn-sm",
           reset: "btn btn-warning btn-sm",
           add: "btn btn-success btn-sm",
           search: "btn btn-info btn-sm"
        },
        span:
        {
           default: "form-group",
           sequenceHead:"",
           orItem:"",
           andItem:"",
           orConnector:"",
           andConnector:""
        },
        div:
        {
           default:"row",
           header: "col-md-12",
           body: "col-md-12",
           footer: "form-inline",
           view: "col-md-12",
           submit: "col-md-12"
        },
        label:
        {
           default:""
        }
        
   },
   GS:
   {
        select: 
        {
           default: "custom-select custom-select-sm form-control form-control-sm",
           andor:"custom-select custom-select-sm form-control form-control-sm"
        },
        input: 
        {
           default: "form-control form-control-sm",
           number: "form-control form-control-sm",
           date: "form-control form-control-sm"
         },
        button: 
        {
           delete: "btn btn-accent btn-outline-danger btn-sm ",
           reset: "btn btn-accent btn-outline-danger btn-sm ",
           add: "btn btn-accent btn-outline-success btn-sm ",
           search: "btn btn-accent btn-block btn-outline-success"
        },
        span:
        {
           default: "col-md-12 form-group",
           sequenceHead:"fs-5",
           orItem:"list-group-item",
           andItem:"",
           orConnector:"list-group-item text-center font-bold font-italic text-accent",
           andConnector:"text-center font-bold font-italic text-accent"
        },
        div:
        {
           default:"row",
           header: "col-md-12 fs-5",
           body: "col-md-12",
           footer: "col-md-12 form-inline row",
           view: "col-md-12 list-group-flush",
           submit: "pt-3 pb-1 col-md-3 offset-md-9 text-center"
        },
        label:
        {
           default:""
        }
        
   }
};
/**
 * EXPORT
 */
export {uraStyles};