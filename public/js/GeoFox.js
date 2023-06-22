// IMPORT : import '../../app/Modules/GeoFox/Scripts/GeoFox.js'

let markers = [];

/**
 * Map styles
 */
const STYLES = {
    default: [],
    minimal: [
        {
            featureType: "poi.business",
            stylers: [{ visibility: "off" }],
        },
        {
            featureType: "transit",
            elementType: "labels.icon",
            stylers: [{ visibility: "off" }],
        },
    ],
};

/**
 * Add a new marker to the given Google Maps object
 * @param {*} latLng GPS coordinates of the marker
 * @param {string} title Title to give to the marker
 * @param {*} icon Icon for the new marker 
 * @param {*} map Map object to which the new marker needs to be added
 * @param {string|int} key Key or identifier for the new marker in the global markers array
 */
function addMarkerToMap(latLng, title, icon, map, key)
{
    var newMarker = new google.maps.Marker({
        position: latLng,
        title: title,
        icon: icon
    });

    // Adding the new marker to the global markers array
    markers[key] = newMarker;
    
    markers[key].setMap(map);
}

/**
 * Remove a marker from its map, based either on the marker object itself or its key in the global markers array
 * @param {*} marker Marker object
 * @param {*} key Key of the marker in the global markers array
 */
function removeMarkerFromMap(marker, key)
{
    if(marker !== null)
    {
        marker.setMap(null);
    }
    
    if(key !== null)
    {
        markers[key].setMap(null);
    }
}

/**
 * Function used to geocode addresses through the Geocoding API of the Google Maps Platform
 * @param {string} address Address to geoencode 
 * @param {string} APIKey Key used to request the API
 * @returns Array of results corresponding to the request
 */
async function geocodeAddress(address, APIKey)
{
    let ret = [];
    
    // Preparing the address string to be URL compatible
    let query = address.replace(' ','+');

    // Making a request to the API
    let response = await fetch('https://maps.googleapis.com/maps/api/geocode/json?address='+query+'&key='+APIKey);
    let results = await response.json(); //extract JSON from the http response

    // Checking that API responded with a 200 HTTP code
    if(results['status'] === 'OK')
    {
        ret = results['results'];
    }
    else {
        console.log(results);
    }

    return ret;
}

/**
 * Function used to reverse geocode addresses from their GPS coordinates through the Geocoding API of the Google Maps Platform
 * @param {string} latlng GPS coordinates of the address that needs to be reverse geocoded (example of expected query : "40.714224,-73.961452") 
 * @param {string} APIKey Key used to request the API
 * @returns Array of results corresponding to the request
 */
async function reverseGeocodeAddress(latlng, APIKey)
{
    let ret = [];
    
    // Preparing the address string to be URL compatible
    let query = latlng.replace(' ','');

    // Making a request to the API
    let response = await fetch('https://maps.googleapis.com/maps/api/geocode/json?latlng='+query+'&key='+APIKey);
    let results = await response.json(); //extract JSON from the http response

    // Checking that API responded with a 200 HTTP code
    if(results['status'] === 'OK')
    {
        ret = results['results'];
    }
    else {
        console.log(results);
    }

    return ret;
}