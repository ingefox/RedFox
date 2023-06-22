/**
 * IMPORT
 */
import {uraStyles} from './UraCSS.js';
import {uraStyleDefault,btn,date,formatSortDate} from './UraConf.js';

/**
 * FUNCTION RETURN CLASS HTML FOR BALISE CHOOSE
 * @param balise BALISE (select, input ....)
 * @param type is the type of balise ex delete .... default value is default
 * @param style style use
 * @return {string}
 */
function getStyle(balise,style=false,type="default"){
   if(style != false){
      return uraStyles[style][balise][type];
   }else{
      return uraStyles[uraStyleDefault][balise][type];
   }
   
}
/**
 * return the icone button for identifier name
 * @param {string} name identifier
 * @return {string}
 */
function getBtn(name){
   return btn[name];
}
/**
 * return in date format or delimiter
 * @param {string} item array or search
 * @param {string} subitem  format or delimiter
 * @returns 
 */
function getDate(item, subitem){
   return date[item][subitem];
}

/**
 * EXPORT
 */
export {getStyle,getBtn,getDate,formatSortDate};